<?php
function AVCONNECT_create_menu()
{
    add_menu_page('Aveonline Connect', 'Aveonline Connect', 'administrator', __FILE__, 'AVCONNECT_settings_page', plugins_url('../img/icon.png', __FILE__));

    add_action('admin_init', 'AVCONNECT_register_settings');
}
add_action('admin_menu', 'AVCONNECT_create_menu');

function AVCONNECT_register_settings()
{
    //register our settings
    register_setting('AVCONNECT-settings-group', 'new_option_name');
    register_setting('AVCONNECT-settings-group', 'some_other_option');
    register_setting('AVCONNECT-settings-group', 'option_etc');
}

function AVCONNECT_settings_page()
{
    function randomToken()
    {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 40; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }
    $apis = CWWYA_get_option("apis");
    if ($_POST['save'] == "1") {
        for ($i = 0; $i < count(AVCONNECT_LIST_CONNECT); $i++) {
            $connect = AVCONNECT_LIST_CONNECT[$i];
            $active = $_POST[$connect];
            $key = null;
            foreach ($apis as $index => $e) {
                if ($e['name'] == AVCONNECT_KEY . $connect) {
                    $key = $index;
                    // break;
                }
            }
            if ($key  === null) {
                $newApi = [
                    'active' => $active,
                    'name' => AVCONNECT_KEY . $connect,
                    'hidden' => 'on',
                    //PENDING: change de url
                    'url' => "http://localhost:3005",
                    'function' => "AVCONNECT_action",
                    'token' => randomToken(),
                    'permission' => array(
                        "product_ready" => true,
                        "product_create" => true,
                        "product_update" => true,
                        "product_delete" => false,
                        "order_ready" => true,
                        "order_create" => true,
                        "order_update" => true,
                        "order_delete" => false,
                        "user_ready" => false,
                        "user_create" => false,
                        "user_update" => false,
                        "user_delete" => false,
                    )
                ];
                array_push(
                    $apis,
                    $newApi
                );
                CWWYA_alertConnect($newApi);
            } else {
                if ($apis[$key]['active'] != $active) {
                    $apis[$key]['active'] = $active;
                    if ($active == 'on') {
                        CWWYA_alertConnect($apis[$key]);
                    } else {
                        CWWYA_alertDisconnect($apis[$key]);
                    }
                }
            }
        }
        CWWYA_set_option("apis", $apis);
    }
    // CWWYA_set_option("apis", []);
    $is_generated = true;
?>
    <form method="post">
        <input type="hidden" name="save" value="1">
        <h1>
            Aveonline Connect
        </h1>
        <p>
            Aqui puedes configurar las conexiones entre tu Tienda y Aveonline
        </p>
        <table class="AVCONNECT-table-swich">
            <tbody>
                <?php
                for ($i = 0; $i < count(AVCONNECT_LIST_CONNECT); $i++) {
                    $connect = AVCONNECT_LIST_CONNECT[$i];
                    $api = (array_find($apis, function ($e) use ($connect) {
                        return $e['name'] == AVCONNECT_KEY . $connect;
                    })) ?? [];
                    $active = $api['active'] == 'on';
                    $token = $api['token'] ?? '';
                    $is_generated &= $token != '';
                ?>
                    <tr>
                        <td>

                            <h3 class="AVCONNECT-swich-title"><?= $connect ?></h3>
                        </td>
                        <td>
                            <label class="AVCONNECT-swich">
                                <div class="AVCONNECT-swich-box"></div>
                                <input
                                    class="AVCONNECT-swich-checkbox"
                                    type="checkbox" <?php echo $active ? "checked" : "" ?>
                                    name="<?= $connect ?>"
                                    id="<?= $connect ?>" />
                            </label>

                        </td>
                        <td>
                            <?php
                            if ($token) {
                            ?>
                                <div class="AVCONNECT-btn button action" onclick="AVCONNECT_onCopy('<?= $token ?>','Token copiado')">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                                        <path d="M288 64C252.7 64 224 92.7 224 128L224 384C224 419.3 252.7 448 288 448L480 448C515.3 448 544 419.3 544 384L544 183.4C544 166 536.9 149.3 524.3 137.2L466.6 81.8C454.7 70.4 438.8 64 422.3 64L288 64zM160 192C124.7 192 96 220.7 96 256L96 512C96 547.3 124.7 576 160 576L352 576C387.3 576 416 547.3 416 512L416 496L352 496L352 512L160 512L160 256L176 256L176 192L160 192z" fill="currentColor" />
                                    </svg>
                                    Copiar Token
                                </div>
                            <?php
                            }
                            ?>
                        </td>
                    </tr>
                <?php
                }
                ?>

            </tbody>
        </table>
        <br>
        <button class="AVCONNECT-btn button action">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640">
                <path d="M160 96C124.7 96 96 124.7 96 160L96 480C96 515.3 124.7 544 160 544L480 544C515.3 544 544 515.3 544 480L544 237.3C544 220.3 537.3 204 525.3 192L448 114.7C436 102.7 419.7 96 402.7 96L160 96zM192 192C192 174.3 206.3 160 224 160L384 160C401.7 160 416 174.3 416 192L416 256C416 273.7 401.7 288 384 288L224 288C206.3 288 192 273.7 192 256L192 192zM320 352C355.3 352 384 380.7 384 416C384 451.3 355.3 480 320 480C284.7 480 256 451.3 256 416C256 380.7 284.7 352 320 352z" fill="currentColor" />
            </svg>
            <?= $is_generated ? "Guardar" : "Generar Conexiones" ?>
        </button>
    </form>
<?php
    // var_dump($apis);
    // echo "<br/>";
    // var_dump($_POST);
}
