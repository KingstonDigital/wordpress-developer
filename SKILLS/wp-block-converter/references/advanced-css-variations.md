# Advanced CSS & Block Style Variations

This reference covers "Premium" UI patterns and the modular implementation of Block Style Variations.

## 1. Block Style Variations (Modular Pattern)

Avoid adding large CSS blocks for variations directly into `functions.php`. Instead, register the style and provide a path to a dedicated CSS file.

### PHP Registration (in `functions.php` or a dedicated file)

```php
/**
 * Register Style Variations for the Hero Block
 */
function my_namespace_register_hero_styles() {
    register_block_style(
        'my-namespace/hero',
        array(
            'name'         => 'glassmorphism',
            'label'        => __( 'Glassmorphism', 'my-namespace' ),
            'style_handle' => 'my-namespace-hero-glass-style',
        )
    );

    // Enqueue the specific CSS file ONLY when the variation is needed
    wp_register_style(
        'my-namespace-hero-glass-style',
        get_template_directory_uri() . '/blocks/hero/styles/glassmorphism.css',
        array(),
        '1.0.0'
    );
}
add_action( 'init', 'my_namespace_register_hero_styles' );
```

### Modular CSS (`styles/glassmorphism.css`)
WordPress automatically applies the class `.is-style-[name]`.

```css
.wp-block-my-namespace-hero.is-style-glassmorphism {
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
}
```

---

## 2. "Premium" UI Patterns

### Glassmorphism
Characterized by transparency, multi-layered borders, and background blur.

```scss
.premium-glass {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px) saturate(180%);
    -webkit-backdrop-filter: blur(10px) saturate(180%);
    border-radius: 12px;
    border: 1px solid rgba(255, 255, 255, 0.125);
}
```

### Micro-Animations
Subtle hover effects that make the block feel "alive".

```scss
.hover-lift {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    
    &:hover {
        transform: translateY(-8px) scale(1.02);
    }
}
```

### Mesh Gradients
Vibrant, dynamic backgrounds that feel modern.

```scss
.mesh-gradient {
    background-color: #ff9a9e;
    background-image: 
        radial-gradient(at 0% 0%, hsla(253,16%,7%,1) 0, transparent 50%), 
        radial-gradient(at 50% 0%, hsla(225,39%,30%,1) 0, transparent 50%), 
        radial-gradient(at 100% 0%, hsla(339,49%,30%,1) 0, transparent 50%);
}
```

---

## 3. Implementation Workflow for Variations

1.  **Draft the CSS**: Create a standalone `.scss` or `.css` file for the variation.
2.  **Register in PHP**: Use `register_block_style`.
3.  **Scoped Styling**: Ensure the variation styles nested within the block's root class and use the `.is-style-[name]` modifier.
4.  **Editor Preview**: WordPress automatically loads enqueued block styles in the editor, but ensure your `editorScript` mentions the dependencies if using complex build steps.
