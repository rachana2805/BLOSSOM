<?php
$files = glob("*.php");
$valid_pages = [];
foreach($files as $f) { $valid_pages[] = basename($f); }

foreach($files as $file) {
    if ($file == 'analyze_links.php') continue;
    $content = file_get_contents($file);
    preg_match_all('/href\s*=\s*[\'"]([^\'"]*)[\'"]/i', $content, $hrefs);
    preg_match_all('/action\s*=\s*[\'"]([^\'"]*)[\'"]/i', $content, $actions);
    preg_match_all('/window\.location\.href\s*=\s*[\'"]([^\'"]*)[\'"]/i', $content, $windows);
    
    $all = array_merge($hrefs[1], $actions[1], $windows[1]);
    $issues = [];
    foreach($all as $link) {
        if (empty($link) || $link == '#') {
            $issues[] = $link;
            continue;
        }
        $parts = explode('?', $link);
        $base = explode('#', $parts[0])[0];
        if (!empty($base) && !in_array($base, $valid_pages) && !preg_match('/^(http|mailto|tel)/i', $base) && !preg_match('/\.(css|js|png|jpg|jpeg)$/i', $base)) {
            $issues[] = $link . ' (Not Found)';
        }
    }
    
    if (!empty($issues)) {
        echo "--- $file ---\n";
        foreach(array_unique($issues) as $i) {
            echo "  $i\n";
        }
    }
}
?>
