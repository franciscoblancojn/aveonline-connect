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
?>
    <h1>
        Aveonline Connect
    </h1>
    <p>
        Aqui puedes configurar las conecxiones entre tu Tienda y Aveonline
    </p>
    <table class="AVCONNECT-table-swich">
        <tbody>
            <tr>
                <td>

                    <h3 class="AVCONNECT-swich-title">CRM</h3>
                </td>
                <td>
                    <label class="AVCONNECT-swich">
                        <div class="AVCONNECT-swich-box"></div>
                        <input class="AVCONNECT-swich-checkbox" type="checkbox" <?php echo $CRMActive ? "checked" : "" ?> name="CRM" id="CRM" />
                    </label>

                </td>
                <td>
                    <button class="AVCONNECT-btn button action" onclick="AVCONNECT_onCopy('text','Token copiado')">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                            <path d="M288 64C252.7 64 224 92.7 224 128L224 384C224 419.3 252.7 448 288 448L480 448C515.3 448 544 419.3 544 384L544 183.4C544 166 536.9 149.3 524.3 137.2L466.6 81.8C454.7 70.4 438.8 64 422.3 64L288 64zM160 192C124.7 192 96 220.7 96 256L96 512C96 547.3 124.7 576 160 576L352 576C387.3 576 416 547.3 416 512L416 496L352 496L352 512L160 512L160 256L176 256L176 192L160 192z" fill="currentColor" />
                        </svg>
                        Copiar Token
                    </button>

                </td>
            </tr>
        </tbody>
    </table>
<?php
    var_dump($apis);
}
