<?php 

// Charger les styles du thème parent et du thème enfant
function chicdressing_enqueue_styles() {
    // Style du thème parent
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );

    // Style du thème enfant (optionnel si tu veux ajouter un fichier spécifique)
    wp_enqueue_style( 'Alexis-theme', get_stylesheet_directory_uri() . '/css/theme.css', array('parent-style') );
}
add_action( 'wp_enqueue_scripts', 'chicdressing_enqueue_styles' );

