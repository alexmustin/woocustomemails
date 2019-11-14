# woo_custom_emails_per_product

> Easily add custom content for each product into the default WooCommerce customer receipt emails.

## Description

Easily add custom content for each product into the default WooCommerce customer emails.

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
13. Now, try to purchase one of the products you have customized with this plugin — when you receive the customer email, you will see your custom content showing in the location you’ve chosen.

## Frequently Asked Questions

### Why can't I see the 'Custom Emails' tab when Editing a ____ Product?
At this time, the plugin is only designed to modify emails sent for the default WooCommerce "product" post type. You may be able to use the plugin with other product types by adding the proper CSS classes to the "Extra Display Classes" field under the WCE Settings page. To find the CSS classes which will make the tab visible: edit one of your custom products, scroll to the 'Product Data' table, inspect the code for the tab content you can see, and use that CSS class inside the 'Extra Display Classes' field on the WCE Settings page.
If you are using a WooCommerce add-on which creates its own custom emails (like Box Office, etc), this plugin will not be able to add content inside those emails. You may be able to find a paid plugin which can modify emails for your custom product type.

### Why does my content appear in the wrong location in the email?
When there are multiple products in an Order which have the same Custom Email message assigned, the first occurrence of a product with that Custom Email will get priority on which Content Location setting to use.

### I just upgraded from version 1.x. Where is my Custom Content?
In the Admin menu, go to Custom Emails > WCE Settings. Enable the option for "Show Version 1x Custom Content" and Save. Now when you go into a WooCommerce product you had previously customized in version 1.x, you will see a new button for "Show Content" -- if you click this button it will display your old Custom Content in a textarea field, so you can Copy/Paste it into a new WCE Message.

### What HTML tags are allowed in version 1.x?
For WooCustomEmails version 1.x, the following HTML tags are allowed in the "Custom Email Content" field:
- article
- section
- div
- h1, h2, h3, h4, h5, h6
- ul, ol, li
- p
- a
- b, strong
- i, em
- span
- hr
- img
- br

### Something Else?
If you are having any issues, please post in the Support Forum.

## Changelog

### 2.2.2 - (Jan 11, 2019)
* Fix: Fixes error: "in_array() expects parameter 2 to be array, boolean given"

### 2.2.1 - (Dec 27, 2018)
* Fix: Adds compatibility for WordPress 5.02 and WooCommerce 3.5.3

### 2.2.0 - (Jul 9, 2018)
* New: Now assign a Custom Email Message for all 3 of the default Order Status emails! The plugin is backwards-compatible, so you will see your email settings from previous versions automatically applied.
* Fix: Fixed a CSS issue on the WCE Messages Admin Page.

### 2.1.4 - (Apr 11, 2018)
* Fix: Fixed issue where error messages were displaying in emails under certain circumstances.

### 2.1.3 - (Mar 21, 2018)
* Fix: Fixed issue where no messages were displaying in emails.

### 2.1.2 - (Mar 6, 2018)
* Fix: Fixed issue where "Warning:" error message was showing in emails.

### 2.1.1 - (Feb 26, 2018)
* Fix: Fixed issue with Variable Products not saving Custom Messages.

### 2.1.0 - (Feb 13, 2018)
* New: Adds a new 'WCE Settings' page under the 'Custom Emails' admin menu.
* New: New Settings option: "Show Version 1x Content" -- Adds a new "Show Content" button in the Product Data "Custom Emails" Tab -- click this button to display your old Version 1.x Custom Content, so you can Copy/Paste it into a new WCE Message.
* New: New Settings option: "Extra Display Classes" -- Allows you to specify the extra WooCommerce product classes assigned to the 'Custom Emails' Product Data Tab. Example: "Bookings": show_if_booking
* Fix: "After Order Meta" and "After Customer Details" settings have been updated so the content will appear in the expected location inside the email.

### 2.0.0 - (Feb 11, 2018)
* New: Adds a new Custom Post Type for you to create your Custom Email Messages.
* New: Moves WooCustomEmails fields into its own Tab in the Product Data section.
* New: AJAX Live Search to quickly and easily find your Custom Email Messages.
* Fix: No more duplicate messages in emails!

### 1.0.5 - (Jan 16, 2018)
* Fix: Fixes error message: 'Fatal error: Cannot redeclare woo_custom_emails_output_message()'

### 1.0.4 - (Dec 9, 2017)
* New: Added option to choose which Customer Order Status Email the content will appear in. Choose from "Processing," "On-Hold," or "Complete" status emails.
* Fix: Added support for br tags

### 1.0.3 - (Sept 8, 2017)
* Fix: Fixed issue of PHP code showing in the admin area

### 1.0.2 - (Sept 5, 2017)
* Fix: Added support for "align" attribute in img tags

### 1.0.1 - (Jul 6, 2017)
* First release!
