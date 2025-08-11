<?php
function AVCONNECT_create_menu() {
	add_menu_page('Aveonline Connect', 'Aveonline Connect', 'administrator', __FILE__, 'AVCONNECT_settings_page' , plugins_url('../img/icon.png', __FILE__) );

    add_action( 'admin_init', 'register_AVCONNECT_settings' );
}
add_action('admin_menu', 'AVCONNECT_create_menu');

function register_AVCONNECT_settings() {
	//register our settings
	register_setting( 'AVCONNECT-settings-group', 'new_option_name' );
	register_setting( 'AVCONNECT-settings-group', 'some_other_option' );
	register_setting( 'AVCONNECT-settings-group', 'option_etc' );
}

function AVCONNECT_settings_page(){
    ?>
    <h1>
        Aveonline Connect
    </h1>
    
    <?php
}

