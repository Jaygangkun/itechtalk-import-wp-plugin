<?php

class ITT_Settings
{
    public function __construct() {
        add_action( 'admin_enqueue_scripts', [$this, 'admin_script'] );
        add_action('admin_menu', [$this, 'admin_menu_init']);
    }

    public function admin_menu_init() {
        add_menu_page(
            'Itechtalk Import',
            'Itechtalk Import',
            '',
            'itt'
        );

        add_submenu_page( 'itt', 'Import', 'Import', 'manage_options', 'itt_import', [$this, 'page_import']);
    }

    public function admin_script() {
        wp_enqueue_script( 'itt_import_script', ITT_ASSETS_URL . 'js/itt-import.js', array(), '1.0' );

        wp_enqueue_style( 'itt_admin_style', ITT_ASSETS_URL . 'css/itt-admin.css', array(), '1.0' );
    }

    public function page_import() {
        require_once( ITT_DIR . 'pages/import.php' );
    }

}