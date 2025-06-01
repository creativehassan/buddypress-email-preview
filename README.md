# BuddyPress Email Preview

A comprehensive email previewer for BuddyPress emails with live preview, test sending, and customization options.

## Description

BuddyPress Email Preview is a powerful WordPress plugin that allows you to preview and test BuddyPress emails before they are sent to users. This plugin provides a user-friendly interface to:

- Preview **ALL** BuddyPress email types in both HTML and plain text formats
- Support for **ALL** BuddyPress core tokens and plugin-specific tokens
- Customize email tokens with sample or custom values
- Send test emails to verify appearance and functionality
- View emails with BuddyPress styling applied
- Access preview functionality directly from the email edit screen
- **COMPREHENSIVE**: Full support for BuddyPress Group Email Subscription (GES) plugin emails

## Features

### 🎯 **Complete Email Preview**
- Real-time preview of **ALL** BuddyPress email types
- Support for both HTML and plain text formats
- Applies BuddyPress email styling and templates
- Responsive preview interface
- **NEW**: Supports ALL core BuddyPress email types (17+ types)

### 🔧 **Universal Token Management**
- **COMPREHENSIVE** token support for ALL BuddyPress email types
- Support for **ALL** BuddyPress core tokens (60+ tokens)
- **COMPLETE** recipient token support (`recipient.name`, `recipient.email`, `recipient.username`)
- Custom token value input for testing scenarios
- Automatic sample data generation for all tokens
- Real-time token replacement preview
- **COMPLETE**: Support for ALL GES custom tokens (8+ GES-specific tokens)

### 📧 **Advanced Test Email Sending**
- Send test emails to any email address
- Uses BuddyPress email system for accurate testing
- Custom token values in test emails
- Success/error feedback
- **NEW**: Enhanced support for plugin-specific email types

### 🎨 **Enhanced Admin Integration**
- Seamless integration with BuddyPress email management
- Additional columns in email post type listing
- Preview meta box in email edit screen
- Dedicated admin page for comprehensive testing
- **NEW**: Complete email type and token reference

### 📱 **Responsive Design**
- Mobile-friendly admin interface
- Optimized for various screen sizes
- Clean and intuitive user experience

### 🔌 **Complete Plugin Compatibility**
- **BuddyPress Group Email Subscription (GES)**: Full support for ALL email types and tokens
- Automatic detection of installed plugins
- Enhanced token support for third-party plugins
- **NEW**: Extensible architecture for future plugin support

## Supported Email Types

The plugin supports **ALL** BuddyPress email types including:

### BuddyPress Core (17 Email Types)
- **Activity Emails**: activity-comment, activity-comment-author, activity-at-message
- **Group Emails**: groups-at-message, groups-invitation, groups-membership-request, groups-member-promoted, groups-details-updated, groups-membership-request-accepted, groups-membership-request-rejected, groups-membership-request-accepted-by-admin, groups-membership-request-rejected-by-admin
- **Friendship Emails**: friends-request, friends-request-accepted
- **Message Emails**: messages-unread
- **Registration Emails**: core-user-registration, core-user-registration-with-blog, core-user-activation
- **Settings Emails**: settings-verify-email-change

### BuddyPress Group Email Subscription (4 Email Types)

When the GES plugin is detected, additional email types are supported:

- **bp-ges-single**: Individual activity notifications
- **bp-ges-digest**: Daily and weekly digest emails with group summaries
- **bp-ges-notice**: Group administrator notices to all members
- **bp-ges-welcome**: Welcome emails for new group members

## Complete Token Reference

### BuddyPress Core Tokens (60+ Tokens)

#### Common Tokens (Available in all emails)
- `{{{site.name}}}`, `{{{site.url}}}`, `{{{site.description}}}`, `{{{site.admin-email}}}`
- `{{{user.name}}}`, `{{{user.email}}}`, `{{{user.username}}}`
- `{{{profile.url}}}`
- `{{{recipient.name}}}`, `{{{recipient.email}}}`, `{{{recipient.username}}}`
- `{{{email.subject}}}`, `{{{email.preheader}}}`
- `{{{unsubscribe}}}`

#### Activity Tokens
- `{{{poster.name}}}`, `{{{poster.url}}}`
- `{{{usermessage}}}`, `{{{thread.url}}}`, `{{{mentioned.url}}}`

#### Group Tokens
- `{{{group.name}}}`, `{{{group.url}}}`, `{{{group.description}}}`
- `{{{inviter.name}}}`, `{{{inviter.url}}}`, `{{{invite.message}}}`
- `{{{requesting-user.name}}}`, `{{{request.message}}}`
- `{{{promoted_to}}}`, `{{{changed_text}}}`
- `{{{invites.url}}}`, `{{{group-requests.url}}}`, `{{{leave-group.url}}}`

#### Friendship Tokens
- `{{{initiator.name}}}`, `{{{initiator.url}}}`
- `{{{friend.name}}}`, `{{{friendship.url}}}`
- `{{{friend-requests.url}}}`

#### Message Tokens
- `{{{sender.name}}}`, `{{{sender.url}}}`
- `{{{usersubject}}}`, `{{{usermessage}}}`, `{{{message.url}}}`

#### Registration Tokens
- `{{{activate.url}}}`, `{{{key}}}`
- `{{{user-site.url}}}`, `{{{activate-site.url}}}`
- `{{{lostpassword.url}}}`, `{{{verify.url}}}`

### GES-Specific Tokens (8+ Tokens)

#### Single Notification Tokens
- `{{{ges.action}}}`: Activity action description
- `{{{ges.subject}}}`: Dynamic email subject
- `{{{ges.email-setting-description}}}`: Email setting description
- `{{{ges.email-setting-links}}}`: Email setting links

#### Digest Tokens
- `{{{ges.digest-summary}}}`: **Formatted group summary with activity counts**
- `{{{subscription_type}}}`: Type of subscription (dig/sum)
- `{{{recipient.id}}}`: Recipient user ID

#### Unsubscribe Tokens
- `{{{ges.unsubscribe}}}`: Group-specific unsubscribe link
- `{{{ges.unsubscribe-global}}}`: Global unsubscribe link
- `{{{ges.settings-link}}}`: Link to user's group settings

#### Group Tokens
- `{{{group.link}}}`: HTML formatted group link

## Installation

1. **Download** the plugin files
2. **Upload** the `buddypress-email-preview` folder to `/wp-content/plugins/`
3. **Activate** the plugin through the 'Plugins' menu in WordPress
4. **Navigate** to `Emails > Email Preview` in your WordPress admin

### Requirements

- WordPress 5.0 or higher
- BuddyPress plugin installed and activated
- PHP 7.4 or higher
- **Optional**: BuddyPress Group Email Subscription plugin for enhanced functionality

## Usage

### Basic Preview

1. Go to `Emails > Email Preview` in your WordPress admin
2. Select any email type from the dropdown (17+ core types supported)
3. Choose between HTML or Plain Text preview
4. View the rendered email with realistic sample data for ALL tokens

### GES Digest Preview

1. Ensure the BuddyPress Group Email Subscription plugin is active
2. Select "bp-ges-digest" from the email type dropdown
3. The preview will show a realistic digest summary with multiple groups
4. Customize the `{{{ges.digest-summary}}}` token to test different scenarios
5. **ALL** GES tokens are automatically populated with sample data

### Advanced Token Customization

1. Select any email type to load ALL available tokens
2. Enter custom values in any token input field
3. Preview updates automatically with your custom values
4. Test with realistic scenarios using comprehensive token support

### Comprehensive Test Email Sending

1. Select any email type and configure tokens as needed
2. Enter a valid email address in the test email field
3. Click "Send Test Email" to send a real email with ALL tokens replaced
4. Check the recipient's inbox for the test email

### Quick Preview from Email Edit

1. Edit any BuddyPress email post
2. Use the "Email Preview" meta box in the sidebar
3. Click "Preview This Email" to open the preview page
4. The email type will be automatically selected with ALL tokens loaded

## Technical Details

### Architecture

The plugin follows WordPress coding standards and uses:

- **Object-oriented PHP** for clean, maintainable code
- **AJAX** for seamless user interactions
- **WordPress hooks** for proper integration
- **BuddyPress APIs** for email handling
- **Comprehensive token system** supporting all BuddyPress plugins

### File Structure

```
buddypress-email-preview/
├── buddypress-email-preview.php    # Main plugin file
├── includes/
│   ├── class-admin.php             # Admin functionality
│   ├── class-email-renderer.php    # Email rendering logic
│   └── class-token-manager.php     # Comprehensive token management
├── assets/
│   ├── css/
│   │   └── admin.css               # Admin styles
│   └── js/
│       └── admin.js                # Admin JavaScript
└── README.md                       # This file
```

### Hooks and Filters

The plugin provides several hooks for customization:

```php
// Filter email preview content
add_filter('bpep_email_preview_content', 'custom_preview_content', 10, 3);

// Filter available tokens
add_filter('bpep_available_tokens', 'custom_tokens', 10, 2);

// Filter sample token values
add_filter('bpep_sample_token_value', 'custom_sample_value', 10, 2);
```

## Customization

### Adding Custom Tokens

You can add custom tokens for specific email types:

```php
function add_custom_email_tokens($tokens, $email_type) {
    if ($email_type === 'custom-email-type') {
        $tokens['custom.token'] = 'Custom token description';
    }
    return $tokens;
}
add_filter('bpep_available_tokens', 'add_custom_email_tokens', 10, 2);
```

### Custom Sample Values

Provide custom sample values for tokens:

```php
function custom_sample_values($value, $token) {
    if ($token === 'custom.token') {
        return 'Custom sample value';
    }
    return $value;
}
add_filter('bpep_sample_token_value', 'custom_sample_values', 10, 2);
```

### Customizing GES Digest Summary

You can customize the sample digest summary:

```php
function custom_ges_digest_summary($summary) {
    // Return your custom digest summary HTML
    return '<ul><li>Custom Group (10 items)</li></ul>';
}
add_filter('bpep_ges_sample_digest_summary', 'custom_ges_digest_summary');
```

## Troubleshooting

### Common Issues

**Plugin not appearing in admin menu**
- Ensure BuddyPress is installed and activated
- Check user permissions (requires `manage_options` capability)

**Email preview not loading**
- Check browser console for JavaScript errors
- Verify AJAX requests are not being blocked
- Ensure proper nonce verification

**Test emails not sending**
- Verify WordPress email configuration
- Check email address validity
- Review server email logs

**Tokens not working**
- Ensure the email type exists in your system
- Check that plugins are active for plugin-specific tokens
- Verify token format uses correct braces: `{{{token}}}` or `{{token}}`

**GES tokens not working**
- Ensure BuddyPress Group Email Subscription plugin is active
- Check that GES email types exist in your system
- Verify GES plugin version compatibility

### Debug Mode

Enable WordPress debug mode for detailed error information:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
```

## Contributing

We welcome contributions to improve the plugin:

1. **Fork** the repository
2. **Create** a feature branch
3. **Make** your changes
4. **Test** thoroughly with all email types
5. **Submit** a pull request

### Development Setup

1. Clone the repository
2. Install WordPress and BuddyPress
3. Optionally install BuddyPress Group Email Subscription
4. Activate the plugin
5. Test with ALL supported email types
6. Verify ALL tokens work correctly

## Changelog

### Version 1.0.0
- Initial release
- **COMPLETE** email preview functionality for ALL BuddyPress email types
- **COMPREHENSIVE** token management system (50+ tokens)
- Test email sending with full token support
- Admin interface integration
- Responsive design
- **FULL** BuddyPress Group Email Subscription plugin support (8+ GES tokens)
- **ENHANCED** token replacement system
- **REALISTIC** sample data for ALL tokens
- **UNIVERSAL** support for all BuddyPress core email types

## Support

For support and questions:

- **Author**: Hassan Ali | Coresol Studio
- **Website**: [https://coresolstudio.com](https://coresolstudio.com)
- **Email**: Contact through website

## License

This plugin is licensed under the GPL v2 or later.

```
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
```

## Credits

- Built for the BuddyPress community
- Developed by Hassan Ali | Coresol Studio
- Uses BuddyPress email system and APIs
- **COMPLETE** compatibility with BuddyPress Group Email Subscription plugin
- Supports **ALL** BuddyPress core email types and tokens
- Follows WordPress coding standards

---

**Made with ❤️ for the BuddyPress community - Now with COMPLETE token support!** 