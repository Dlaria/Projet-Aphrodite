<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', 'http://localhost/Projet-Aphrodite/wp-content/themes/astra-child/style.css' );
}

// function setup_style()
// {
//    add_theme_support('wp-block-styles');
//    add_editor_style('style.css');
// }
// add_action('after_setup_theme', 'setup_style');

function include_carrousel_js_file() {
   wp_enqueue_script('carrousel_js', '../wp-content/themes/astra-child/js/ac_script.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'include_carrousel_js_file');

function get_product(){
   global $wpdb;
   $table_name = $wpdb->prefix . 'product';
   $cd_result = $wpdb->get_results('SELECT * FROM '.$table_name);
   return $cd_result;
}

?>