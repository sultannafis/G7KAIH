
import os
import re

with open('all_views.txt', 'r') as f:
    files = f.read().splitlines()

missing = []
for file_path in files:
    if not os.path.isfile(file_path):
        continue
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()
        if not re.search(r'x-app-layout|x-guest-layout|layouts\.app|layouts\.guest|toast-notification', content):
            missing.append(file_path)

for m in missing:
    print(m)
    
