<?php
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
   if (isset($_GET['page_id']) && $_GET['page_id'] == 127){
      wp_enqueue_style( 'parent-style', 'http://localhost/Projet-Aphrodite/wp-content/themes/astra-child/includes/css/boutique-style.css' );
   }else{
      wp_enqueue_style( 'parent-style', 'http://localhost/Projet-Aphrodite/wp-content/themes/astra-child/style.css' );
   }
}


function include_carrousel_js_file() {
   if (isset($_GET['page_id']) && $_GET['page_id'] == 127){
      wp_enqueue_script('boutique_js', '../wp-content/themes/astra-child/includes/js/ac_boutique_script.js', array('jquery'), false, true);
   }else{
      wp_enqueue_script('acceuil_js', '../wp-content/themes/astra-child/includes/js/ac_acceuil_script.js', array('jquery'), false, true);
   }
}
add_action('wp_enqueue_scripts', 'include_carrousel_js_file');

function get_product(){
   global $wpdb;
   $table_name = $wpdb->prefix . 'product';
   $cd_result = $wpdb->get_results('SELECT * FROM '.$table_name);
   return $cd_result;
}

?>