from pypdf import PdfReader
import sys

try:
    reader = PdfReader("public/g7kaih-panduan.pdf")
    # Read first 5 pages, which should contain the intro/about
    text = ""
    for i in range(min(15, len(reader.pages))):
        text += reader.pages[i].extract_text() + "\n"
    print(text[:3000]) # print first 3000 chars
except Exception as e:
    print(f"Error: {e}")
