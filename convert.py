import re

with open('local-seo-toronto.html', 'r', encoding='utf-8') as f:
    html = f.read()

# Grab everything in <head> except <title>, <meta charset>, etc.
head_match = re.search(r'<head>.*?</head>', html, re.DOTALL)
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
body_content = body_match.group(1).strip()

sections = re.sub(r'(<!-- [A-Z0-9:\s]+ -->\s*<(nav|div|section|footer)\b)', r'SPLIT\1', body_content, flags=re.IGNORECASE)
blocks = []
for part in sections.split('SPLIT'):
    part = part.strip()
    if part:
        # Also need to grab the last <script> along with the footer if it's there
        blocks.append(f"<!-- wp:html -->\n{part}\n<!-- /wp:html -->")

final_blocks = [head_block] + blocks

with open('local-seo-toronto-blocks.html', 'w', encoding='utf-8') as f:
    f.write("\n\n".join(final_blocks))

print("Converted!")
