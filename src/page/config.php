<?php
function AVCONNECT_create_menu()
{
    add_menu_page('Aveonline Connect', 'Aveonline Connect', 'administrator', __FILE__, 'AVCONNECT_settings_page', plugins_url('../img/icon.png', __FILE__));

    add_action('admin_init', 'register_AVCONNECT_settings');
}
add_action('admin_menu', 'AVCONNECT_create_menu');

function register_AVCONNECT_settings()
{
    //register our settings
    register_setting('AVCONNECT-settings-group', 'new_option_name');
    register_setting('AVCONNECT-settings-group', 'some_other_option');
    register_setting('AVCONNECT-settings-group', 'option_etc');
}

function AVCONNECT_settings_page()
{
    $apis = CWWYA_get_option("apis");

    $CRMActive = false;
    $CRM2Active = false;
?>
    <h1>
        Aveonline Connect
    </h1>
    <p>
        Aqui puedes configurar las conecxiones entre tu Tienda y Aveonline
    </p>
    <label class="AVCONNECT-swich">
        <h3 class="AVCONNECT-swich-title">CRM</h3>
        <div class="AVCONNECT-swich-box"></div>
        <input class="AVCONNECT-swich-checkbox" type="checkbox" <?php echo $CRMActive ? "checked" : "" ?> name="CRM" id="CRM" />
    </label>
    <br>
    <label class="AVCONNECT-swich">
        <h3 class="AVCONNECT-swich-title">CRM2</h3>
        <div class="AVCONNECT-swich-box"></div>
        <input class="AVCONNECT-swich-checkbox" type="checkbox" <?php echo $CRM2Active ? "checked" : "" ?> name="CRM2" id="CRM2" />
    </label>
<?php
    var_dump($apis);
}
