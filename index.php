<?php
/*
Plugin Name: Aveonline Connect
Plugin URI: https://github.com/franciscoblancojn/aveonline-connect
Description: Integración de Aveonline con tu tienda de Wordpress.
Version: 1.0.0
Author: franciscoblancojn
Author URI: https://franciscoblanco.vercel.app/
License: GPL2+
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wc-aveonline-connect
*/
if (! defined('ABSPATH')) exit;

if (!function_exists('is_plugin_active'))
    require_once(ABSPATH . '/wp-admin/includes/plugin.php');

//AVCONNECT_
define("AVCONNECT_KEY", 'AVCONNECT');
define("AVCONNECT_LOG", true);
define("AVCONNECT_BASENAME", plugin_basename(__FILE__));
define("AVCONNECT_DIR", plugin_dir_path(__FILE__));
define("AVCONNECT_URL", plugin_dir_url(__FILE__));

function AVCONNECT_get_version()
{
    $plugin_data = get_plugin_data(__FILE__);
    $plugin_version = $plugin_data['Version'];
    return $plugin_version;
}


function AVCONNECT_log_dependencia(String $text)
{
?>
    <div class="notice notice-error is-dismissible">
        <p>
            <?= $text ?>
        </p>
    </div>
<?php
}

function AVCONNECT_required_validations()
{
    $requiredValidations = [
        [
            "slug"      => 'aveonline/index.php',
            "alt_slugs" => ['aveonline-master/index.php'],
            "min_version" => '3.2.0',
            "error" => ('Aveonline Connect requiere el plugin "Aveonline" versión 3.2.0 o superior, descarga el plugin <a target="_blank" href="https://github.com/franciscoblancojn/aveonline-shipping/archive/refs/heads/master.zip">aquí</a>')
        ],
        [
            "slug"      => 'connect-woo-with-your-api/connect-woo-with-your-api.php',
            "alt_slugs" => ['connect-woo-with-your-api-master/connect-woo-with-your-api.php'],
            "min_version" => '1.0.1',
            "error" => ('Aveonline Connect requiere el plugin "Connect Woo with your api" versión 1.0.1 o superior, descarga el plugin <a target="_blank" href="https://github.com/franciscoblancojn/connect-woo-with-your-api/archive/refs/heads/master.zip">aquí</a>')
        ],
        [
            "slug"      => 'woocommerce/woocommerce.php',
            "min_version" => '10.0.0',
            "error" => ('Aveonline Connect requiere el plugin "WooCommerce" versión 10.0.0 o superior')
        ],
        [
            "callback" => function () {
                return (is_callable('curl_init') &&
                    function_exists('curl_init') &&
                    function_exists('curl_close') &&
                    function_exists('curl_exec') &&
                    function_exists('curl_setopt_array'));
            },
            "error" => ('Aveonline Connect requiere "Curl" habilitado en el servidor')
        ],
    ];

    $sw = true;

    foreach ($requiredValidations as $validation) {
        $isValid = false;

        // Validación por callback (ej. curl)
        if (isset($validation['callback']) && is_callable($validation['callback'])) {
            $isValid = $validation['callback']();
        } else {
            // Validación de plugins
            $pluginFile = null;

            if (is_plugin_active($validation['slug'])) {
                $pluginFile = $validation['slug'];
            } elseif (isset($validation['alt_slugs'])) {
                foreach ($validation['alt_slugs'] as $alt) {
                    if (is_plugin_active($alt)) {
                        $pluginFile = $alt;
                        break;
                    }
                }
            }

            if ($pluginFile) {
                // Verificar versión
                $plugins = get_plugins();
                if (isset($plugins[$pluginFile])) {
                    $currentVersion = $plugins[$pluginFile]['Version'];
                    $minVersion     = $validation['min_version'] ?? null;

                    if ($minVersion) {
                        $isValid = version_compare($currentVersion, $minVersion, '>=');
                    } else {
                        $isValid = true;
                    }
                }
            }
        }

        if (!$isValid) {
            $error = $validation['error'] ?? 'Dependencia faltante';
            add_action('admin_notices', function () use ($error) {
                AVCONNECT_log_dependencia($error);
            });
            $sw = false;
        }
    }

    return $sw;
}
if (AVCONNECT_required_validations()) {
    require_once AVCONNECT_DIR . 'update.php';
    github_updater_plugin_wordpress([
        'basename' => AVCONNECT_BASENAME,
        'dir' => AVCONNECT_DIR,
        'file' => "index.php",
        'path_repository' => 'franciscoblancojn/aveonline-connect',
        'branch' => 'master',
        'token_array_split' => [
            "g",
            "h",
            "p",
            "_",
            "G",
            "4",
            "W",
            "E",
            "W",
            "F",
            "p",
            "V",
            "U",
            "E",
            "F",
            "V",
            "x",
            "F",
            "U",
            "n",
            "b",
            "M",
            "k",
            "P",
            "R",
            "x",
            "o",
            "f",
            "t",
            "Y",
            "8",
            "z",
            "j",
            "t",
            "4",
            "E",
            "x",
            "b",
            "i",
            "9"
        ]
    ]);
    require_once AVCONNECT_DIR . 'src/_index.php';
    register_deactivation_hook(__FILE__, 'AVCONNECT_desactive_plugin');
    register_uninstall_hook(__FILE__, 'AVCONNECT_desactive_plugin');
}
