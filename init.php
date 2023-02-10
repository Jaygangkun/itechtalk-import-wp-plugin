<?php

/**
 * Plugin Name: Itechtalk Import
 * Plugin URI: https://woocommerce.com/
 * Description: Import products to be compared from csv
 * Version: 1.0.0
 * Author: Jay
 * Author URI: 
 * Text Domain: 
 * Domain Path: 
 * Requires at least: 5.8
 * Requires PHP: 7.2
 *
 * @package WooCommerce
 */


define('ITT_DIR', plugin_dir_path(__FILE__));
define('ITT_URL', plugin_dir_url(__FILE__));
define('ITT_ASSETS_URL', plugin_dir_url(__FILE__) . 'assets/');
define('ITT_UPLOADS_PATH', plugin_dir_path(__FILE__) . 'uploads/');

define('ITT_POST_TYPE', 'itt_product');

if( !class_exists('ITT_Settings') ){
    require_once ITT_DIR . 'classes/itt_settings.php';
    $settings = new ITT_Settings();
}

if( !class_exists('ITT_Ajax') ){
    require_once ITT_DIR . 'classes/itt_ajax.php';
    $ajax = new ITT_Ajax();
}

if( !class_exists('ITT_Log') ){
    require_once ITT_DIR . 'classes/itt_log.php';
}


add_filter('theme_page_templates', 'itt_add_page_template_to_dropdown');
add_filter('template_include', 'itt_change_page_template', 99);
add_action('wp_enqueue_scripts', 'itt_remove_style' );

$templateFile = ITT_DIR.'templates/itt-product-compare-page-template.php';
$templateFile = str_replace("\\", "/", $templateFile);
define('ITT_COMPARE_TEMPLATE_FILE', $templateFile);

/**
 * Add page templates.
 *
 * @param  array  $templates  The list of page templates
 *
 * @return array  $templates  The modified list of page templates
 */
function itt_add_page_template_to_dropdown($templates)
{
    $templates[ITT_COMPARE_TEMPLATE_FILE] = __('Itt Product Compare Page', 'text-domain');

    return $templates;
}

/**
 * Change the page template to the selected template on the dropdown
 * 
 * @param $template
 *
 * @return mixed
 */
function itt_change_page_template($template)
{
    if (is_page()) {
        $meta = get_post_meta(get_the_ID());

        if (!empty($meta['_wp_page_template'][0]) && $meta['_wp_page_template'][0] != $template) {
            $template = $meta['_wp_page_template'][0];
        }
    }

    return $template;
}

function itt_remove_style()
{
    // Change this "my-page" with your page slug
    if (is_page('my-page')) {
        $theme = wp_get_theme();

        $parent_style = $theme->stylesheet . '-style'; 

        wp_dequeue_style($parent_style);
        wp_deregister_style($parent_style);
        wp_deregister_style($parent_style . '-css');
    }
}

add_action( 'init', 'itt_rewrites_init' );
function itt_rewrites_init(){
    $args = array(
        'meta_key' => '_wp_page_template',
        'meta_value' => ITT_COMPARE_TEMPLATE_FILE
    );

    $pages = get_pages($args);

    if(count($pages) > 0) {
        add_rewrite_rule(
            'itt-compare/([a-zA-Z0-9-]+)[/]?$',
            'index.php?page_id='.$pages[0]->ID.'&products=$matches[1]',
            'top'
        );
    }    
}

add_filter( 'query_vars', 'itt_query_vars' );
function itt_query_vars( $query_vars ){
    $query_vars[] = 'products';
    return $query_vars;
}