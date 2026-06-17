<?php
$html = file_get_contents('http://127.0.0.1:8000/663098/dipak-shikshan-sankul');
if (!$html) {
    echo "Failed to fetch HTML\n";
    exit(1);
}

// Find gallery section
$pos = strpos($html, 'id="gallery"');
if ($pos === false) {
    echo "Gallery section not found\n";
    exit(1);
}

echo substr($html, $pos, 2000);
