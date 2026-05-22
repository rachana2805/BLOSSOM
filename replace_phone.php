<?php
$files = glob("*.php");
$count = 0;
foreach ($files as $file) {
    if (!is_file($file)) continue;
    $content = file_get_contents($file);
    
    // Replace URL usages
    $new_content = str_replace("919739296978", "919739296978", $content);
    // Replace visual usages
    $new_content = str_replace("97392 96978", "97392 96978", $new_content);
    $new_content = str_replace("+91 97392 96978", "+91 97392 96978", $new_content);
    
    if ($content !== $new_content) {
        file_put_contents($file, $new_content);
        echo "Updated $file\n";
        $count++;
    }
}
echo "Total files updated: $count\n";
?>
