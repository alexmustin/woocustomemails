<?php

$tab = isset( $_GET['tab'] ) ? $_GET['tab'] : $this->display_settings_key;
?>
<div class="wrap">

    <h2>Woo Custom Emails Settings</h2>

    <hr>

    <p class="howto"><?php _e('Settings for the Woo Custom Emails Per Product plugin.', 'woo_custom_emails_domain'); ?></p>

    <?php $this->plugin_options_tabs(); ?>

    <!-- // Display the Settings form with all sections and fields. // -->
    <form action="options.php" method="post">
        <?php
        wp_nonce_field();

        // Settings group.
        settings_fields($tab);

        // Group sections.
        do_settings_sections($tab);

        // Submit button.
        submit_button('Save Settings');
        ?>
    </form>

</div>
<?php
