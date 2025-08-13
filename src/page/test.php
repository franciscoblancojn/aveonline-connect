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
    if($_POST['show']=="1"){
        var_dump($_POST);
    }
?>
    <form method="post">
        <input type="hidden" name="show" value="1">
        <button class="AVCONNECT-btn button action">
            Test
        </button>
    </form>
<?php
    // var_dump($apis);
    // echo "<br/>";
    // var_dump($_POST);
}
