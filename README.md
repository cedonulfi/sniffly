# Sniffly

Sniffly is a simple tool that scans website files for suspicious code patterns. It includes scripts in Python and PHP to help detect potential threats and maintain website security.

## Features

- Scans files for suspicious patterns such as `eval()`, `base64_decode()`, and other potentially harmful code.
- Can be run both as a Python script (`scan.py`) and as a PHP script (`scan.php`).
- Supports scanning all files in a directory, including subdirectories.

## Usage

### Python Script (`scan.py`)

1. Place `scan.py` in the desired folder.
2. Modify the `TARGET_DIR` variable to point to the folder you want to scan (e.g., `public_html`).
3. Run the script:
   ```bash
   python scan.py
   ```

### PHP Script (`scan.php`)

1. Place `scan.php` in the `scan` folder (separate from `public_html`).
2. Modify the `$targetDirectory` variable to point to the `public_html` folder.
3. Run the script by including it in your PHP application or running it via the command line:
   ```bash
   php scan.php
   ```

## Suspicious Code Patterns Detected

- `eval(base64_decode())`
- `eval()`
- `base64_decode()`
- `shell_exec()`, `passthru()`, `exec()`, `system()` (commands execution)
- `preg_replace()` with `/e` modifier (deprecated)
- `gzinflate()` (inflating compressed strings)

## License

This project is open-source and available under the [MIT License](LICENSE).

---

For any issues or questions, feel free to open an issue on the [GitHub repository](https://github.com/cedonulfi/sniffly).
