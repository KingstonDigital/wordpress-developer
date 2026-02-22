---
name: wp-block-converter
description: Guide for converting static HTML and CSS into native, modern WordPress Gutenberg blocks. Use this skill when the user provides HTML/CSS markup and requests it to be functioning as a custom WordPress block, or when you need to scaffold a new block based on existing design assets.
---

# WordPress Block Converter

This skill provides a standardized workflow for converting static HTML/CSS into native WordPress Gutenberg blocks using React (JSX) and `@wordpress/scripts`.

## Core Principles

1.  **Block Scope**: All block styling must be scoped to the block's specific class name to prevent CSS leakage.
2.  **Native Components**: Favor native WordPress components (`RichText`, `InnerBlocks`, `MediaUpload`, `InspectorControls`) over custom implementations for standard functionality.
3.  **Modern Standards**: Use `block.json` for registration, `edit.js` for the editor interface, and `save.js` for the frontend markup.

## Workflow: HTML/CSS to WordPress Block

When starting a conversion, follow these steps systematically:

### 1. Analyze the Static Source

Review the provided HTML and CSS to identify:
-   **Static Elements**: Structure, wrappers, decorative icons.
-   **Dynamic Attributes**: Text content, images, links, colors that the user should be able to edit in the WordPress Editor.
-   **Inner Blocks**: Sections that should contain arbitrary nested blocks.

### 2. Scaffold the Block Structure

Create the standard Block API v3 file structure.
*If the skill's companion script `scripts/scaffold_block.py` is available, use it to generate the boilerplate.*

Required files:
-   `block.json`: Metadata, attributes, and file references.
-   `index.js`: Block registration point.
-   `edit.js`: The editor interface component.
-   `save.js`: The frontend saved markup component.
-   `style.scss`: Frontend styles.
-   `editor.scss`: Editor-specific styles (optional, often unneeded if `style.scss` suffices).

### 3. Define the `block.json`

Register the block and define its attributes based on the analysis in step 1.

**Key considerations:**
-   **Attributes**: Define type, source, and selector for each dynamic piece of content to ensure proper serialization/deserialization.
-   **Supports**: Enable relevant supports (e.g., `align`, `color`, `spacing`) to leverage built-in WordPress styling controls.

*See `references/block-json-examples.md` for attribute schemas and `supports` configurations.*

### 4. Implement `edit.js` (The Editor Interface)

Convert the static HTML to JSX and integrate WordPress components.

1.  Import necessary components from `@wordpress/block-editor` and `@wordpress/components`.
2.  Use `useBlockProps()` on the outermost wrapper element.
3.  Replace static text with `<RichText>`.
4.  Replace static images with `<MediaUpload>` or custom upload handlers.
5.  Add configuration options to the sidebar using `<InspectorControls>`.

*See `references/edit-save-patterns.md` for common JSX implementations of WordPress components.*

### 5. Implement `save.js` (The Frontend Markup)

Replicate the HTML structure, rendering the saved attribute values.

1.  Use `useBlockProps.save()` on the outermost wrapper element.
2.  Render text using `<RichText.Content>`.
3.  Ensure the structure exactly matches what the original CSS expects, minus any editor-only UI elements.

### 6. Migrate and Scope CSS

Convert the provided CSS to SCSS and ensure it is properly scoped.

1.  Place the CSS in `style.scss`.
2.  Wrap all styles within the block's generated class name (usually `.wp-block-[namespace]-[block-name]`).
3.  Convert any absolute units to relative units (e.g., `rem`, `em`) if appropriate for the theme, though preserving the original design's exact units is often the safest initial approach unless requested otherwise.

### 7. Implement Block Style Variations (Optional)

If the design has multiple "styles" for the same block component (e.g., "Standard", "Glassmorphism", "Dark Mode"):

1.  **Register in PHP**: Use `register_block_style()` in your theme's `functions.php` or a dedicated register file.
2.  **Separate CSS**: Avoid cluttering the main `style.scss`. Place variation-specific styles in modular files and enqueue them via the `style_handle` in PHP.
3.  **Modifier Classes**: Style the variation using the `.is-style-[name]` class.

*See `references/advanced-css-variations.md` for the modular PHP enqueuing pattern and premium CSS examples.*

## Common Components and Their Usage

### RichText
Use for any inline editable text (headings, paragraphs, spans).
**When to use**: Text needs formatting (bold, italic, links) or semantic tags.
**Avoid when**: The text should be purely structural or unformatted.

### InnerBlocks
Use when a section of the block should allow the user to insert other blocks (e.g., a "Card" block that can contain any combination of headings, images, and paragraphs).
**When to use**: Highly flexible content areas.

### InspectorControls
Use for block settings that affect the overall appearance or behavior but don't belong in the visual canvas (e.g., toggling a dark mode, selecting a predefined layout style, or setting abstract metadata).

## Best Practices

-   **Attribute Validation**: Ensure `block.json` attribute definitions (`source`, `selector`) perfectly match the markup generated by `save.js`. Mismatches cause block validation errors.
-   **React/JSX translation**: Remember to convert HTML attributes to JSX properties (e.g., `class` -> `className`, `for` -> `htmlFor`, inline `style="string"` to `style={{ object }}`).
-   **No JS in save**: The `save.js` function must return static HTML or React elements that serialize to static HTML. It cannot contain dynamic behavior or state hooks.

## Reference Material

When performing conversions, consult the following references for exact syntax and examples:
-   `references/block-json-examples.md`: For `block.json` structure, attribute types, and `supports` options.
-   `references/edit-save-patterns.md`: For standard implementations of `RichText`, `InnerBlocks`, `MediaUpload`, and `InspectorControls`.
-   `references/advanced-css-variations.md`: For modular block style variations, "Premium" CSS patterns, and PHP enqueuing logic.
