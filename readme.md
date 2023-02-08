# Custom Emails Per Product for WooCommerce

> Easily add custom content for each product into the default WooCommerce receipt emails.

## Description

Easily add custom content for each product into the default WooCommerce receipt emails.

Use the new "Custom Emails" menu to create custom messages to be displayed. Then for each WooCommerce product, you can assign your Custom Email Message, select which Order Status email your custom message will be displayed in: "Order Processing," "Order On-Hold," and "Order Complete," and select which location inside the email template your custom content will appear: before or after the Order Details table, after the Order Meta, or after the Customer Details.

If you assign multiple products to have the same Custom Email Message, in the same Order Status email, the message will only be displayed once to avoid duplicates.

Great for product instructions to users, terms and conditions, linking to follow-up registration forms, advertising related products/events, or anything else!

## Installation

This section describes how to install the plugin and get it working.

1. Backup your site before installing a new plugin.
2. Upload the plugin files to the `/wp-content/plugins/custom-emails-per-product-for-woocommerce/` directory, or install the plugin through the WordPress plugins screen directly.
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
There is a known bug with the Loom browser extension, where it interrupts the AJAX search. If you are using the Loom browser extension, please disable it while using WC Custom Emails Per Product.

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

### 1.0.0 - (Feb 7, 2023)
* NEW: First release under a new name!