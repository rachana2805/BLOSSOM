import os
import re
import glob

directory = 'c:\\xampp\\htdocs\\blossom\\blossom2'
php_files = glob.glob(os.path.join(directory, '*.php'))

href_pattern = re.compile(r'href\s*=\s*[\'"]([^\'"]*)[\'"]')
action_pattern = re.compile(r'action\s*=\s*[\'"]([^\'"]*)[\'"]')
window_loc_pattern = re.compile(r'window\.location\.href\s*=\s*[\'"]([^\'"]*)[\'"]')

links = {}

for filepath in php_files:
    filename = os.path.basename(filepath)
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        
    file_links = {
        'href': href_pattern.findall(content),
        'action': action_pattern.findall(content),
        'window_loc': window_loc_pattern.findall(content)
    }
    
    # Filter out valid links that definitely exist (like other .php pages, or external URLs)
    # We want to flag suspicious links like #, empty strings, missing pages.
    links[filename] = file_links

valid_pages = [os.path.basename(f) for f in php_files]

print("Suspicious Links:")
for filename, file_links in links.items():
    issues = []
    
    for link_type, urls in file_links.items():
        for url in urls:
            if not url or url == '#':
                issues.append(f"{link_type}: '{url}'")
            elif not url.startswith('http') and not url.startswith('mailto') and not url.startswith('?') and not url.startswith('tel') and not url.startswith('#'):
                # Check if it's a local file
                base_url = url.split('?')[0].split('#')[0]
                if base_url and base_url not in valid_pages and not base_url.endswith('.css') and not base_url.endswith('.js') and not base_url.endswith('.png') and not base_url.endswith('.jpg') and not base_url.endswith('.jpeg'):
                    issues.append(f"{link_type}: '{url}' (Not Found)")
                    
    if issues:
        print(f"\n--- {filename} ---")
        for i in set(issues):
            print(f"  {i}")
