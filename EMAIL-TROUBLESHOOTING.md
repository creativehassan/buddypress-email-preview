# 📧 Email Troubleshooting Guide

## 🚨 "Failed to send test email" - Complete Fix Guide

If you're getting "Failed to send test email" errors, this guide will help you diagnose and fix the issue.

## 🔍 Step 1: Check Email System Status

In the **BuddyPress Email Preview** admin page, look at the **Email System Status** section. You should see:

- ✅ **wp_mail function available** 
- ✅ **PHP mail function available**
- ✅ **BuddyPress email system available**
- ⚠️ **No SMTP plugin detected** (this is often the issue!)

## 🛠️ Step 2: Common Solutions

### Solution A: Install an SMTP Plugin (RECOMMENDED)

Most WordPress hosting providers don't properly configure PHP mail. Install one of these plugins:

1. **WP Mail SMTP** (Most popular)
   - Install from WordPress admin: Plugins > Add New > Search "WP Mail SMTP"
   - Configure with your email provider (Gmail, Outlook, etc.)

2. **Easy WP SMTP** (Simple alternative)
   - Install from WordPress admin: Plugins > Add New > Search "Easy WP SMTP"

3. **Post SMTP** (Advanced features)
   - Install from WordPress admin: Plugins > Add New > Search "Post SMTP"

### Solution B: Check WordPress Email Configuration

Test if WordPress can send emails at all:

1. Go to **Tools > Site Health** in WordPress admin
2. Look for email-related issues
3. Or install a plugin like "Check Email" to test basic email functionality

### Solution C: Server Configuration Issues

If you're on shared hosting, contact your hosting provider and ask:

- "Is PHP mail() function enabled?"
- "Are there any restrictions on sending emails from PHP?"
- "Do you recommend using SMTP instead of PHP mail?"

## 🔧 Step 3: Advanced Troubleshooting

### Enable WordPress Debug Mode

Add these lines to your `wp-config.php` file:

```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Then check `/wp-content/debug.log` for email-related errors.

### Test with Different Email Types

Try sending test emails with different BuddyPress email types:

1. **core-user-registration** (simple email)
2. **activity-comment** (activity-related email)
3. **bp-ges-digest** (if you have GES plugin)

### Check Email Headers and Content

The plugin now includes a **fallback email system** that will try multiple methods:

1. **BuddyPress email system** (primary method)
2. **WordPress wp_mail()** (fallback method)

If both fail, you'll get detailed error messages.

## 📋 Step 4: Specific Error Messages

### "WordPress email system is not working"
- Install an SMTP plugin (see Solution A above)
- Contact your hosting provider about email configuration

### "BuddyPress error: [specific error]"
- Check if BuddyPress is properly installed and activated
- Verify the email type exists in your system
- Try with a different email type

### "Fallback error: [specific error]"
- This means both BuddyPress and WordPress mail systems failed
- Definitely install an SMTP plugin
- Check server email configuration

### "Invalid email address"
- Make sure you're entering a valid email address
- Try with a different email address (like Gmail)

## ✅ Step 5: Verify the Fix

After implementing a solution:

1. Go back to **BuddyPress Email Preview**
2. Check the **Email System Status** - you should now see:
   - ✅ **SMTP Plugin: [Plugin Name]**
3. Try sending a test email again
4. Check your email inbox (including spam folder)

## 🎯 Quick Fix Checklist

- [ ] Install WP Mail SMTP plugin
- [ ] Configure SMTP settings with your email provider
- [ ] Test with a simple email type like "core-user-registration"
- [ ] Check spam folder in your email
- [ ] Verify Email System Status shows SMTP plugin active
- [ ] Try with different email addresses

## 🆘 Still Having Issues?

### Local Development (XAMPP, WAMP, etc.)
Local servers often can't send emails. This is normal! The plugin will work fine on your live website.

### Hosting-Specific Issues

**Shared Hosting**: Often blocks PHP mail - use SMTP plugin
**VPS/Dedicated**: May need to configure mail server - use SMTP plugin
**WordPress.com**: Email should work automatically
**WP Engine**: Supports email but SMTP plugin recommended

### Contact Support

If you're still having issues after trying these solutions:

1. Note which **Email System Status** items are failing
2. Note the exact error message you're getting
3. Mention your hosting provider
4. Include whether you've installed an SMTP plugin

---

## 🎉 Success!

Once your email system is working:

- ✅ Test emails will send successfully
- ✅ All BuddyPress email tokens will work perfectly
- ✅ You can preview any email type with confidence
- ✅ Real BuddyPress emails will also work properly

**The email preview plugin is working correctly - the issue is almost always with the server's email configuration, which is easily fixed with an SMTP plugin!** 