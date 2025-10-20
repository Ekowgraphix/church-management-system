<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Member;
use Illuminate\Support\Facades\DB;

echo "╔═══════════════════════════════════════════════════════════╗\n";
echo "║         FIX EXISTING MEMBERS WITHOUT MEMBER IDs           ║\n";
echo "╚═══════════════════════════════════════════════════════════╝\n\n";

echo "Checking for members without member_id...\n\n";

$membersWithoutId = Member::whereNull('member_id')
    ->orWhere('member_id', '')
    ->get();

if ($membersWithoutId->isEmpty()) {
    echo "✓ All members have member IDs!\n";
    echo "  Total members: " . Member::count() . "\n\n";
    exit(0);
}

echo "Found {$membersWithoutId->count()} member(s) without member_id\n\n";

foreach ($membersWithoutId as $index => $member) {
    $num = $index + 1;
    echo "[$num] {$member->first_name} {$member->last_name} (ID: {$member->id})\n";
}

echo "\n─────────────────────────────────────────────────────────────\n";
echo "Generating member IDs...\n\n";

DB::beginTransaction();

try {
    $memberIdPrefix = 'MEM';
    $year = date('Y');
    
    // Get the highest existing member number for this year
    $lastMemberId = Member::whereNotNull('member_id')
        ->where('member_id', '!=', '')
        ->whereYear('created_at', $year)
        ->orderBy('member_id', 'desc')
        ->value('member_id');
    
    if ($lastMemberId && preg_match('/MEM' . $year . '(\d+)/', $lastMemberId, $matches)) {
        $nextNumber = intval($matches[1]) + 1;
    } else {
        $nextNumber = 1;
    }
    
    foreach ($membersWithoutId as $member) {
        $memberId = $memberIdPrefix . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        
        $member->update(['member_id' => $memberId]);
        
        echo "✓ {$member->first_name} {$member->last_name} → {$memberId}\n";
        
        $nextNumber++;
    }
    
    DB::commit();
    
    echo "\n✅ Successfully assigned member IDs to {$membersWithoutId->count()} member(s)!\n\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "\n✗ Error: " . $e->getMessage() . "\n\n";
}

echo "─────────────────────────────────────────────────────────────\n";
echo "Summary:\n";
echo "  Total members: " . Member::count() . "\n";
echo "  With member_id: " . Member::whereNotNull('member_id')->where('member_id', '!=', '')->count() . "\n";
echo "  Without member_id: " . Member::whereNull('member_id')->orWhere('member_id', '')->count() . "\n";
echo "\n";
