# Gutenberg Block Markup Serialization Reference

This document defines the "Golden Rules" for generating raw WordPress block markup (the `<!-- wp: ... -->` format). Adhering to these rules prevents "Block Recovery" errors and ensures pixel-perfect layout implementation.

## 1. The Clean Wrapper Rule
**CRITICAL**: Never place HTML comments or explanatory notes *inside* the wrapper of a native WordPress block.

*   ❌ **Incorrect**:
    ```html
    <!-- wp:group -->
    <div class="wp-block-group">
        <!-- MY CUSTOM CONTENT -->
        <p>Hello</p>
    </div>
    <!-- /wp:group -->
    ```
*   ✅ **Correct**:
    ```html
    <!-- MY CUSTOM CONTENT -->
    <!-- wp:group -->
    <div class="wp-block-group">
        <p>Hello</p>
    </div>
    <!-- /wp:group -->
    ```
Gutenberg's parser is strict. Unexpected HTML (like comments) between the opening wrapper and child blocks will break validation.

## 2. Attribute Parity Rule
The JSON attributes in the block delimiter MUST match the HTML output exactly in terms of functional classes and styles.

*   **Alignments**: If `{"align":"full"}` is in the JSON, the class `alignfull` must be on the wrapper.
*   **Spacing**: If `{"spacing":{"padding":{"top":"80px"}}}` is in the JSON, `padding-top:80px` must be in the `style` attribute.
*   **Gap**: If `{"gap":"60px"}` is in a columns block JSON, the class `has-custom-gap` (or equivalent) might be needed, and the flex spacing must match.

## 3. Layout Constraint Mapping
When using `core/group` with constrained layouts, ensure the JSON schema is complete.

*   **Constrained Layout**:
    ```html
    <!-- wp:group {"layout":{"type":"constrained","contentSize":"1200px"}} -->
    <div class="wp-block-group"> ... </div>
    <!-- /wp:group -->
    ```
*   **Wide Content**: Use `{"align":"wide"}` in JSON and `alignwide` in class to respect the theme's wide-width settings.

## 4. Column Sizing
Native columns require the `flex-basis` style and specific classes to render correctly in both the editor and frontend.

```html
<!-- wp:column {"width":"60%"} -->
<div class="wp-block-column" style="flex-basis:60%"> ... </div>
<!-- /wp:column -->
```

## 5. Refactoring Checklist (To Avoid Content Loss)
When refactoring existing markup (e.g., adding a wrapper or changing a block type):
1.  **Scan for HTML snippets**: Ensure `core/html` content is preserved.
2.  **Verify Inner Content**: Check that all paragraphs, headings, and lists are copied into the new container.
3.  **Check IDs**: Preserve any `id="..."` attributes used for anchor links.
4.  **Button Links**: Ensure the `wp-block-button__link` class is inside the `wp-block-button` wrapper.

## 6. CSS SCOPING
Always scope custom CSS to the outermost page-specific class (e.g., `.kdm-technical-seo`) to prevent styles from leaking into other pages or the global header/footer.
