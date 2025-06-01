<?php
/**
 * Plugin Name: BuddyPress Email Preview
 * Plugin URI: https://coresolstudio.com
 * Description: A comprehensive email previewer for BuddyPress emails with live preview, test sending, and customization options.
 * Version: 1.0.0
 * Author: Hassan Ali | Coresol Studio
 * Author URI: https://coresolstudio.com
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: buddypress-email-preview
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 * Network: false
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('BPEP_VERSION', '1.0.0');
define('BPEP_PLUGIN_FILE', __FILE__);
define('BPEP_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('BPEP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('BPEP_PLUGIN_BASENAME', plugin_basename(__FILE__));

/**
 * Main BuddyPress Email Preview Class
 */
class BuddyPress_Email_Preview {
    
    /**
     * Single instance of the class
     */
    private static $instance = null;
    
    /**
     * Get single instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }
    
    /**
     * Initialize the plugin
     */
    public function init() {
        // Check if BuddyPress is active
        if (!$this->is_buddypress_active()) {
            add_action('admin_notices', array($this, 'buddypress_missing_notice'));
            return;
        }
        
        // Load text domain
        load_plugin_textdomain('buddypress-email-preview', false, dirname(BPEP_PLUGIN_BASENAME) . '/languages');
        
        // Initialize hooks
        $this->init_hooks();
        
        // Include required files
        $this->includes();
    }
    
    /**
     * Check if BuddyPress is active
     */
    private function is_buddypress_active() {
        return class_exists('BuddyPress') && function_exists('bp_get_email_post_type');
    }
    
    /**
     * Check if BuddyPress Group Email Subscription is active
     */
    private function is_ges_active() {
        return function_exists('ass_group_notification_activity') || class_exists('BPGES_Queued_Item');
    }
    
    /**
     * Show notice if BuddyPress is not active
     */
    public function buddypress_missing_notice() {
        ?>
        <div class="notice notice-error">
            <p><?php _e('BuddyPress Email Preview requires BuddyPress to be installed and activated.', 'buddypress-email-preview'); ?></p>
        </div>
        <?php
    }
    
    /**
     * Initialize hooks
     */
    private function init_hooks() {
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        
        // AJAX hooks
        add_action('wp_ajax_bpep_preview_email', array($this, 'ajax_preview_email'));
        add_action('wp_ajax_bpep_send_test_email', array($this, 'ajax_send_test_email'));
        add_action('wp_ajax_bpep_get_email_content', array($this, 'ajax_get_email_content'));
        
        // Add custom columns to email post type
        add_filter('manage_' . bp_get_email_post_type() . '_posts_columns', array($this, 'add_email_columns'));
        add_action('manage_' . bp_get_email_post_type() . '_posts_custom_column', array($this, 'email_column_content'), 10, 2);
        
        // Add preview button to email edit screen
        add_action('add_meta_boxes_' . bp_get_email_post_type(), array($this, 'add_preview_meta_box'));
    }
    
    /**
     * Include required files
     */
    private function includes() {
        // Include admin class if in admin
        if (is_admin()) {
            require_once BPEP_PLUGIN_DIR . 'includes/class-admin.php';
            require_once BPEP_PLUGIN_DIR . 'includes/class-email-renderer.php';
            require_once BPEP_PLUGIN_DIR . 'includes/class-token-manager.php';
        }
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_submenu_page(
            'edit.php?post_type=' . bp_get_email_post_type(),
            __('Email Preview', 'buddypress-email-preview'),
            __('Email Preview', 'buddypress-email-preview'),
            'manage_options',
            'bp-email-preview',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Enqueue admin scripts and styles
     */
    public function enqueue_admin_scripts($hook) {
        // Only load on our admin pages
        if (strpos($hook, 'bp-email-preview') === false && 
            strpos($hook, bp_get_email_post_type()) === false) {
            return;
        }
        
        wp_enqueue_style(
            'bpep-admin-style',
            BPEP_PLUGIN_URL . 'assets/css/admin.css',
            array(),
            BPEP_VERSION
        );
        
        wp_enqueue_script(
            'bpep-admin-script',
            BPEP_PLUGIN_URL . 'assets/js/admin.js',
            array('jquery', 'wp-util'),
            BPEP_VERSION,
            true
        );
        
        wp_localize_script('bpep-admin-script', 'bpepAjax', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bpep_nonce'),
            'strings' => array(
                'preview_loading' => __('Loading preview...', 'buddypress-email-preview'),
                'send_test_loading' => __('Sending test email...', 'buddypress-email-preview'),
                'test_sent_success' => __('Test email sent successfully!', 'buddypress-email-preview'),
                'error_occurred' => __('An error occurred. Please try again.', 'buddypress-email-preview'),
            )
        ));
    }
    
    /**
     * Admin page content
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php _e('BuddyPress Email Preview', 'buddypress-email-preview'); ?></h1>
            
            <?php if ($this->is_ges_active()): ?>
            <div class="notice notice-info">
                <p>
                    <strong><?php _e('BuddyPress Group Email Subscription Detected!', 'buddypress-email-preview'); ?></strong>
                    <?php _e('This plugin now supports ALL GES email tokens including {{{ges.digest-summary}}}, {{{ges.action}}}, {{{ges.unsubscribe}}}, and many more with realistic sample data.', 'buddypress-email-preview'); ?>
                </p>
            </div>
            <?php endif; ?>
            
            <div class="notice notice-success">
                <p>
                    <strong><?php _e('Comprehensive Token Support:', 'buddypress-email-preview'); ?></strong>
                    <?php _e('This plugin supports ALL BuddyPress core email tokens plus tokens from installed plugins. Preview any email type with realistic sample data!', 'buddypress-email-preview'); ?>
                </p>
            </div>
            
            <div class="bpep-admin-container">
                <div class="bpep-sidebar">
                    <div class="bpep-card">
                        <h3><?php _e('Select Email Type', 'buddypress-email-preview'); ?></h3>
                        <select id="bpep-email-type" class="bpep-select">
                            <option value=""><?php _e('Choose an email type...', 'buddypress-email-preview'); ?></option>
                            <?php $this->render_email_type_options(); ?>
                        </select>
                    </div>
                    
                    <div class="bpep-card">
                        <h3><?php _e('Preview Options', 'buddypress-email-preview'); ?></h3>
                        <label>
                            <input type="radio" name="preview_mode" value="html" checked>
                            <?php _e('HTML Version', 'buddypress-email-preview'); ?>
                        </label><br>
                        <label>
                            <input type="radio" name="preview_mode" value="plaintext">
                            <?php _e('Plain Text Version', 'buddypress-email-preview'); ?>
                        </label>
                    </div>
                    
                    <div class="bpep-card">
                        <h3><?php _e('Test Email', 'buddypress-email-preview'); ?></h3>
                        <input type="email" id="bpep-test-email" placeholder="<?php _e('Enter email address', 'buddypress-email-preview'); ?>" class="bpep-input">
                        <button id="bpep-send-test" class="button button-secondary" disabled>
                            <?php _e('Send Test Email', 'buddypress-email-preview'); ?>
                        </button>
                    </div>
                    
                    <div class="bpep-card">
                        <h3><?php _e('Token Values', 'buddypress-email-preview'); ?></h3>
                        <div id="bpep-tokens">
                            <p class="description"><?php _e('Select an email type to see available tokens', 'buddypress-email-preview'); ?></p>
                        </div>
                    </div>
                    
                    <div class="bpep-card">
                        <h3><?php _e('Supported Email Types', 'buddypress-email-preview'); ?></h3>
                        <div style="max-height: 200px; overflow-y: auto;">
                            <h4><?php _e('BuddyPress Core:', 'buddypress-email-preview'); ?></h4>
                            <ul style="margin-left: 15px; font-size: 12px;">
                                <li>core-user-activation</li>
                                <li>activity-comment</li>
                                <li>activity-comment-author</li>
                                <li>activity-at-message</li>
                                <li>groups-at-message</li>
                                <li>core-user-registration</li>
                                <li>core-user-registration-with-blog</li>
                                <li>friends-request</li>
                                <li>friends-request-accepted</li>
                                <li>groups-details-updated</li>
                                <li>groups-invitation</li>
                                <li>groups-member-promoted</li>
                                <li>groups-membership-request</li>
                                <li>groups-membership-request-accepted</li>
                                <li>groups-membership-request-rejected</li>
                                <li>messages-unread</li>
                                <li>settings-verify-email-change</li>
                            </ul>
                            
                            <?php if ($this->is_ges_active()): ?>
                            <h4><?php _e('Group Email Subscription:', 'buddypress-email-preview'); ?></h4>
                            <ul style="margin-left: 15px; font-size: 12px;">
                                <li><strong>bp-ges-single</strong> - <?php _e('Individual notifications', 'buddypress-email-preview'); ?></li>
                                <li><strong>bp-ges-digest</strong> - <?php _e('Daily/Weekly digests', 'buddypress-email-preview'); ?></li>
                                <li><strong>bp-ges-notice</strong> - <?php _e('Admin notices', 'buddypress-email-preview'); ?></li>
                                <li><strong>bp-ges-welcome</strong> - <?php _e('Welcome emails', 'buddypress-email-preview'); ?></li>
                            </ul>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($this->is_ges_active()): ?>
                    <div class="bpep-card">
                        <h3><?php _e('GES Special Tokens', 'buddypress-email-preview'); ?></h3>
                        <div style="max-height: 150px; overflow-y: auto;">
                            <ul style="margin-left: 15px; font-size: 11px;">
                                <li><code>{{{ges.digest-summary}}}</code></li>
                                <li><code>{{{ges.action}}}</code></li>
                                <li><code>{{{ges.subject}}}</code></li>
                                <li><code>{{{ges.email-setting-description}}}</code></li>
                                <li><code>{{{ges.email-setting-links}}}</code></li>
                                <li><code>{{{ges.unsubscribe}}}</code></li>
                                <li><code>{{{ges.unsubscribe-global}}}</code></li>
                                <li><code>{{{ges.settings-link}}}</code></li>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="bpep-main">
                    <div class="bpep-preview-container">
                        <div class="bpep-preview-header">
                            <h3><?php _e('Email Preview', 'buddypress-email-preview'); ?></h3>
                            <div class="bpep-preview-actions">
                                <button id="bpep-refresh-preview" class="button">
                                    <?php _e('Refresh Preview', 'buddypress-email-preview'); ?>
                                </button>
                            </div>
                        </div>
                        <div id="bpep-preview-content">
                            <div class="bpep-placeholder">
                                <p><?php _e('Select an email type to see the preview', 'buddypress-email-preview'); ?></p>
                                <p class="description"><?php _e('All tokens will be replaced with realistic sample data automatically.', 'buddypress-email-preview'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Render email type options
     */
    private function render_email_type_options() {
        $email_types = $this->get_email_types();
        
        foreach ($email_types as $type => $data) {
            printf(
                '<option value="%s">%s</option>',
                esc_attr($type),
                esc_html($data['name'])
            );
        }
    }
    
    /**
     * Get all email types
     */
    private function get_email_types() {
        $email_types = array();
        
        // Get email type taxonomy terms
        $terms = get_terms(array(
            'taxonomy' => bp_get_email_tax_type(),
            'hide_empty' => false,
        ));
        
        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $email_types[$term->slug] = array(
                    'name' => $term->name,
                    'description' => $term->description,
                    'slug' => $term->slug,
                );
            }
        }
        
        return $email_types;
    }
    
    /**
     * AJAX handler for email preview
     */
    public function ajax_preview_email() {
        check_ajax_referer('bpep_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'buddypress-email-preview'));
        }
        
        $email_type = sanitize_text_field($_POST['email_type']);
        $preview_mode = sanitize_text_field($_POST['preview_mode']);
        $custom_tokens = isset($_POST['tokens']) ? $_POST['tokens'] : array();
        
        try {
            $renderer = new BPEP_Email_Renderer();
            $preview = $renderer->render_email_preview($email_type, $preview_mode, $custom_tokens);
            
            wp_send_json_success($preview);
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * AJAX handler for sending test email
     */
    public function ajax_send_test_email() {
        check_ajax_referer('bpep_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'buddypress-email-preview'));
        }
        
        $email_type = sanitize_text_field($_POST['email_type']);
        $test_email = sanitize_email($_POST['test_email']);
        $custom_tokens = isset($_POST['tokens']) ? $_POST['tokens'] : array();
        
        if (!is_email($test_email)) {
            wp_send_json_error(__('Invalid email address', 'buddypress-email-preview'));
        }
        
        try {
            $renderer = new BPEP_Email_Renderer();
            $result = $renderer->send_test_email($email_type, $test_email, $custom_tokens);
            
            if ($result) {
                wp_send_json_success(__('Test email sent successfully!', 'buddypress-email-preview'));
            } else {
                wp_send_json_error(__('Failed to send test email', 'buddypress-email-preview'));
            }
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * AJAX handler for getting email content
     */
    public function ajax_get_email_content() {
        check_ajax_referer('bpep_nonce', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('Insufficient permissions', 'buddypress-email-preview'));
        }
        
        $email_type = sanitize_text_field($_POST['email_type']);
        
        try {
            $token_manager = new BPEP_Token_Manager();
            $tokens = $token_manager->get_available_tokens($email_type);
            
            wp_send_json_success(array(
                'tokens' => $tokens
            ));
        } catch (Exception $e) {
            wp_send_json_error($e->getMessage());
        }
    }
    
    /**
     * Add custom columns to email post type
     */
    public function add_email_columns($columns) {
        $new_columns = array();
        
        foreach ($columns as $key => $value) {
            $new_columns[$key] = $value;
            
            if ($key === 'title') {
                $new_columns['email_type'] = __('Email Type', 'buddypress-email-preview');
                $new_columns['preview'] = __('Preview', 'buddypress-email-preview');
            }
        }
        
        return $new_columns;
    }
    
    /**
     * Email column content
     */
    public function email_column_content($column, $post_id) {
        switch ($column) {
            case 'email_type':
                $email_type = bp_email_get_type($post_id);
                if ($email_type) {
                    $term = get_term_by('slug', $email_type, bp_get_email_tax_type());
                    if ($term) {
                        echo esc_html($term->name);
                    }
                }
                break;
                
            case 'preview':
                printf(
                    '<a href="%s" class="button button-small">%s</a>',
                    esc_url(admin_url('edit.php?post_type=' . bp_get_email_post_type() . '&page=bp-email-preview&email_type=' . bp_email_get_type($post_id))),
                    __('Preview', 'buddypress-email-preview')
                );
                break;
        }
    }
    
    /**
     * Add preview meta box to email edit screen
     */
    public function add_preview_meta_box() {
        add_meta_box(
            'bpep-preview-metabox',
            __('Email Preview', 'buddypress-email-preview'),
            array($this, 'preview_meta_box_content'),
            bp_get_email_post_type(),
            'side',
            'high'
        );
    }
    
    /**
     * Preview meta box content
     */
    public function preview_meta_box_content($post) {
        $email_type = bp_email_get_type($post->ID);
        
        if ($email_type) {
            printf(
                '<p><a href="%s" class="button button-primary" target="_blank">%s</a></p>',
                esc_url(admin_url('edit.php?post_type=' . bp_get_email_post_type() . '&page=bp-email-preview&email_type=' . $email_type)),
                __('Preview This Email', 'buddypress-email-preview')
            );
        } else {
            echo '<p>' . __('Save the email first to enable preview.', 'buddypress-email-preview') . '</p>';
        }
    }
}

// Initialize the plugin
BuddyPress_Email_Preview::get_instance();

// Activation hook
register_activation_hook(__FILE__, function() {
    // Check if BuddyPress is active
    if (!class_exists('BuddyPress')) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_die(__('BuddyPress Email Preview requires BuddyPress to be installed and activated.', 'buddypress-email-preview'));
    }
}); 