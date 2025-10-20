<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait SyncsStorage
{
    /**
     * Sync storage files to public folder (for systems without symlink support)
     */
    protected function syncStorageToPublic()
    {
        $source = storage_path('app/public');
        $destination = public_path('storage');
        
        // Create destination if it doesn't exist
        if (!File::exists($destination)) {
            File::makeDirectory($destination, 0755, true);
        }
        
        // Run sync in background to avoid timeout
        try {
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows command - run in background with START /B
                $cmd = 'start /B cmd /c xcopy "' . $source . '" "' . $destination . '" /E /I /Y /Q /D > NUL 2>&1';
                pclose(popen($cmd, 'r'));
            } else {
                // Linux/Mac command - run in background
                $cmd = 'cp -Ru "' . $source . '/." "' . $destination . '/" > /dev/null 2>&1 &';
                exec($cmd);
            }
        } catch (\Exception $e) {
            // Silent fail - sync will happen on next request
            \Log::debug('Auto-sync skipped: ' . $e->getMessage());
        }
    }
    
    /**
     * Sync only a specific file (faster, no timeout)
     */
    protected function syncSingleFile($filePath)
    {
        if (empty($filePath)) {
            return;
        }
        
        try {
            $sourcePath = storage_path('app/public/' . $filePath);
            $destPath = public_path('storage/' . $filePath);
            
            // Create directory if needed
            $destDir = dirname($destPath);
            if (!File::exists($destDir)) {
                File::makeDirectory($destDir, 0755, true);
            }
            
            // Copy single file
            if (File::exists($sourcePath)) {
                File::copy($sourcePath, $destPath);
            }
        } catch (\Exception $e) {
            \Log::debug('Single file sync failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Recursive copy fallback (not used to avoid timeout)
     */
    protected function recursiveCopy($source, $destination)
    {
        // Deprecated - use syncSingleFile instead to avoid timeout
        return;
    }
}
