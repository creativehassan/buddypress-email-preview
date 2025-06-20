<?php
/**
 * Email Renderer Class
 * 
 * Handles email preview generation and test email sending
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BPEP_Email_Renderer {
    
    /**
     * Render email preview
     */
    public function render_email_preview($email_type, $preview_mode = 'html', $custom_tokens = array()) {
        if (empty($email_type)) {
            throw new Exception(__('Email type is required', 'buddypress-email-preview'));
        }
        
        // Get the email object
        $email = bp_get_email($email_type);
        if (is_wp_error($email)) {
            throw new Exception(__('Email type not found', 'buddypress-email-preview'));
        }
        
        // Get token manager
        $token_manager = new BPEP_Token_Manager();
        $tokens = $token_manager->get_sample_tokens($email_type, $custom_tokens);
        
        // Get email content based on preview mode
        if ($preview_mode === 'plaintext') {
            $content = $email->get_content_plaintext();
            $subject = $email->get_subject();
        } else {
            $content = $email->get_content_html();
            $subject = $email->get_subject();
            
            // Apply email template
            $template = $email->get_template();
            if ($template && strpos($template, '{{{content}}}') !== false) {
                $content = str_replace('{{{content}}}', $content, $template);
            }
        }
        
        // Replace tokens in content and subject
        $content = $this->replace_tokens_in_content($content, $tokens);
        $subject = $this->replace_tokens_in_content($subject, $tokens);
        
        // Apply email appearance settings for HTML
        if ($preview_mode === 'html') {
            $content = $this->apply_email_styling($content);
        }
        
        return array(
            'subject' => $subject,
            'content' => $content,
            'tokens_used' => $tokens,
            'preview_mode' => $preview_mode
        );
    }
    
    /**
     * Replace tokens in content with enhanced support for GES tokens
     */
    private function replace_tokens_in_content($content, $tokens) {
        // First, use BuddyPress built-in token replacement if available
        if (function_exists('bp_core_replace_tokens_in_text')) {
            $content = bp_core_replace_tokens_in_text($content, $tokens);
        }
        
        // Additional token replacement for any remaining tokens
        foreach ($tokens as $token => $value) {
            // Handle both {{{token}}} and {{token}} formats
            $content = str_replace('{{{' . $token . '}}}', $value, $content);
            $content = str_replace('{{' . $token . '}}', $value, $content);
        }
        
        return $content;
    }
    
    /**
     * Send test email with improved error handling and fallback
     */
    public function send_test_email($email_type, $test_email, $custom_tokens = array()) {
        if (empty($email_type) || empty($test_email)) {
            throw new Exception(__('Email type and test email address are required', 'buddypress-email-preview'));
        }
        
        // Validate email address
        if (!is_email($test_email)) {
            throw new Exception(__('Invalid email address provided', 'buddypress-email-preview'));
        }
        
        // Get token manager
        $token_manager = new BPEP_Token_Manager();
        $tokens = $token_manager->get_sample_tokens($email_type, $custom_tokens);
        
        // Try BuddyPress email system first
        $bp_result = $this->send_via_buddypress($email_type, $test_email, $tokens);
        
        if ($bp_result === true) {
            return true;
        }
        
        // If BuddyPress method fails, try fallback method
        $fallback_result = $this->send_via_fallback($email_type, $test_email, $tokens);
        
        if ($fallback_result === true) {
            return true;
        }
        
        // If both methods fail, throw an exception with detailed error
        $error_message = __('Failed to send test email. ', 'buddypress-email-preview');
        
        if (is_wp_error($bp_result)) {
            $error_message .= __('BuddyPress error: ', 'buddypress-email-preview') . $bp_result->get_error_message() . ' ';
        }
        
        if (is_wp_error($fallback_result)) {
            $error_message .= __('Fallback error: ', 'buddypress-email-preview') . $fallback_result->get_error_message();
        }
        
        throw new Exception($error_message);
    }
    
    /**
     * Send email via BuddyPress email system
     */
    private function send_via_buddypress($email_type, $test_email, $tokens) {
        try {
            // Create a test recipient
            $recipient = new BP_Email_Recipient($test_email);
            
            // For GES emails, we need to set up the GES tokens properly
            if (strpos($email_type, 'bp-ges-') === 0) {
                $this->setup_ges_environment($tokens);
            }
            
            // Add recipient tokens to the tokens array
            $tokens['recipient.name'] = $recipient->get_name();
            $tokens['recipient.email'] = $recipient->get_address();
            $tokens['recipient.username'] = $recipient->get_name(); // Fallback if username not available
            
            // Send the email using BuddyPress email system
            $result = bp_send_email($email_type, $recipient, array(
                'tokens' => $tokens
            ));
            
            // Clean up GES environment if it was set up
            if (strpos($email_type, 'bp-ges-') === 0) {
                $this->cleanup_ges_environment();
            }
            
            return $result;
            
        } catch (Exception $e) {
            return new WP_Error('bp_email_failed', $e->getMessage());
        }
    }
    
    /**
     * Send email via WordPress fallback method
     */
    private function send_via_fallback($email_type, $test_email, $tokens) {
        try {
            // Get the email object
            $email = bp_get_email($email_type);
            if (is_wp_error($email)) {
                return $email;
            }
            
            // Get email content
            $subject = $email->get_subject();
            $content = $email->get_content_html();
            
            // If no HTML content, use plain text
            if (empty($content)) {
                $content = $email->get_content_plaintext();
                $content = nl2br(esc_html($content));
            }
            
            // Apply email template
            $template = $email->get_template();
            if ($template && strpos($template, '{{{content}}}') !== false) {
                $content = str_replace('{{{content}}}', $content, $template);
            }
            
            // Replace tokens in content and subject
            $content = $this->replace_tokens_in_content($content, $tokens);
            $subject = $this->replace_tokens_in_content($subject, $tokens);
            
            // Apply email styling
            $content = $this->apply_email_styling($content);
            
            // Add test email notice
            $content = '<div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 10px; margin-bottom: 20px; border-radius: 4px; color: #856404;">' .
                      '<strong>' . __('Test Email Notice:', 'buddypress-email-preview') . '</strong> ' .
                      __('This is a test email sent from BuddyPress Email Preview plugin.', 'buddypress-email-preview') .
                      '</div>' . $content;
            
            // Set up email headers
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
                'From: ' . get_bloginfo('name') . ' <' . get_bloginfo('admin_email') . '>'
            );
            
            // Send the email
            $result = wp_mail($test_email, $subject, $content, $headers);
            
            return $result;
            
        } catch (Exception $e) {
            return new WP_Error('fallback_email_failed', $e->getMessage());
        }
    }
    
    /**
     * Setup GES environment for proper token handling
     */
    private function setup_ges_environment($tokens) {
        // Set up BuddyPress GES tokens if the plugin is active
        if (class_exists('BuddyPress') && isset($tokens['subscription_type'])) {
            if (!isset(buddypress()->ges_tokens)) {
                buddypress()->ges_tokens = array();
            }
            buddypress()->ges_tokens = array_merge(buddypress()->ges_tokens, $tokens);
        }
    }
    
    /**
     * Cleanup GES environment
     */
    private function cleanup_ges_environment() {
        if (class_exists('BuddyPress') && isset(buddypress()->ges_tokens)) {
            unset(buddypress()->ges_tokens);
        }
    }
    
    /**
     * Apply email styling
     */
    private function apply_email_styling($content) {
        // Get BuddyPress email appearance settings
        $appearance = bp_email_get_appearance_settings();
        
        // Basic email wrapper with BuddyPress styling
        $styled_content = sprintf(
            '<div style="background-color: %s; padding: 20px; font-family: Arial, sans-serif;">
                <div style="max-width: 600px; margin: 0 auto; background-color: %s; border-radius: 5px; overflow: hidden;">
                    <div style="background-color: %s; padding: 20px; text-align: center;">
                        <h1 style="color: %s; font-size: %spx; margin: 0;">%s</h1>
                    </div>
                    <div style="padding: 30px; background-color: %s; color: #333;">
                        %s
                    </div>
                    <div style="padding: 20px; background-color: #f5f5f5; text-align: center; font-size: 12px; color: #666;">
                        <p>This is a preview email generated by BuddyPress Email Preview plugin.</p>
                    </div>
                </div>
            </div>',
            $appearance['email_bg'],
            $appearance['body_bg'],
            $appearance['header_bg'],
            $appearance['header_text_color'],
            $appearance['header_text_size'],
            get_bloginfo('name'),
            $appearance['body_bg'],
            $content
        );
        
        return $styled_content;
    }
    
    /**
     * Get email post by type
     */
    private function get_email_post_by_type($email_type) {
        $args = array(
            'post_type' => bp_get_email_post_type(),
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'tax_query' => array(
                array(
                    'taxonomy' => bp_get_email_tax_type(),
                    'field' => 'slug',
                    'terms' => $email_type,
                ),
            ),
        );
        
        $posts = get_posts($args);
        return !empty($posts) ? $posts[0] : null;
    }
    
    /**
     * Format email content for preview
     */
    private function format_email_content($content, $is_html = true) {
        if (!$is_html) {
            return nl2br(esc_html($content));
        }
        
        // Basic HTML formatting for preview
        $content = wpautop($content);
        
        // Style links
        $content = preg_replace(
            '/<a([^>]*)>/i',
            '<a$1 style="color: #0073aa; text-decoration: none;">',
            $content
        );
        
        return $content;
    }
} 