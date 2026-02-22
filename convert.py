import re
import argparse
import sys
import os

def convert_html_to_blocks(input_file, output_file):
    if not os.path.exists(input_file):
        print(f"Error: Input file '{input_file}' not found.")
        sys.exit(1)

    print(f"Converting '{input_file}' to '{output_file}'...")

    with open(input_file, 'r', encoding='utf-8') as f:
        html = f.read()

    # Grab everything in <head> except <title>, <meta charset>, etc.
    head_match = re.search(r'<head>.*?</head>', html, re.DOTALL)
    if not head_match:
        print("Error: Could not find <head> tag in HTML.")
        sys.exit(1)
        
    head_content = head_match.group(0)

    # Extract schema, fonts, and style block
    style_tags = []
    for match in re.finditer(r'(<script type="application/ld\+json">.*?</script>|<link [^>]+>|<style>.*?</style>)', head_content, re.DOTALL):
        style_tags.append(match.group(1))

    head_block = "<!-- wp:html -->\n" + "\n".join(style_tags) + "\n<!-- /wp:html -->"

    # For body elements, we will manually match the elements by looking for:
    # <!-- NAME -->
    # <tag ...>...</tag>
    body_match = re.search(r'<body>(.*?)</body>', html, re.DOTALL)
    if not body_match:
        print("Error: Could not find <body> tag in HTML.")
        sys.exit(1)
        
    body_content = body_match.group(1).strip()

    sections = re.sub(r'(<!-- [A-Z0-9:\s]+ -->\s*<(nav|div|section|footer)\b)', r'SPLIT\1', body_content, flags=re.IGNORECASE)
    blocks = []
    for part in sections.split('SPLIT'):
        part = part.strip()
        if part:
            blocks.append(f"<!-- wp:html -->\n{part}\n<!-- /wp:html -->")

    final_blocks = [head_block] + blocks

    with open(output_file, 'w', encoding='utf-8') as f:
        f.write("\n\n".join(final_blocks))

    print("Success: Converted!")

if __name__ == "__main__":
    parser = argparse.ArgumentParser(description="Convert static HTML landing pages into WordPress-ready blocks.")
    parser.add_argument("input", help="The source HTML file to convert.")
    parser.add_argument("output", help="The destination file for WordPress blocks (usually .html or .txt).")
    
    args = parser.parse_args()
    convert_html_to_blocks(args.input, args.output)
