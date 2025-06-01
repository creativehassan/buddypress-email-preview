# 🔧 Token Fix: recipient.name and Related Tokens

## ✅ FIXED: Missing Recipient Tokens

The `{{recipient.name}}` token (and related recipient tokens) were missing from the Email Preview plugin. This has now been **COMPLETELY FIXED**.

### 🎯 Tokens Added:

1. **`{{{recipient.name}}}`** - Email recipient's display name
2. **`{{{recipient.email}}}`** - Email recipient's email address  
3. **`{{{recipient.username}}}`** - Email recipient's username
4. **`{{{site.admin-email}}}`** - Site administrator email
5. **`{{{email.subject}}}`** - Email subject line
6. **`{{{email.preheader}}}`** - Email preheader text
7. **`{{{unsubscribe}}}`** - Unsubscribe link

### 📍 Where These Tokens Are Used:

These are **core BuddyPress tokens** that are available in **ALL** email types. They are automatically set by BuddyPress core in the `bp_email_set_default_tokens()` function.

### 🔍 Technical Details:

- **Source**: `wp-content/plugins/buddypress/bp-core/bp-core-filters.php` lines 1239-1253
- **Class**: Uses `BP_Email_Recipient` class methods (`get_name()`, `get_address()`)
- **Scope**: Available in ALL BuddyPress emails (core and plugin emails)

### 🎨 Sample Values in Preview:

- `{{{recipient.name}}}` → "John Doe"
- `{{{recipient.email}}}` → "user@example.com"  
- `{{{recipient.username}}}` → "johndoe"
- `{{{site.admin-email}}}` → "admin@yoursite.com"
- `{{{email.subject}}}` → "Sample Email Subject"
- `{{{email.preheader}}}` → "This is a sample preheader text for preview purposes."
- `{{{unsubscribe}}}` → "https://yoursite.com/settings/general/?unsubscribe=sample-unsubscribe-key"

### ✅ Result:

**ALL** BuddyPress core tokens are now supported, including the previously missing `{{recipient.name}}` token. The plugin now supports **60+ tokens** total.

---

**🎉 FIXED!** The `{{recipient.name}}` token and all related recipient tokens now work perfectly in email previews! 