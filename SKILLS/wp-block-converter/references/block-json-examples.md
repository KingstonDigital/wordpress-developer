# WordPress `block.json` Examples and Schemas

This reference provides standard patterns for configuring `block.json` when converting static HTML to WordPress blocks.

## Basic Structure

Every block requires a foundational `block.json`:

```json
{
	"$schema": "https://schemas.wp.org/trunk/block.json",
	"apiVersion": 3,
	"name": "namespace/block-name",
	"version": "1.0.0",
	"title": "Block Title",
	"category": "design",
	"icon": "smiley",
	"description": "A description of the block.",
	"attributes": {},
	"supports": {},
	"textdomain": "namespace",
	"editorScript": "file:./index.js",
	"editorStyle": "file:./index.css",
	"style": "file:./style-index.css"
}
```

## Attribute Definitions

Attributes define the dynamic data the block saves. Extracting these correctly is the most critical part of converting static HTML.

### Text Content (RichText)

For editable text, usually extracted directly from the HTML content.

```json
"title": {
    "type": "string",
    "source": "html",
    "selector": "h2"
}
```

### Static Text / Strings

For text that doesn't need HTML formatting, or configuration values.

```json
"alignment": {
    "type": "string",
    "default": "left"
}
```

### Images and Media

Media requires storing both the URL (for rendering) and the ID (for editor manipulation).

```json
"imageUrl": {
    "type": "string",
    "source": "attribute",
    "selector": "img",
    "attribute": "src"
},
"imageId": {
    "type": "number"
},
"imageAlt": {
    "type": "string",
    "source": "attribute",
    "selector": "img",
    "attribute": "alt",
    "default": ""
}
```

### Booleans (Toggles)

Used for conditional rendering or toggle states in the inspector.

```json
"showButton": {
    "type": "boolean",
    "default": true
}
```

### Arrays / Objects

For repeating elements or complex data.

```json
"items": {
    "type": "array",
    "default": []
}
```

## Supports Opt-ins

The `supports` object allows you to opt the block into native WordPress core features, reducing the need for custom `InspectorControls`.

```json
"supports": {
    "html": false,            // Disable "Edit as HTML"
    "align": ["wide", "full"], // Enable alignment toolbar
    "color": {
        "text": true,         // Enable text color
        "background": true,   // Enable background color
        "link": true          // Enable link color
    },
    "spacing": {
        "margin": true,       // Enable margin controls
        "padding": true,      // Enable padding controls
        "blockGap": true      // Enable block spacing
    },
    "typography": {
        "fontSize": true,     // Enable font size controls
        "lineHeight": true    // Enable line height controls
    }
}
```

Core styles enabled via `supports` are automatically applied to the wrapper element by `useBlockProps()` in `edit.js` and `useBlockProps.save()` in `save.js`.
