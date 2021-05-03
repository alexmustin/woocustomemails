# Woo Custom Emails Per Product

> Easily add custom content for each product into the default WooCommerce receipt emails.

## Description

Easily add custom content for each product into the default WooCommerce receipt emails.

Use the new "Custom Emails" menu to create custom messages to be displayed. Then for each WooCommerce product, you can assign your Custom Email Message, select which Order Status email your custom message will be displayed in: "Order Processing," "Order On-Hold," and "Order Complete," and select which location inside the email template your custom content will appear: before or after the Order Details table, after the Order Meta, or after the Customer Details.

If you assign multiple products to have the same Custom Email Message, in the same Order Status email, the message will only be displayed once to avoid duplicates.

Great for product instructions to users, terms and conditions, linking to follow-up registration forms, advertising related products/events, or anything else!

## Installation

This section describes how to install the plugin and get it working.

1. Backup your site before installing a new plugin.
2. Upload the plugin files to the `/wp-content/plugins/woo-custom-emails-per-product/` directory, or install the plugin through the WordPress plugins screen directly.
3. Activate the plugin through the 'Plugins' screen in WordPress. You'll see a new Admin Menu item is added: "Custom Emails"
4. Go to 'Custom Emails' > 'Add New WCE Message' and Publish a message to be shown in your emails. Auto-generated content (like shortcodes, etc) will NOT appear in your email content.
5. Go to 'Products' and Edit one of your published WooCommerce products.
6. Scroll down to the 'Product Data' section and look for the new 'Custom Emails' tab.
7. Select which Order Status email should show your custom content.
8. Click the button for 'Select Message'. This will open a Search form below the button.
9. Type the name of your Custom Message into the search box. Results will begin to appear as you type.
10. Click the name of the Custom Email Message you'd like to use. It will show the Title of the custom message above the Search Box and the text will be Green to indicate you've selected the item.
11. Select where your custom content will display inside the email, by choosing one of the “Content Location” options.
12. Publish/Update the Product.
13. Now, try to purchase one of the products you have customized with this plugin — when you receive the email, you will see your custom content showing in the location you’ve chosen.

## Frequently Asked Questions

### Why can't I see the 'Custom Emails' tab?
At this time, the plugin is developed to only modify emails sent for the default WooCommerce "product" post type. You may be able to experiment and use this plugin with other product post types -- see the instructions under "Display for Other Product Types" under the WCE Settings page.

### Why don't the Custom Messages appear when I search?
There is a known bug with the Loom browser extension, where it interrupts the AJAX search. If you are using the Loom browser extension, please disable it while using Woo Custom Emails Per Product.

If this is not the case, there could be another plugin interfering with the AJAX search. Please check your browser console for any errors.

### Why does my content appear in the wrong location in the email?
When there are multiple products in an Order which have the same Custom Email message assigned, the first occurrence of a product with that Custom Email will get priority on which Content Location setting to use.

### What HTML tags are allowed?
You can use any HTML allowed in the Classic Editor.

### How do I request a feature or report a bug?
Have you found something wrong with the plugin? Thought of a helpful feature to add? Please see the Issues section on GitHub:
[https://github.com/alexmustin/woocustomemails/issues/](https://github.com/alexmustin/woocustomemails/issues/)

### Something Else?
If you are having any issues, please post in the Support Forum.

## Changelog

### 2.2.9 - (May 2, 2021)
* FIX: Fixes an error when saving on the WCE Settings page: "WordPress Error: Options page ___ not found in allowed options list"

### 2.2.8 - (Feb 24, 2021)
* FIX: Fixes a PHP 7.4 error: "Notice: Trying to access array offset on value of type bool in..."
* UPDATE: WCE Settings page: now with Tabs. The page now uses the default WordPress Settings API for a cleaner look.
* UPDATE: Completely refactored the code for the Admin area
* UPDATE: Compatible with WordPress 5.6.2 and WooCommerce 5.0

### 2.2.7 - (Dec 2, 2020)
* FIX: Resolves the error: "Undefined variable: output ..."
* FIX: Resolves the error: "Notice: Undefined index: show_in_admin_emails ..."

### 2.2.6 - (Nov 12, 2020)
* UPDATE: Compatible with WordPress 5.6 and WooCommerce 4.7.0
* ADD: New plugin page: "Assigned Messages" -- this new reporting page shows all your WooCommerce Products which have Custom Messages assigned. A table shows each Product and its associated Custom Messages, their titles, assigned order status, and assigned template location.
* ADD: New setting: "Include Custom Message in Admin Emails" -- the WCE Settings page has been updated with a new option allowing you to include the Custom Messages in Admin emails. When enabled, the Custom Message will appear in the same location in the email as it does for the customer.

### 2.2.5 - (Apr 25, 2020)
* UPDATE: Compatible with WordPress 5.5 and WooCommerce 4.0.1

### 2.2.4 - (Nov 29, 2019)
* FIX: Removes the error message: "Cannot redeclare log_message()..."

### 2.2.3 - (Nov 14, 2019)
* ADD: Adds compatibility for WordPress 5.3
* FIX: Stops the Custom Email Message from being shown in the Admin "New Order" email

### 2.2.2 - (Jan 11, 2019)
* FIX: Fixes error: "in_array() expects parameter 2 to be array, boolean given"

### 2.2.1 - (Dec 27, 2018)
* FIX: Adds compatibility for WordPress 5.02 and WooCommerce 3.5.3

### 2.2.0 - (Jul 9, 2018)
* ADD: Now assign a Custom Email Message for all 3 of the default Order Status emails! The plugin is backwards-compatible, so you will see your email settings from previous versions automatically applied.
* FIX: Fixed a CSS issue on the WCE Messages Admin Page.

### 2.1.4 - (Apr 11, 2018)
* FIX: Fixed issue where error messages were displaying in emails under certain circumstances.

### 2.1.3 - (Mar 21, 2018)
* FIX: Fixed issue where no messages were displaying in emails.

### 2.1.2 - (Mar 6, 2018)
* FIX: Fixed issue where "Warning:" error message was showing in emails.

### 2.1.1 - (Feb 26, 2018)
* FIX: Fixed issue with Variable Products not saving Custom Messages.

### 2.1.0 - (Feb 13, 2018)
* ADD: Adds a new 'WCE Settings' page under the 'Custom Emails' admin menu.
* ADD: New Settings option: "Show Version 1x Content" -- Adds a new "Show Content" button in the Product Data "Custom Emails" Tab -- click this button to display your old Version 1.x Custom Content, so you can Copy/Paste it into a new WCE Message.
* ADD: New Settings option: "Extra Display Classes" -- Allows you to specify the extra WooCommerce product classes assigned to the 'Custom Emails' Product Data Tab. Example: "Bookings": show_if_booking
* FIX: "After Order Meta" and "After Customer Details" settings have been updated so the content will appear in the expected location inside the email.

### 2.0.0 - (Feb 11, 2018)
* ADD: Adds a new Custom Post Type for you to create your Custom Email Messages.
* ADD: Moves WooCustomEmails fields into its own Tab in the Product Data section.
* ADD: AJAX Live Search to quickly and easily find your Custom Email Messages.
* FIX: No more duplicate messages in emails!

### 1.0.5 - (Jan 16, 2018)
* FIX: Fixes error message: 'Fatal error: Cannot redeclare woo_custom_emails_output_message()'

### 1.0.4 - (Dec 9, 2017)
* ADD: Added option to choose which Customer Order Status Email the content will appear in. Choose from "Processing," "On-Hold," or "Complete" status emails.
* FIX: Added support for br tags

### 1.0.3 - (Sept 8, 2017)
* FIX: Fixed issue of PHP code showing in the admin area

### 1.0.2 - (Sept 5, 2017)
* FIX: Added support for "align" attribute in img tags

### 1.0.1 - (Jul 6, 2017)
* First release!
