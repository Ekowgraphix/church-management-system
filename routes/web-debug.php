<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug-photos', function () {
    $members = \App\Models\Member::all();
    
    echo "<h1>Photo Debug Information</h1>";
    echo "<style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .member { border: 2px solid #ccc; padding: 15px; margin: 10px 0; background: #f9f9f9; }
        .photo { width: 100px; height: 100px; border-radius: 50%; object-fit: cover; border: 3px solid green; }
        .error { color: red; }
        .success { color: green; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #4CAF50; color: white; }
    </style>";
    
    echo "<h2>Storage Link Status:</h2>";
    $publicStorage = public_path('storage');
    $storageApp = storage_path('app/public');
    echo "<table>";
    echo "<tr><th>Check</th><th>Path</th><th>Status</th></tr>";
    echo "<tr><td>Public Storage</td><td>$publicStorage</td><td>" . (file_exists($publicStorage) ? '<span class="success">EXISTS</span>' : '<span class="error">MISSING</span>') . "</td></tr>";
    echo "<tr><td>Storage App Public</td><td>$storageApp</td><td>" . (file_exists($storageApp) ? '<span class="success">EXISTS</span>' : '<span class="error">MISSING</span>') . "</td></tr>";
    echo "<tr><td>Is Link?</td><td>$publicStorage</td><td>" . (is_link($publicStorage) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</td></tr>";
    echo "</table>";
    
    echo "<h2>Photo Files in Storage:</h2>";
    $photoPath = storage_path('app/public/members/photos');
    if (file_exists($photoPath)) {
        $files = scandir($photoPath);
        $files = array_diff($files, ['.', '..']);
        echo "<p>Found " . count($files) . " file(s)</p>";
        foreach ($files as $file) {
            echo "<div>📷 $file</div>";
        }
    } else {
        echo "<p class='error'>Photo directory not found!</p>";
    }
    
    echo "<h2>Members in Database:</h2>";
    echo "<p>Total members: " . $members->count() . "</p>";
    
    foreach ($members as $member) {
        echo "<div class='member'>";
        echo "<h3>{$member->full_name}</h3>";
        echo "<table>";
        echo "<tr><th>Field</th><th>Value</th><th>File Exists?</th></tr>";
        echo "<tr><td>ID</td><td>{$member->id}</td><td>-</td></tr>";
        echo "<tr><td>profile_photo</td><td>" . ($member->profile_photo ?? '<em>NULL</em>') . "</td>";
        if ($member->profile_photo) {
            $fullPath = storage_path('app/public/' . $member->profile_photo);
            echo "<td>" . (file_exists($fullPath) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</td>";
        } else {
            echo "<td>-</td>";
        }
        echo "</tr>";
        echo "<tr><td>photo</td><td>" . ($member->photo ?? '<em>NULL</em>') . "</td>";
        if ($member->photo) {
            $fullPath = storage_path('app/public/' . $member->photo);
            echo "<td>" . (file_exists($fullPath) ? '<span class="success">YES</span>' : '<span class="error">NO</span>') . "</td>";
        } else {
            echo "<td>-</td>";
        }
        echo "</tr>";
        echo "</table>";
        
        if ($member->profile_photo || $member->photo) {
            $photoField = $member->profile_photo ?? $member->photo;
            $webPath = asset('storage/' . $photoField);
            echo "<p><strong>Web URL:</strong> $webPath</p>";
            echo "<p><strong>Display Test:</strong></p>";
            echo "<img src='$webPath' class='photo' onerror='this.style.border=\"3px solid red\"'>";
        } else {
            echo "<p class='error'>No photo uploaded</p>";
        }
        echo "</div>";
    }
    
    return '';
});
