<?php

@ini_set('upload_max_size', '256M');
@ini_set('post_max_size', '256M');
@ini_set('max_execution_time', '300');

$version = 1.6;
/**
 * Define Constants
 */
define('CHILD_THEME_KINGSTON_DIGITAL', $version);

/**
 * Enqueue styles
 */
function child_enqueue_styles()
{
    // Parent theme styles
    wp_enqueue_style(
        'twentytwentyfive-parent-style',
        get_template_directory_uri() . '/style.css'
    );

    // Global custom CSS
    wp_enqueue_style('kingston-digital-custom-css', get_stylesheet_directory_uri() . '/css/front-page.css', array(), CHILD_THEME_KINGSTON_DIGITAL, 'all');
}
add_action('wp_enqueue_scripts', 'child_enqueue_styles', 15);

/**
 * Enqueue SEO Landing Page specific styles
 */
function kdm_enqueue_seo_landing_page_styles()
{
    // Only load the landing page styles if we are on the 'local-seo' page
    if (is_page('local-seo')) {
        // Enqueue the landing page stylesheet (minified for non-logged-in users)
        $css_file = (!is_user_logged_in() && file_exists(get_stylesheet_directory() . '/css/seo-landing-page.min.css'))
            ? '/css/seo-landing-page.min.css'
            : '/css/seo-landing-page.css';

        wp_enqueue_style(
            'kdm-seo-landing-style',
            get_stylesheet_directory_uri() . $css_file,
            array('twentytwentyfive-parent-style', 'kingston-digital-custom-css'),
            filemtime(get_stylesheet_directory() . $css_file)
        );

        // Google Fonts for SEO Landing Page
        wp_enqueue_style(
            'kdm-google-fonts',
            'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap',
            array(),
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'kdm_enqueue_seo_landing_page_styles', 20);

/**
 * Enqueue Toronto Baby Guide specific styles
 */
function tbg_enqueue_blog_styles()
{
    // Load the styles on the blog page or any other relevant page
    // Using an array to catch common slug variations
    if (is_page(array('toronto-baby-guide', 'toronto-baby-guide-blog'))) {
        wp_enqueue_style(
            'tbg-blog-style',
            get_stylesheet_directory_uri() . '/css/toronto-baby-guide.css',
            array('twentytwentyfive-parent-style'),
            file_exists(get_stylesheet_directory() . '/css/toronto-baby-guide.css') ? filemtime(get_stylesheet_directory() . '/css/toronto-baby-guide.css') : CHILD_THEME_KINGSTON_DIGITAL
        );


        // Google Fonts for Toronto Baby Guide
        wp_enqueue_style(
            'tbg-google-fonts',
            'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap',
            array(),
            null
        );
    }
}
add_action('wp_enqueue_scripts', 'tbg_enqueue_blog_styles', 21);


function custom_enqueue_scripts()
{
    // Register the script first
    wp_register_script('custom-script', get_stylesheet_directory_uri() . '/js/script.js', array(), '1.0', true);

    // Now enqueue the script
    wp_enqueue_script('custom-script');
}
add_action('wp_enqueue_scripts', 'custom_enqueue_scripts');


// add custom css files to the visual editor
function add_block_styles()
{
    // Default styles
    add_editor_style('/css/front-page.css');

    // SEO Landing Page styles
    add_editor_style('/css/seo-landing-page.css');

    // Toronto Baby Guide styles
    add_editor_style('/css/toronto-baby-guide.css');
}
add_action('after_setup_theme', 'add_block_styles');


// Add custom styles to wordpress core blocks 
function enqueue_custom_block_styles()
{
    // Google Fonts for Editor
    wp_enqueue_style(
        'kdm-google-fonts-editor',
        'https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:ital,wght@0,300;0,400;0,500;0,600;1,300&display=swap',
        array(),
        null
    );

    // Google Fonts for Toronto Baby Guide in Editor
    wp_enqueue_style(
        'tbg-google-fonts-editor',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=DM+Sans:wght@300;400;500&display=swap',
        array(),
        null
    );


    wp_enqueue_script(
        'custom-block-styles',
        get_stylesheet_directory_uri() . '/js/custom-block-styles.js',
        array('wp-blocks', 'wp-dom-ready', 'wp-edit-post'),
        '1.0.0',
        true
    );
}
add_action('enqueue_block_editor_assets', 'enqueue_custom_block_styles');

// Custom block style image overlay text  
register_block_style(
    'core/image',
    array(
        'name' => 'text-overlay',
        'label' => __('Text Overlay', 'kingston-digital'),
        'inline_style' => '.wp-block-image.is-style-text-overlay { }'
    )
);


add_filter('walker_nav_menu_start_el', 'wpse_226884_replace_hash', 999);

function wpse_226884_replace_hash($menu_item)
{
    if (strpos($menu_item, 'href="#"') !== false) {
        $menu_item = str_replace('href="#"', 'href="javascript:void(0);"', $menu_item);
    }
    return $menu_item;
}
