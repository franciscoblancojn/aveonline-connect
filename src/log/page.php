<?php
if (AVCONNECT_LOG) {
    function AVCONNECT_add_logAveonline_aveonline_option_page($admin_bar)
    {
        global $pagenow;
        $admin_bar->add_menu(
            array(
                'id' => AVCONNECT_KEY . "_LOG",
                'title' => AVCONNECT_KEY,
                'href' => get_site_url() . '/wp-admin/options-general.php?page=' . AVCONNECT_KEY . "_LOG"
            )
        );
    }

    function AVCONNECT_logAveonline_aveonline_option_page()
    {
        add_options_page(
            AVCONNECT_KEY . "_LOG",
            AVCONNECT_KEY . "_LOG",
            'manage_options',
            AVCONNECT_KEY . "_LOG",
            AVCONNECT_KEY . "_log_page",
        );
    }

    function AVCONNECT_log_page()
    {
        if ($_POST['clear-log'] == "1") {
            update_option(AVCONNECT_KEY . "log", "[]");
        }
        // var_dump(AVCONNECT_getCache("token"));
        $log = AVCONNECT_getLog();
?>
        <form method="post">
            <input type="hidden" name="clear-log" value="1">
            <button style="
                cursor: pointer;
                position: fixed;
                bottom: 1rem;
                right: 1rem;
                font-size: 2rem;
                padding: .25rem 1.5rem;
                background: #1d2327;
                border: 0;
                border-radius: .35rem;
                color: #f0f0f1;
            ">Borrar Log</button>
        </form>
        <h1>
            Solo se guardan las <?= AVCONNECT_N_MAX_LOG ?> peticiones
        </h1>
        <script>
            const json_log = <?= json_encode($log) ?>;
        </script>
        <pre>
            <?php var_dump(array_reverse($log)); ?>
        </pre>
<?php
    }
    add_action('admin_bar_menu', 'AVCONNECT_add_logAveonline_aveonline_option_page', 100);

    add_action('admin_menu', 'AVCONNECT_logAveonline_aveonline_option_page');
}
