
import re
import os

def minify_css(css):
    # Remove comments
    css = re.sub(r'/\*.*?\*/', '', css, flags=re.DOTALL)
    # Remove whitespace
    css = re.sub(r'\s+', ' ', css)
    css = re.sub(r'\s*([{:;,])\s*', r'\1', css)
    return css.strip()

theme_path = 'wp-content/themes/twentytwentyfive-child/'
input_file = theme_path + 'seo-landing-pages.css'
output_file = theme_path + 'seo-landing-pages.min.css'

if os.path.exists(input_file):
    with open(input_file, 'r', encoding='utf-8') as f:
        original_css = f.read()

    minified_content = minify_css(original_css)

    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(minified_content)

    print(f"Minified {input_file} to {output_file}")
else:
    print(f"Error: {input_file} not found")
