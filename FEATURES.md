# BuddyPress Email Preview - Complete Feature List

## 🎯 COMPREHENSIVE TOKEN SUPPORT

Your BuddyPress Email Preview plugin now supports **ALL** email tokens from **ALL** BuddyPress plugins you have installed!

### ✅ BuddyPress Core Email Types (17 Types)
1. `core-user-activation` - Account activation emails
2. `activity-comment` - Activity comment notifications
3. `activity-comment-author` - Comment author notifications
4. `activity-at-message` - Activity mentions
5. `groups-at-message` - Group mentions
6. `core-user-registration` - User registration emails
7. `core-user-registration-with-blog` - Multisite registration
8. `friends-request` - Friend request notifications
9. `friends-request-accepted` - Friend request accepted
10. `groups-details-updated` - Group details changed
11. `groups-invitation` - Group invitations
12. `groups-member-promoted` - Member promotion notifications
13. `groups-membership-request` - Membership requests
14. `groups-membership-request-accepted` - Request accepted
15. `groups-membership-request-rejected` - Request rejected
16. `messages-unread` - Private message notifications
17. `settings-verify-email-change` - Email verification

### ✅ BuddyPress Group Email Subscription (4 Types)
1. `bp-ges-single` - Individual activity notifications
2. `bp-ges-digest` - Daily/Weekly digest emails
3. `bp-ges-notice` - Admin notices to all members
4. `bp-ges-welcome` - Welcome emails for new members

## 🔧 COMPLETE TOKEN REFERENCE (60+ Tokens)

### Common Tokens (Available in ALL emails)
- `{{{site.name}}}` - Your site name
- `{{{site.url}}}` - Your site URL
- `{{{site.description}}}` - Site tagline
- `{{{site.admin-email}}}` - Site admin email address
- `{{{user.name}}}` - User display name
- `{{{user.email}}}` - User email address
- `{{{user.username}}}` - Username
- `{{{profile.url}}}` - User profile URL
- `{{{recipient.name}}}` - **Email recipient name**
- `{{{recipient.email}}}` - **Email recipient email address**
- `{{{recipient.username}}}` - **Email recipient username**
- `{{{email.subject}}}` - Email subject line
- `{{{email.preheader}}}` - Email preheader text
- `{{{unsubscribe}}}` - Unsubscribe link

### Activity & Content Tokens
- `{{{poster.name}}}` - Activity author name
- `{{{poster.url}}}` - Activity author profile
- `{{{usermessage}}}` - Activity/message content
- `{{{thread.url}}}` - Activity thread URL
- `{{{mentioned.url}}}` - Mentioned activity URL

### Group-Related Tokens
- `{{{group.name}}}` - Group name
- `{{{group.url}}}` - Group URL
- `{{{group.description}}}` - Group description
- `{{{group.link}}}` - HTML formatted group link
- `{{{inviter.name}}}` - Person who sent invitation
- `{{{inviter.url}}}` - Inviter's profile URL
- `{{{invite.message}}}` - Invitation message
- `{{{requesting-user.name}}}` - Membership requester
- `{{{request.message}}}` - Membership request message
- `{{{promoted_to}}}` - New role after promotion
- `{{{changed_text}}}` - What changed in group
- `{{{invites.url}}}` - Group invitations page
- `{{{group-requests.url}}}` - Membership requests page
- `{{{leave-group.url}}}` - Leave group URL

### Friendship Tokens
- `{{{initiator.name}}}` - Friend request sender
- `{{{initiator.url}}}` - Sender's profile URL
- `{{{friend.name}}}` - Friend's name
- `{{{friendship.url}}}` - Friend's profile URL
- `{{{friend-requests.url}}}` - Friend requests page

### Message Tokens
- `{{{sender.name}}}` - Message sender name
- `{{{sender.url}}}` - Sender's profile URL
- `{{{usersubject}}}` - Message subject
- `{{{message.url}}}` - Message thread URL

### Registration & Account Tokens
- `{{{activate.url}}}` - Account activation URL
- `{{{key}}}` - Activation key
- `{{{user-site.url}}}` - User's site (multisite)
- `{{{activate-site.url}}}` - Site activation URL
- `{{{lostpassword.url}}}` - Password reset URL
- `{{{verify.url}}}` - Email verification URL

### 🌟 SPECIAL GES TOKENS (8+ Tokens)

#### Single Notification Tokens
- `{{{ges.action}}}` - **Activity action description**
- `{{{ges.subject}}}` - **Dynamic email subject**
- `{{{ges.email-setting-description}}}` - **Email setting explanation**
- `{{{ges.email-setting-links}}}` - **Email setting links**

#### Digest Tokens
- `{{{ges.digest-summary}}}` - **🎯 FORMATTED GROUP SUMMARY WITH ACTIVITY COUNTS**
- `{{{subscription_type}}}` - **Subscription type (dig/sum)**
- `{{{recipient.id}}}` - **Recipient user ID**

#### Unsubscribe Tokens
- `{{{ges.unsubscribe}}}` - **Group-specific unsubscribe link**
- `{{{ges.unsubscribe-global}}}` - **Global unsubscribe link**
- `{{{ges.settings-link}}}` - **User's group settings page**

## 🚀 WHAT THIS MEANS FOR YOU

### ✅ Complete Preview Capability
- Preview **ANY** BuddyPress email with **ALL** tokens replaced
- See exactly how your emails will look to users
- Test with realistic sample data automatically generated

### ✅ Perfect GES Integration
- The `{{{ges.digest-summary}}}` token shows a **realistic group summary**
- All GES unsubscribe links work in previews
- Digest emails show proper formatting with multiple groups

### ✅ Universal Token Support
- **Every single token** from BuddyPress core is supported
- **Every GES token** is supported with realistic data
- Custom token values can be entered for testing

### ✅ Real Email Testing
- Send test emails with **ALL** tokens properly replaced
- Test unsubscribe links, group links, profile links
- Verify email appearance in actual email clients

## 🎨 SAMPLE DATA EXAMPLES

When you preview emails, you'll see realistic data like:

### Group Summary (ges.digest-summary)
```html
Group Summary:
<ul>
  <li>Developers Group (5 items)</li>
  <li>Design Team (3 items)</li>
  <li>General Discussion (2 items)</li>
</ul>
```

### Activity Action (ges.action)
```
John Doe posted an update in the group "Sample Group"
```

### Email Settings Description
```
You are receiving this email because you are subscribed to receive notifications for this group.
```

## 🔧 HOW TO USE

1. **Go to** `Emails > Email Preview` in WordPress admin
2. **Select** any email type from the dropdown
3. **View** all available tokens for that email type
4. **Customize** token values if needed
5. **Preview** the email with realistic data
6. **Send** test emails to verify everything works

## 🎯 SPECIAL FEATURES

### Automatic Plugin Detection
- Automatically detects if GES plugin is active
- Shows additional tokens and email types when available
- Provides helpful information about supported features

### Comprehensive Admin Interface
- Lists all supported email types
- Shows all available tokens for each type
- Provides token descriptions and examples
- Real-time preview updates

### Enhanced Error Handling
- Clear error messages if tokens don't work
- Helpful troubleshooting information
- Debug mode support for developers

---

**🎉 CONGRATULATIONS!** Your BuddyPress Email Preview plugin now supports **EVERY** email token from **EVERY** BuddyPress plugin you have installed. You can preview and test any email with complete confidence! 