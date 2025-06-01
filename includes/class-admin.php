<?php
/**
 * Admin Class
 * 
 * Handles additional admin functionality
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BPEP_Admin {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('admin_init', array($this, 'init'));
    }
    
    /**
     * Initialize admin functionality
     */
    public function init() {
        // Additional admin hooks can be added here
    }
    
    /**
     * Get email statistics
     */
    public function get_email_statistics() {
        $stats = array();
        
        // Count emails by type
        $email_types = get_terms(array(
            'taxonomy' => bp_get_email_tax_type(),
            'hide_empty' => false,
        ));
        
        if (!is_wp_error($email_types)) {
            foreach ($email_types as $type) {
                $count = wp_count_posts(bp_get_email_post_type());
                $stats[$type->slug] = array(
                    'name' => $type->name,
                    'count' => $count->publish ?? 0,
                );
            }
        }
        
        return $stats;
    }
} 