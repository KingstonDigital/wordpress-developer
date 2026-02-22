
=== KDM Premium Vibrant Form ===

Contributors:      WordPress Telex
Tags:              block, form, contact, turnstile
Tested up to:      6.8
Stable tag:        0.1.0
License:           GPLv2 or later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

A premium, vibrant contact form block for WordPress with dark-themed styling, Cloudflare Turnstile bot protection, honeypot field, rate limiting, and two layout modes. Email delivery uses wp_mail(), so any SMTP plugin you have installed will handle routing automatically.

== Description ==

KDM Premium Vibrant Form is a beautifully designed, dark-themed contact form block that integrates directly into the WordPress Block Editor. It provides two layout modes — Compact Box (sidebar-style) and Wide Row (footer-style) — along with enterprise-grade security features.

**Features:**

* Two layout modes: Compact Box and Wide Row
* Cloudflare Turnstile bot protection with server-side verification
* Hidden honeypot field to trap bots
* Minimum input delay detection (rejects submissions that are too fast for a human)
* Transient-based rate limiting per hashed IP address (anonymized, daily rotating salt)
* One-time submission tokens to prevent double-submit attacks
* Origin/Referer validation to block cross-site form submissions
* Email header injection protection (newline stripping on all header-used values)
* Input length limits enforced on both client and server (name: 100, email: 254, phone: 30, message: 5000)
* Optional submission logging with anonymized IPs for monitoring
* Server-side attribute resolution — notification email and success message are never exposed in client-side markup
* CSP-compatible inline script loading via wp_add_inline_script
* Uses wp_mail() for email delivery — works automatically with any SMTP plugin
* AJAX-powered form submission with animated feedback and double-click prevention
* Fully customizable heading, description, success message, and notification email
* Dark-themed premium design with custom CSS tokens
* Sanitized and validated inputs on both client and server

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/telex-kdm-premium-form` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Configure Turnstile and rate limiting settings under Settings > KDM Form Settings.
4. Insert the "KDM Premium Vibrant Form" block in any post or page.

== Frequently Asked Questions ==

= How does email delivery work? =

The form uses the standard WordPress wp_mail() function. If you have an SMTP plugin installed (such as WP Mail SMTP, Post SMTP, or FluentSMTP), it will automatically handle all email routing through your configured provider. No additional SMTP setup is needed in this plugin.

= How do I enable Cloudflare Turnstile? =

Enter your Turnstile Site Key and Secret Key under Settings > KDM Form Settings. The widget will automatically appear in the form on the front end.

= What is the rate limiting feature? =

You can set a send delay (in seconds) under Settings > KDM Form Settings. This prevents the same IP address from submitting the form more than once within the specified interval.

= What is the minimum input delay? =

Bots typically fill and submit forms in under a second. The minimum input delay setting (default: 3 seconds) silently rejects any submission made faster than this threshold. You can adjust or disable this under Settings > KDM Form Settings.

= What is the submission token (double-submit prevention)? =

Each time the form is rendered, a unique one-time token is generated and embedded in a hidden field. When the form is submitted, the server verifies and consumes the token — preventing the same form from being submitted twice. After a successful submission, the user must refresh the page to get a new token.

= Can I enable logging for monitoring? =

Yes. Under Settings > KDM Form Settings, enable "Submission Logging". All rejected submissions (spam, rate limit, validation errors) and successful ones are logged to your PHP error log with anonymized IP addresses.

= How are IP addresses protected? =

IP addresses used for rate limiting are hashed with a daily rotating salt — raw IPs are never stored in the database. When logging is enabled, IP addresses are anonymized (last octet zeroed for IPv4, last 80 bits zeroed for IPv6).

= Where does the notification email go if none is set? =

If no notification email is configured on the block, the form will automatically send submissions to the WordPress admin email address (Settings > General > Administration Email Address).

= How does the honeypot field work? =

A hidden form field called "website_url" is included in the form markup but invisible to real users. Bots that auto-fill all fields will populate this hidden field, and the server will silently discard their submission without revealing that spam was detected.

== Screenshots ==

1. The form block in the editor with Compact Box layout.
2. The form on the front end with Wide Row layout.
3. The KDM Form Settings page in the WordPress admin.

== Changelog ==

= 0.1.0 =
* Initial release with full form, Turnstile, honeypot, and rate limiting support. Email via wp_mail().
