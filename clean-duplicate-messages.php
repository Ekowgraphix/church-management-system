<?php
/**
 * Clean Duplicate Messages Script
 * 
 * This script removes duplicate messages from the database
 * Run this from command line: php clean-duplicate-messages.php
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║           CLEAN DUPLICATE MESSAGES                        ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

try {
    // Find duplicate messages using Laravel Query Builder
    echo "Searching for duplicate messages...\n\n";
    
    // Get all messages ordered by creation
    $allMessages = DB::table('messages')
        ->orderBy('sender_id')
        ->orderBy('receiver_id')
        ->orderBy('message')
        ->orderBy('created_at')
        ->get();
    
    $duplicateIds = [];
    $duplicateGroups = [];
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
                
                // Track for display
                $key = $lastMessage->id;
                if (!isset($duplicateGroups[$key])) {
                    $duplicateGroups[$key] = [
                        'keep_id' => $lastMessage->id,
                        'message' => substr($lastMessage->message, 0, 50),
                        'sender_id' => $lastMessage->sender_id,
                        'receiver_id' => $lastMessage->receiver_id,
                        'duplicate_ids' => []
                    ];
                }
                $duplicateGroups[$key]['duplicate_ids'][] = $message->id;
            }
        }
        $lastMessage = $message;
    }
    
    if (empty($duplicateIds)) {
        echo "✓ No duplicate messages found!\n";
        echo "  Your chat database is clean.\n\n";
        exit(0);
    }
    
    echo "Found " . count($duplicateGroups) . " sets of duplicate messages:\n\n";
    
    foreach ($duplicateGroups as $group) {
        echo "  • Message: \"" . $group['message'] . "...\"\n";
        echo "    Duplicates: " . count($group['duplicate_ids']) . " copies\n";
        echo "    Will keep ID: " . $group['keep_id'] . "\n";
        echo "    Will delete IDs: " . implode(', ', $group['duplicate_ids']) . "\n\n";
    }
    
    echo "─────────────────────────────────────────────────────────────\n";
    echo "Total duplicate messages to remove: " . count($duplicateIds) . "\n";
    echo "─────────────────────────────────────────────────────────────\n\n";
    
    // Ask for confirmation
    echo "Do you want to delete these duplicates? (yes/no): ";
    $handle = fopen("php://stdin", "r");
    $line = trim(fgets($handle));
    fclose($handle);
    
    if (strtolower($line) !== 'yes') {
        echo "\n✗ Cancelled. No messages were deleted.\n";
        exit(0);
    }
    
    // Delete duplicates
    echo "\nDeleting duplicate messages...\n";
    
    $deletedCount = DB::table('messages')->whereIn('id', $duplicateIds)->delete();
    
    echo "\n✓ Successfully deleted " . $deletedCount . " duplicate messages!\n";
    echo "  Your chat is now clean.\n\n";
    
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
echo "║  Done! You can now test the chat feature.                ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";
