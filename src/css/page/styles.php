<?php
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style(
        'AVCONNECT-page-styles',
        plugin_dir_url(__FILE__) . 'styles.css',
        [],
        AVCONNECT_get_version()
    );
});
