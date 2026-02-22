import os
import sys
import json
import argparse

def create_file(path, content):
    with open(path, 'w', encoding='utf-8') as f:
        f.write(content.strip() + "\n")
    print(f"Created: {path}")

def scaffold_block(namespace, block_name, output_dir):
    block_dir = os.path.join(output_dir, block_name)
    os.makedirs(block_dir, exist_ok=True)

    # block.json
    block_json = {
        "$schema": "https://schemas.wp.org/trunk/block.json",
        "apiVersion": 3,
        "name": f"{namespace}/{block_name}",
        "version": "1.0.0",
        "title": block_name.replace('-', ' ').title(),
        "category": "design",
        "icon": "smiley",
        "description": f"A custom block: {block_name}",
        "attributes": {},
        "supports": {
            "html": False
        },
        "textdomain": namespace,
        "editorScript": "file:./index.js",
        "editorStyle": "file:./index.css",
        "style": "file:./style-index.css"
    }
    create_file(os.path.join(block_dir, 'block.json'), json.dumps(block_json, indent=4))

    # index.js
    index_js = f"""
import {{ registerBlockType }} from '@wordpress/blocks';
import './style.scss';
import Edit from './edit';
import save from './save';
import metadata from './block.json';

registerBlockType( metadata.name, {{
	edit: Edit,
	save,
}} );
"""
    create_file(os.path.join(block_dir, 'index.js'), index_js)

    # edit.js
    edit_js = """
import { useBlockProps } from '@wordpress/block-editor';
import './editor.scss';

export default function Edit() {
	return (
		<p { ...useBlockProps() }>
			Edit Mode
		</p>
	);
}
"""
    create_file(os.path.join(block_dir, 'edit.js'), edit_js)

    # save.js
    save_js = """
import { useBlockProps } from '@wordpress/block-editor';

export default function save() {
	return (
		<p { ...useBlockProps.save() }>
			Saved Content
		</p>
	);
}
"""
    create_file(os.path.join(block_dir, 'save.js'), save_js)

    # style.scss
    style_scss = f"""
.wp-block-{namespace}-{block_name} {{
	/* Frontend styles */
}}
"""
    create_file(os.path.join(block_dir, 'style.scss'), style_scss)

    # editor.scss
    editor_scss = f"""
.wp-block-{namespace}-{block_name} {{
	/* Editor-specific styles */
}}
"""
    create_file(os.path.join(block_dir, 'editor.scss'), editor_scss)

    print(f"\nSuccessfully scaffolded '{namespace}/{block_name}' in '{block_dir}'")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Scaffold a new WordPress block.")
    parser.add_argument("namespace", help="The namespace for the block (e.g., 'my-plugin').")
    parser.add_argument("block_name", help="The name of the block (e.g., 'hero-section').")
    parser.add_argument("--output", default=".", help="The directory to create the block folder in.")
    
    args = parser.parse_args()
    scaffold_block(args.namespace, args.block_name, args.output)
