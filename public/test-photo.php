<?php
// Quick photo display test
$storagePath = __DIR__ . '/../storage/app/public/members/photos';
$publicPath = __DIR__ . '/storage/members/photos';

echo "<h1>Photo Storage Test</h1>";

echo "<h2>Storage Path Check:</h2>";
echo "<p>Storage Path: " . $storagePath . "</p>";
echo "<p>Exists: " . (is_dir($storagePath) ? 'YES' : 'NO') . "</p>";

if (is_dir($storagePath)) {
    $files = scandir($storagePath);
    $files = array_diff($files, array('.', '..'));
    echo "<p>Files found: " . count($files) . "</p>";
    
    echo "<h2>Photos:</h2>";
    foreach ($files as $file) {
        $webPath = '/storage/members/photos/' . $file;
        echo "<div style='margin: 10px; padding: 10px; border: 1px solid #ccc;'>";
        echo "<p>File: $file</p>";
        echo "<p>Web Path: $webPath</p>";
        echo "<img src='$webPath' style='width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid green;'>";
        echo "</div>";
    }
}

echo "<h2>Public Storage Link Check:</h2>";
echo "<p>Public Path: " . $publicPath . "</p>";
echo "<p>Exists: " . (is_dir($publicPath) ? 'YES (Link Working!)' : 'NO (Link Missing!)') . "</p>";
echo "<p>Is Link: " . (is_link($publicPath) ? 'YES' : 'NO') . "</p>";
?>
