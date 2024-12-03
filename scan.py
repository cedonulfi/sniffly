import os
import re

# Path to the folder to scan
TARGET_DIR = "../public_html"

# Patterns of suspicious code
SUSPICIOUS_PATTERNS = [
    r"eval\(base64_decode\(",   # Common obfuscation technique
    r"eval\(",                 # Generic eval usage
    r"base64_decode\(",        # Base64 decoding
    r"shell_exec\(",           # Executes shell commands
    r"passthru\(",             # Executes shell commands
    r"exec\(",                 # Executes shell commands
    r"system\(",               # Executes system commands
    r"preg_replace\(.*/e",     # Deprecated /e modifier in preg_replace
    r"gzinflate\(",            # Inflates compressed strings (often used in obfuscation)
]

def scan_file(file_path):
    """Scan a single file for suspicious patterns."""
    results = []
    try:
        with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
            for line_num, line in enumerate(f, start=1):
                for pattern in SUSPICIOUS_PATTERNS:
                    if re.search(pattern, line):
                        results.append((line_num, pattern))
    except Exception as e:
        return False, f"Error reading file: {e}"
    return results

def scan_directory(directory):
    """Recursively scan all files in a directory."""
    results = []
    for root, _, files in os.walk(directory):
        for file in files:
            file_path = os.path.join(root, file)
            suspicious_matches = scan_file(file_path)
            if suspicious_matches:
                for line_num, pattern in suspicious_matches:
                    results.append((file_path, line_num, pattern))
    return results

def main():
    print(f"Scanning directory: {TARGET_DIR}")
    results = scan_directory(TARGET_DIR)
    if results:
        print("\nSuspicious files detected:")
        for file_path, line_num, pattern in results:
            print(f"File: {file_path} | Line: {line_num} | Pattern: {pattern}")
    else:
        print("\nNo suspicious files detected.")

if __name__ == "__main__":
    main()
