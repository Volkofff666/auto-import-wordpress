<?php
/**
 * Theme Customizer
 */

function ai_customize_register($wp_customize) {
    // Color settings
    $wp_customize->add_section('ai_colors', [
        'title' => __('Theme Colors', 'auto-import'),
        'priority' => 30,
    ]);
    
    $wp_customize->add_setting('ai_primary_color', [
        'default' => '#0066FF',
        'sanitize_callback' => 'sanitize_hex_color',
    ]);
    
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'ai_primary_color', [
        'label' => __('Primary Color', 'auto-import'),
        'section' => 'ai_colors',
        'settings' => 'ai_primary_color',
    ]));
    
    // Footer settings
    $wp_customize->add_section('ai_footer', [
        'title' => __('Footer Settings', 'auto-import'),
        'priority' => 120,
    ]);
    
    $wp_customize->add_setting('ai_footer_text', [
        'default' => '',
        'sanitize_callback' => 'wp_kses_post',
    ]);
    
    $wp_customize->add_control('ai_footer_text', [
        'label' => __('Footer Text', 'auto-import'),
        'section' => 'ai_footer',
        'type' => 'textarea',
    ]);
}
add_action('customize_register', 'ai_customize_register');

// Output custom CSS
function ai_customizer_css() {
    $primary_color = get_theme_mod('ai_primary_color', '#0066FF');
    
    if ($primary_color !== '#0066FF') {
        ?>
        <style type="text/css">
            :root {
                --color-primary: <?php echo esc_attr($primary_color); ?>;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'ai_customizer_css');