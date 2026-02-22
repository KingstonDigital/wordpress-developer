# WordPress Developer tools

This repository contains tools for converting static HTML/CSS designs into WordPress-ready components.

## Prerequisites

- **Python 3.x**: Required for utility scripts (`convert.py`, `minify.py`, `scaffold_block.py`).
- **Node.js & npm**: Required for advanced block development (React-based).

## Tool Overview

### 1. Simple HTML-to-Block Converter (`convert.py`)
Used for quickly wrapping a static HTML landing page into WordPress `<!-- wp:html -->` blocks.

**Usage:**
```bash
python convert.py input.html output-blocks.html
```

### 2. CSS Minifier (`minify.py`)
A simple utility to minify theme CSS files.

**Usage:**
```bash
python minify.py
```
*(Currently configured to minify `wp-content/themes/twentytwentyfive-child/seo-landing-pages.css`)*

### 3. Advanced Block Scaffolding (`SKILLS/wp-block-converter/`)
For creating modern React-based Gutenberg blocks.

**Usage:**
```bash
python SKILLS/wp-block-converter/scripts/scaffold_block.py [namespace] [block-name] --output [dir]
```

**Workflow:**
1. Scaffold the block.
2. Install dependencies: `npm install` (in the block or plugin directory).
3. Start development: `npm start`.
4. Build for production: `npm run build`.

## Portable Setup
To use these tools on another machine:
1. Clone this repository.
2. Ensure Python 3 is installed.
3. For React-based blocks, ensure Node.js is installed and run `npm install` in the relevant plugin/theme directory.
