<?php
// Patterns of suspicious code
$suspicious_patterns = [
    '/eval\(base64_decode\(/',   // Common obfuscation technique
    '/eval\(/',                 // Generic eval usage
    '/base64_decode\(/',        // Base64 decoding
    '/shell_exec\(/',           // Executes shell commands
    '/passthru\(/',             // Executes shell commands
    '/exec\(/',                 // Executes shell commands
    '/system\(/',               // Executes system commands
    '/preg_replace\(.+\/e/',    // Deprecated /e modifier in preg_replace
    '/gzinflate\(/',            // Inflates compressed strings
];

// Function to scan a single file
function scanFile($filePath, $patterns) {
    $results = [];
    $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    if ($lines === false) {
        return ["Error reading file"];
    }

    foreach ($lines as $lineNumber => $lineContent) {
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $lineContent)) {
                $results[] = [
                    'line' => $lineNumber + 1, // Line number (starting from 1)
                    'pattern' => $pattern,
                    'content' => trim($lineContent)
                ];
            }
        }
    }

    return $results;
}

// Function to recursively scan all files in a directory
function scanDirectory($directory, $patterns) {
    $results = [];
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

    foreach ($files as $file) {
        if ($file->isFile()) {
            $filePath = $file->getRealPath();
            $scanResults = scanFile($filePath, $patterns);
            if (!empty($scanResults)) {
                $results[$filePath] = $scanResults;
            }
        }
    }

    return $results;
}

// Define the directory to scan
$targetDirectory = __DIR__; // Current directory (public_html)

// Start scanning
echo "Scanning directory: $targetDirectory\n";
$scanResults = scanDirectory($targetDirectory, $suspicious_patterns);

if (!empty($scanResults)) {
    echo "\nSuspicious files detected:\n";
    foreach ($scanResults as $file => $issues) {
        echo "File: $file\n";
        foreach ($issues as $issue) {
            echo "  Line: {$issue['line']} | Pattern: {$issue['pattern']} | Content: {$issue['content']}\n";
        }
        echo "\n";
    }
} else {
    echo "\nNo suspicious files detected.\n";
}
?>
