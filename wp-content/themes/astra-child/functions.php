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
?>