<?php
/**
 * Twenty Twenty-Five Child theme functions and definitions
 */

function twentytwentyfive_child_enqueue_styles()
{
    wp_enqueue_style(
        'twentytwentyfive-parent-style',
        get_template_directory_uri() . '/style.css'
    );
    // Enqueue the child theme stylesheet
    $css_file = (!is_user_logged_in() && file_exists(get_stylesheet_directory() . '/style.min.css')) ? '/style.min.css' : '/style.css';

    wp_enqueue_style(
        'twentytwentyfive-child-style',
        get_stylesheet_directory_uri() . $css_file,
        array('twentytwentyfive-parent-style'),
        filemtime(get_stylesheet_directory() . $css_file)
    );
    wp_enqueue_style(
        'kdm-google-fonts',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'twentytwentyfive_child_enqueue_styles');

function twentytwentyfive_child_editor_styles()
{
    // Add Google Fonts to editor
    wp_enqueue_style(
        'kdm-google-fonts-editor',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap',
        array(),
        null
    );

    // Add custom block styles to editor so it matches frontend
    wp_enqueue_style(
        'twentytwentyfive-child-editor-style',
        get_stylesheet_directory_uri() . '/style.css',
        array(),
        filemtime(get_stylesheet_directory() . '/style.css')
    );
}
add_action('enqueue_block_editor_assets', 'twentytwentyfive_child_editor_styles');
