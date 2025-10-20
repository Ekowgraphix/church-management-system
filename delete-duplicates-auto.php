<?php
/**
 * Auto-delete duplicate messages (no confirmation needed)
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║           AUTO-CLEAN DUPLICATE MESSAGES                   ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

try {
    echo "Searching for duplicate messages...\n\n";
    
    // Get all messages ordered by creation
    $allMessages = DB::table('messages')
        ->orderBy('sender_id')
        ->orderBy('receiver_id')
        ->orderBy('message')
        ->orderBy('created_at')
        ->get();
    
    $duplicateIds = [];
    $lastMessage = null;
    
    foreach ($allMessages as $message) {
        if ($lastMessage && 
            $lastMessage->sender_id == $message->sender_id &&
            $lastMessage->receiver_id == $message->receiver_id &&
            $lastMessage->message == $message->message) {
            
            // Check if created within 5 seconds of each other
            $lastTime = strtotime($lastMessage->created_at);
            $currentTime = strtotime($message->created_at);
            
            if (abs($currentTime - $lastTime) < 5) {
                // This is a duplicate
                $duplicateIds[] = $message->id;
                echo "  Found duplicate: ID {$message->id}\n";
            }
        }
        $lastMessage = $message;
    }
    
    if (empty($duplicateIds)) {
        echo "✓ No duplicate messages found!\n";
        echo "  Your chat database is clean.\n\n";
        exit(0);
    }
    
    echo "\nTotal duplicates found: " . count($duplicateIds) . "\n";
    echo "Deleting duplicates automatically...\n\n";
    
    // Delete duplicates
    $deletedCount = DB::table('messages')->whereIn('id', $duplicateIds)->delete();
    
    echo "✓ Successfully deleted " . $deletedCount . " duplicate messages!\n\n";
    
    // Show summary
    $totalMessages = DB::table('messages')->count();
    echo "─────────────────────────────────────────────────────────────\n";
    echo "Total messages remaining in database: " . $totalMessages . "\n";
    echo "─────────────────────────────────────────────────────────────\n\n";
    
} catch (\Exception $e) {
    echo "\n✗ Error: " . $e->getMessage() . "\n";
    echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    exit(1);
}

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║  Done! Chat duplicates have been removed.                ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";
