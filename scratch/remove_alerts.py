import os
import re

view_dir = 'c:/laragon/www/g7kaih/resources/views'

# We'll look for simple flash blocks that do not contain other @if
# Pattern: @if(session(...)) ... @endif
# Also: @if(session()->has(...)) ... @endif

patterns = [
    re.compile(r'\{\{--\s*Flash\s*--\}\}\s*', re.IGNORECASE),
    re.compile(r'@if\s*\(\s*session\(\s*[\'"](?:success|error|status|danger|warning)[\'"]\s*\)\s*\)(?:(?!@if).)*?@endif\s*', re.DOTALL),
    re.compile(r'@if\s*\(\s*session\(\)->has\(\s*[\'"](?:success|error|status|danger|warning)[\'"]\s*\)\s*\)(?:(?!@if).)*?@endif\s*', re.DOTALL)
]

for root, dirs, files in os.walk(view_dir):
    for f in files:
        if f.endswith('.blade.php'):
            path = os.path.join(root, f)
            with open(path, 'r', encoding='utf-8') as f_in:
                original_content = f_in.read()
            
            content = original_content
            for p in patterns:
                content = p.sub('', content)
            
            if content != original_content:
                with open(path, 'w', encoding='utf-8') as f_out:
                    f_out.write(content)
                print(f"Updated {path}")
