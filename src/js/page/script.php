<?php
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_script(
        'AVCONNECT-page-script',
        plugin_dir_url(__FILE__) . 'script.js',
        ['jquery'],
        AVCONNECT_get_version(),
        true
    );
});
