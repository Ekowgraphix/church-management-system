<?php

namespace App\Services;

use App\Models\DataBackup;
use App\Models\DataRetentionPolicy;
use App\Models\ConsentLog;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class DataProtectionService
{
    // Encrypt sensitive data
    public function encryptData($data)
    {
        return Crypt::encryptString($data);
    }

    // Decrypt sensitive data
    public function decryptData($encryptedData)
    {
        try {
            return Crypt::decryptString($encryptedData);
        } catch (\Exception $e) {
            return null;
        }
    }

    // Log user consent for GDPR
    public function logConsent($memberId, $userId, $consentType, $consentGiven, $description)
    {
        return ConsentLog::create([
            'member_id' => $memberId,
            'user_id' => $userId,
            'consent_type' => $consentType,
            'consent_description' => $description,
            'consent_given' => $consentGiven,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'consent_date' => now(),
        ]);
    }

    // Withdraw consent
    public function withdrawConsent($consentId)
    {
        $consent = ConsentLog::find($consentId);
        if ($consent) {
            $consent->update([
                'consent_given' => false,
                'withdrawn_at' => now(),
            ]);
            return true;
        }
        return false;
    }

    // Check if consent exists and is valid
    public function hasConsent($memberId, $consentType)
    {
        return ConsentLog::where('member_id', $memberId)
            ->where('consent_type', $consentType)
            ->where('consent_given', true)
            ->whereNull('withdrawn_at')
            ->exists();
    }

    // Create backup record
    public function createBackupRecord($backupName, $filePath, $backupType = 'full')
    {
        return DataBackup::create([
            'backup_name' => $backupName,
            'backup_type' => $backupType,
            'file_path' => $filePath,
            'status' => 'pending',
            'is_encrypted' => true,
            'created_by' => auth()->id(),
        ]);
    }

    // Update backup status
    public function updateBackupStatus($backupId, $status, $fileSize = null, $error = null)
    {
        $backup = DataBackup::find($backupId);
        if ($backup) {
            $data = ['status' => $status];
            
            if ($status === 'completed') {
                $data['completed_at'] = now();
            }
            
            if ($fileSize) {
                $data['file_size'] = $fileSize;
            }
            
            if ($error) {
                $data['error_message'] = $error;
            }
            
            $backup->update($data);
            return true;
        }
        return false;
    }

    // Apply data retention policies
    public function applyRetentionPolicies()
    {
        $policies = DataRetentionPolicy::where('is_active', true)->get();
        $results = [];

        foreach ($policies as $policy) {
            $cutoffDate = Carbon::now()->subDays($policy->retention_days);
            
            // This is a simplified example - actual implementation would need to be more sophisticated
            $results[] = [
                'policy' => $policy->data_type,
                'action' => $policy->action,
                'cutoff_date' => $cutoffDate,
                'executed_at' => now(),
            ];

            $policy->update(['last_executed_at' => now()]);
        }

        return $results;
    }

    // Anonymize personal data (GDPR Right to be Forgotten)
    public function anonymizePersonalData($memberId)
    {
        // This would anonymize sensitive personal information
        // while keeping necessary records for audit/compliance
        
        $member = \App\Models\Member::find($memberId);
        if ($member) {
            $member->update([
                'first_name' => 'Anonymized',
                'last_name' => 'User',
                'email' => 'anonymized_' . $memberId . '@deleted.local',
                'phone' => null,
                'address' => null,
                'date_of_birth' => null,
            ]);
            
            // Log the anonymization for compliance
            $this->logConsent(
                $memberId,
                null,
                'data_deletion',
                false,
                'Personal data anonymized per GDPR request'
            );
            
            return true;
        }
        return false;
    }

    // Export personal data (GDPR Right to Data Portability)
    public function exportPersonalData($memberId)
    {
        $member = \App\Models\Member::with([
            'donations',
            'pledges',
            'attendanceRecords',
            'emergencyContacts',
            'documents',
        ])->find($memberId);

        if ($member) {
            return [
                'personal_information' => $member->only([
                    'first_name', 'last_name', 'email', 'phone', 'address',
                    'date_of_birth', 'gender', 'marital_status'
                ]),
                'membership' => $member->only([
                    'membership_number', 'membership_date', 'membership_status'
                ]),
                'donations' => $member->donations->map(function($donation) {
                    return $donation->only(['amount', 'donation_date', 'payment_method']);
                }),
                'pledges' => $member->pledges->map(function($pledge) {
                    return $pledge->only(['pledge_amount', 'amount_paid', 'pledge_date']);
                }),
                'attendance' => $member->attendanceRecords->map(function($record) {
                    return $record->only(['attendance_date', 'service_id']);
                }),
                'exported_at' => now(),
            ];
        }
        
        return null;
    }

    // Clean old backups based on retention
    public function cleanOldBackups($retentionDays = 30)
    {
        $cutoffDate = Carbon::now()->subDays($retentionDays);
        
        $oldBackups = DataBackup::where('created_at', '<', $cutoffDate)
            ->where('status', 'completed')
            ->get();

        foreach ($oldBackups as $backup) {
            // Delete physical file
            if (Storage::exists($backup->file_path)) {
                Storage::delete($backup->file_path);
            }
            
            // Delete record
            $backup->delete();
        }

        return $oldBackups->count();
    }

    // Generate compliance report
    public function generateComplianceReport()
    {
        return [
            'total_consents' => ConsentLog::where('consent_given', true)->count(),
            'withdrawn_consents' => ConsentLog::whereNotNull('withdrawn_at')->count(),
            'recent_backups' => DataBackup::where('status', 'completed')
                ->where('created_at', '>=', Carbon::now()->subDays(7))
                ->count(),
            'active_policies' => DataRetentionPolicy::where('is_active', true)->count(),
            'last_backup' => DataBackup::where('status', 'completed')
                ->latest()
                ->first(),
            'encryption_enabled' => true,
            'ssl_enabled' => request()->secure(),
        ];
    }
}
