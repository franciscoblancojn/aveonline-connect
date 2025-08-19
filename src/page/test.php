<?php
function AVCONNECT_test_create_menu()
{
    add_menu_page('Aveonline Connect test', 'Aveonline Connect test', 'administrator', __FILE__, 'AVCONNECT_test_settings_page', plugins_url('../img/icon.png', __FILE__));

    add_action('admin_init', 'AVCONNECT_test_register_settings');
}
add_action('admin_menu', 'AVCONNECT_test_create_menu');

function AVCONNECT_test_register_settings()
{
    //register our settings
    register_setting('AVCONNECT-settings-group', 'new_option_name');
    register_setting('AVCONNECT-settings-group', 'some_other_option');
    register_setting('AVCONNECT-settings-group', 'option_etc');
}

function AVCONNECT_test_settings_page()
{
    $api = new AVCONNECT_api_ave();
    if ($_POST['action'] == "auth") {
        // var_dump($api->auth());
        // echo json_encode($api->product->create(AVCONNECT_parseProductCreate(44)));
        // var_dump(AVCONNECT_parseProductUpdate(12));
        // echo json_encode($api->product->update(AVCONNECT_parseProductUpdate(12)));

        // echo json_encode($api->order->get());
        // echo json_encode($api->order->create(AVCONNECT_parseOrderCreate(193)));
        // var_dump(get_post_meta(117, AVCONNECT_KEY_ORDER_REF, true));
        echo json_encode($api->order->update(AVCONNECT_parseOrderUpdate(193)));
    }
?>
    <form method="post">
        <input type="hidden" name="action" value="auth">
        <button class="AVCONNECT-btn button action">
            Test Auth
        </button>
    </form>
<?php
    // var_dump($apis);
    // echo "<br/>";
    // var_dump($_POST);
}
