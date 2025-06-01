<?php
/**
 * Token Manager Class
 * 
 * Handles email token generation and management for all BuddyPress plugins
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class BPEP_Token_Manager {
    
    /**
     * Get available tokens for an email type
     */
    public function get_available_tokens($email_type) {
        $tokens = array();
        
        // Common tokens available in all emails
        $tokens = array_merge($tokens, $this->get_common_tokens());
        
        // Email type specific tokens
        switch ($email_type) {
            case 'activity-comment':
            case 'activity-comment-author':
                $tokens = array_merge($tokens, $this->get_activity_tokens());
                break;
                
            case 'activity-at-message':
            case 'groups-at-message':
                $tokens = array_merge($tokens, $this->get_mention_tokens());
                break;
                
            case 'friends-request':
            case 'friends-request-accepted':
                $tokens = array_merge($tokens, $this->get_friendship_tokens());
                break;
                
            case 'groups-invitation':
            case 'groups-membership-request':
            case 'groups-member-promoted':
            case 'groups-details-updated':
            case 'groups-membership-request-accepted':
            case 'groups-membership-request-rejected':
            case 'groups-membership-request-accepted-by-admin':
            case 'groups-membership-request-rejected-by-admin':
                $tokens = array_merge($tokens, $this->get_group_tokens());
                break;
                
            case 'messages-unread':
                $tokens = array_merge($tokens, $this->get_message_tokens());
                break;
                
            case 'core-user-registration':
            case 'core-user-registration-with-blog':
            case 'core-user-activation':
                $tokens = array_merge($tokens, $this->get_registration_tokens());
                break;
                
            case 'settings-verify-email-change':
                $tokens = array_merge($tokens, $this->get_email_change_tokens());
                break;
                
            // BuddyPress Group Email Subscription plugin tokens
            case 'bp-ges-single':
                $tokens = array_merge($tokens, $this->get_ges_single_tokens());
                break;
                
            case 'bp-ges-digest':
                $tokens = array_merge($tokens, $this->get_ges_digest_tokens());
                break;
                
            case 'bp-ges-notice':
                $tokens = array_merge($tokens, $this->get_ges_notice_tokens());
                break;
                
            case 'bp-ges-welcome':
                $tokens = array_merge($tokens, $this->get_ges_welcome_tokens());
                break;
        }
        
        return $tokens;
    }
    
    /**
     * Get sample tokens with values for preview
     */
    public function get_sample_tokens($email_type, $custom_tokens = array()) {
        $tokens = array();
        
        // Get available tokens
        $available_tokens = $this->get_available_tokens($email_type);
        
        // Generate sample values
        foreach ($available_tokens as $token => $description) {
            if (isset($custom_tokens[$token])) {
                $tokens[$token] = $custom_tokens[$token];
            } else {
                $tokens[$token] = $this->get_sample_token_value($token);
            }
        }
        
        return $tokens;
    }
    
    /**
     * Get common tokens available in all emails
     */
    private function get_common_tokens() {
        return array(
            'site.name' => __('Site name', 'buddypress-email-preview'),
            'site.url' => __('Site URL', 'buddypress-email-preview'),
            'site.description' => __('Site description', 'buddypress-email-preview'),
            'site.admin-email' => __('Site admin email', 'buddypress-email-preview'),
            'user.name' => __('User display name', 'buddypress-email-preview'),
            'user.email' => __('User email address', 'buddypress-email-preview'),
            'user.username' => __('Username', 'buddypress-email-preview'),
            'profile.url' => __('User profile URL', 'buddypress-email-preview'),
            'recipient.name' => __('Email recipient name', 'buddypress-email-preview'),
            'recipient.email' => __('Email recipient email address', 'buddypress-email-preview'),
            'recipient.username' => __('Email recipient username', 'buddypress-email-preview'),
            'email.subject' => __('Email subject', 'buddypress-email-preview'),
            'email.preheader' => __('Email preheader text', 'buddypress-email-preview'),
            'unsubscribe' => __('Unsubscribe link', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get activity-related tokens
     */
    private function get_activity_tokens() {
        return array(
            'poster.name' => __('Activity poster name', 'buddypress-email-preview'),
            'poster.url' => __('Activity poster profile URL', 'buddypress-email-preview'),
            'usermessage' => __('Activity content', 'buddypress-email-preview'),
            'thread.url' => __('Activity thread URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get mention-related tokens
     */
    private function get_mention_tokens() {
        return array(
            'poster.name' => __('Mention author name', 'buddypress-email-preview'),
            'poster.url' => __('Mention author profile URL', 'buddypress-email-preview'),
            'usermessage' => __('Mention content', 'buddypress-email-preview'),
            'mentioned.url' => __('Mentioned activity URL', 'buddypress-email-preview'),
            'group.name' => __('Group name (for group mentions)', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get friendship-related tokens
     */
    private function get_friendship_tokens() {
        return array(
            'initiator.name' => __('Friend request sender name', 'buddypress-email-preview'),
            'initiator.url' => __('Friend request sender profile URL', 'buddypress-email-preview'),
            'friend.name' => __('Friend name', 'buddypress-email-preview'),
            'friendship.url' => __('Friend profile URL', 'buddypress-email-preview'),
            'friend-requests.url' => __('Friend requests page URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get group-related tokens
     */
    private function get_group_tokens() {
        return array(
            'group.name' => __('Group name', 'buddypress-email-preview'),
            'group.url' => __('Group URL', 'buddypress-email-preview'),
            'group.description' => __('Group description', 'buddypress-email-preview'),
            'inviter.name' => __('Group inviter name', 'buddypress-email-preview'),
            'inviter.url' => __('Group inviter profile URL', 'buddypress-email-preview'),
            'invite.message' => __('Invitation message', 'buddypress-email-preview'),
            'invites.url' => __('Group invitations page URL', 'buddypress-email-preview'),
            'group-requests.url' => __('Group membership requests URL', 'buddypress-email-preview'),
            'requesting-user.name' => __('Membership request user name', 'buddypress-email-preview'),
            'promoted_to' => __('Promoted role', 'buddypress-email-preview'),
            'changed_text' => __('Changed group details', 'buddypress-email-preview'),
            'request.message' => __('Membership request message', 'buddypress-email-preview'),
            'leave-group.url' => __('Leave group URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get message-related tokens
     */
    private function get_message_tokens() {
        return array(
            'sender.name' => __('Message sender name', 'buddypress-email-preview'),
            'sender.url' => __('Message sender profile URL', 'buddypress-email-preview'),
            'usersubject' => __('Message subject', 'buddypress-email-preview'),
            'usermessage' => __('Message content', 'buddypress-email-preview'),
            'message.url' => __('Message thread URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get registration-related tokens
     */
    private function get_registration_tokens() {
        return array(
            'activate.url' => __('Account activation URL', 'buddypress-email-preview'),
            'key' => __('Activation key', 'buddypress-email-preview'),
            'user-site.url' => __('User site URL (multisite)', 'buddypress-email-preview'),
            'activate-site.url' => __('Site activation URL (multisite)', 'buddypress-email-preview'),
            'lostpassword.url' => __('Lost password URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get email change tokens
     */
    private function get_email_change_tokens() {
        return array(
            'verify.url' => __('Email verification URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get BuddyPress Group Email Subscription single notification tokens
     */
    private function get_ges_single_tokens() {
        return array(
            'ges.action' => __('Activity action description', 'buddypress-email-preview'),
            'ges.subject' => __('Email subject', 'buddypress-email-preview'),
            'ges.email-setting-description' => __('Email setting description', 'buddypress-email-preview'),
            'ges.email-setting-links' => __('Email setting links', 'buddypress-email-preview'),
            'ges.unsubscribe-global' => __('Global unsubscribe link', 'buddypress-email-preview'),
            'ges.unsubscribe' => __('Group-specific unsubscribe link', 'buddypress-email-preview'),
            'ges.settings-link' => __('Link to user group settings page', 'buddypress-email-preview'),
            'usermessage' => __('Activity content', 'buddypress-email-preview'),
            'thread.url' => __('Activity thread URL', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get BuddyPress Group Email Subscription digest tokens
     */
    private function get_ges_digest_tokens() {
        return array(
            'ges.digest-summary' => __('Group digest summary with activity counts', 'buddypress-email-preview'),
            'ges.subject' => __('Digest email subject', 'buddypress-email-preview'),
            'ges.settings-link' => __('Link to user group settings page', 'buddypress-email-preview'),
            'subscription_type' => __('Type of subscription (dig/sum)', 'buddypress-email-preview'),
            'recipient.id' => __('Recipient user ID', 'buddypress-email-preview'),
            'usermessage' => __('Digest content body', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get BuddyPress Group Email Subscription notice tokens
     */
    private function get_ges_notice_tokens() {
        return array(
            'ges.subject' => __('Notice email subject', 'buddypress-email-preview'),
            'group.name' => __('Group name', 'buddypress-email-preview'),
            'group.link' => __('Group link with HTML', 'buddypress-email-preview'),
            'group.url' => __('Group URL', 'buddypress-email-preview'),
            'usermessage' => __('Notice message content', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get BuddyPress Group Email Subscription welcome tokens
     */
    private function get_ges_welcome_tokens() {
        return array(
            'ges.subject' => __('Welcome email subject', 'buddypress-email-preview'),
            'ges.settings-link' => __('Link to user group settings page', 'buddypress-email-preview'),
            'ges.unsubscribe' => __('Group-specific unsubscribe link', 'buddypress-email-preview'),
            'ges.unsubscribe-global' => __('Global unsubscribe link', 'buddypress-email-preview'),
            'usermessage' => __('Welcome message content', 'buddypress-email-preview'),
        );
    }
    
    /**
     * Get sample value for a token
     */
    private function get_sample_token_value($token) {
        $current_user = wp_get_current_user();
        $site_url = home_url();
        
        switch ($token) {
            case 'site.name':
                return get_bloginfo('name');
                
            case 'site.url':
                return $site_url;
                
            case 'site.description':
                return get_bloginfo('description');
                
            case 'site.admin-email':
                return get_bloginfo('admin_email');
                
            case 'user.name':
            case 'poster.name':
            case 'sender.name':
            case 'initiator.name':
            case 'friend.name':
            case 'inviter.name':
            case 'requesting-user.name':
                return $current_user->display_name ?: 'John Doe';
                
            case 'user.email':
                return $current_user->user_email ?: 'user@example.com';
                
            case 'user.username':
                return $current_user->user_login ?: 'johndoe';
                
            case 'profile.url':
            case 'poster.url':
            case 'sender.url':
            case 'initiator.url':
            case 'friendship.url':
            case 'inviter.url':
                return $site_url . '/members/johndoe/';
                
            case 'usermessage':
                return 'This is a sample message content for preview purposes. It could be an activity update, comment, or any other user-generated content.';
                
            case 'usersubject':
                return 'Sample Message Subject';
                
            case 'thread.url':
            case 'mentioned.url':
                return $site_url . '/activity/p/123/';
                
            case 'friend-requests.url':
                return $site_url . '/members/johndoe/friends/requests/';
                
            case 'group.name':
                return 'Sample Group Name';
                
            case 'group.url':
                return $site_url . '/groups/sample-group/';
                
            case 'group.description':
                return 'This is a sample group description.';
                
            case 'invite.message':
                return 'You have been invited to join our group!';
                
            case 'request.message':
                return 'I would like to join this group because I am interested in the topics discussed here.';
                
            case 'invites.url':
                return $site_url . '/members/johndoe/invites/';
                
            case 'group-requests.url':
                return $site_url . '/groups/sample-group/admin/membership-requests/';
                
            case 'leave-group.url':
                return $site_url . '/groups/sample-group/leave-group/';
                
            case 'message.url':
                return $site_url . '/members/johndoe/messages/view/123/';
                
            case 'promoted_to':
                return 'Administrator';
                
            case 'changed_text':
                return 'Group description has been updated.';
                
            case 'activate.url':
                return $site_url . '/activate/?key=sample-activation-key';
                
            case 'key':
                return 'sample-activation-key-123456';
                
            case 'user-site.url':
                return $site_url . '/site/johndoe/';
                
            case 'activate-site.url':
                return $site_url . '/activate-site/?key=sample-site-key';
                
            case 'lostpassword.url':
                return wp_lostpassword_url();
                
            case 'verify.url':
                return $site_url . '/settings/general/?verify-email=sample-verification-key';
                
            // BuddyPress Group Email Subscription tokens
            case 'ges.action':
                return 'John Doe posted an update in the group "Sample Group"';
                
            case 'ges.subject':
                return 'New activity in Sample Group';
                
            case 'ges.email-setting-description':
                return 'You are receiving this email because you are subscribed to receive notifications for this group.';
                
            case 'ges.email-setting-links':
                return 'To change your email settings for this group, visit: ' . $site_url . '/groups/sample-group/admin/';
                
            case 'ges.unsubscribe-global':
                return $site_url . '/members/johndoe/groups/?bpass-action=unsubscribe&access_key=sample-key';
                
            case 'ges.unsubscribe':
                return $site_url . '/groups/sample-group/?bpass-action=unsubscribe&access_key=sample-key';
                
            case 'ges.settings-link':
                return $site_url . '/members/johndoe/groups/';
                
            case 'ges.digest-summary':
                return $this->get_sample_digest_summary();
                
            case 'subscription_type':
                return 'dig';
                
            case 'recipient.id':
                return $current_user->ID ?: 1;
                
            case 'group.link':
                return '<a href="' . $site_url . '/groups/sample-group/">Sample Group Name</a>';
                
            case 'recipient.name':
                return $current_user->display_name ?: 'John Doe';
                
            case 'recipient.email':
                return $current_user->user_email ?: 'user@example.com';
                
            case 'recipient.username':
                return $current_user->user_login ?: 'johndoe';
                
            case 'email.subject':
                return 'Sample Email Subject';
                
            case 'email.preheader':
                return 'This is a sample preheader text for preview purposes.';
                
            case 'unsubscribe':
                return $site_url . '/settings/general/?unsubscribe=sample-unsubscribe-key';
                
            default:
                return 'Sample Value';
        }
    }
    
    /**
     * Generate sample digest summary content
     */
    private function get_sample_digest_summary() {
        $site_url = home_url();
        
        // Sample digest summary that matches the GES plugin format
        $summary = 'Group Summary:' . "\n";
        $summary .= '<ul style="margin: 0; padding: 0; list-style: none;">' . "\n";
        $summary .= '<li style="margin-bottom: 10px; padding: 8px; background: #f9f9f9; border-left: 3px solid #0073aa;">';
        $summary .= '<a href="' . $site_url . '/groups/developers-group/" style="color: #0073aa; text-decoration: none; font-weight: bold;">Developers Group</a> (5 items)';
        $summary .= '</li>' . "\n";
        $summary .= '<li style="margin-bottom: 10px; padding: 8px; background: #f9f9f9; border-left: 3px solid #0073aa;">';
        $summary .= '<a href="' . $site_url . '/groups/design-team/" style="color: #0073aa; text-decoration: none; font-weight: bold;">Design Team</a> (3 items)';
        $summary .= '</li>' . "\n";
        $summary .= '<li style="margin-bottom: 10px; padding: 8px; background: #f9f9f9; border-left: 3px solid #0073aa;">';
        $summary .= '<a href="' . $site_url . '/groups/general-discussion/" style="color: #0073aa; text-decoration: none; font-weight: bold;">General Discussion</a> (2 items)';
        $summary .= '</li>' . "\n";
        $summary .= '</ul>';
        
        return $summary;
    }
} 