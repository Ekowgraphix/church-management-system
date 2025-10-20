<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\AccessLog;
use App\Models\ErrorLog;
use App\Models\SecurityIncident;
use Illuminate\Support\Facades\Log;

class AuditService
{
    // Log user action
    public function logAction($event, $auditable = null, $oldValues = [], $newValues = [])
    {
        return AuditLog::create([
            'user_id' => auth()->id(),
            'event' => $event,
            'auditable_type' => $auditable ? get_class($auditable) : null,
            'auditable_id' => $auditable ? $auditable->id : null,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    // Log access to resources
    public function logAccess($action, $resource, $responseCode = null, $requestData = [])
    {
        return AccessLog::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'resource' => $resource,
            'method' => request()->method(),
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'response_code' => $responseCode,
            'request_data' => !empty($requestData) ? json_encode($requestData) : null,
        ]);
    }

    // Log error
    public function logError($errorType, $errorMessage, $severity = 'error', $context = [])
    {
        return ErrorLog::create([
            'user_id' => auth()->id(),
            'severity' => $severity,
            'error_type' => $errorType,
            'error_message' => $errorMessage,
            'stack_trace' => isset($context['trace']) ? json_encode($context['trace']) : null,
            'file' => $context['file'] ?? null,
            'line' => $context['line'] ?? null,
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'context' => !empty($context) ? json_encode($context) : null,
        ]);
    }

    // Log security incident
    public function logSecurityIncident($incidentType, $description, $severity = 'medium', $additionalData = [])
    {
        return SecurityIncident::create([
            'incident_type' => $incidentType,
            'severity' => $severity,
            'description' => $description,
            'user_id' => auth()->id(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'resource_accessed' => request()->fullUrl(),
            'additional_data' => !empty($additionalData) ? json_encode($additionalData) : null,
        ]);
    }

    // Get user activity history
    public function getUserActivity($userId, $days = 30)
    {
        return [
            'audit_logs' => AuditLog::where('user_id', $userId)
                ->where('created_at', '>=', now()->subDays($days))
                ->latest()
                ->get(),
            'access_logs' => AccessLog::where('user_id', $userId)
                ->where('created_at', '>=', now()->subDays($days))
                ->latest()
                ->get(),
        ];
    }

    // Get recent security incidents
    public function getRecentIncidents($days = 7, $severity = null)
    {
        $query = SecurityIncident::where('created_at', '>=', now()->subDays($days));
        
        if ($severity) {
            $query->where('severity', $severity);
        }
        
        return $query->where('status', '!=', 'false_positive')
            ->orderBy('severity', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    // Get unresolved errors
    public function getUnresolvedErrors($severity = null)
    {
        $query = ErrorLog::where('resolved', false);
        
        if ($severity) {
            $query->where('severity', $severity);
        }
        
        return $query->latest()->get();
    }

    // Generate audit report
    public function generateAuditReport($startDate, $endDate)
    {
        return [
            'period' => [
                'start' => $startDate,
                'end' => $endDate,
            ],
            'summary' => [
                'total_actions' => AuditLog::whereBetween('created_at', [$startDate, $endDate])->count(),
                'unique_users' => AuditLog::whereBetween('created_at', [$startDate, $endDate])->distinct('user_id')->count('user_id'),
                'total_accesses' => AccessLog::whereBetween('created_at', [$startDate, $endDate])->count(),
                'total_errors' => ErrorLog::whereBetween('created_at', [$startDate, $endDate])->count(),
                'critical_incidents' => SecurityIncident::whereBetween('created_at', [$startDate, $endDate])->where('severity', 'critical')->count(),
            ],
            'top_actions' => AuditLog::whereBetween('created_at', [$startDate, $endDate])
                ->select('event', \DB::raw('count(*) as count'))
                ->groupBy('event')
                ->orderBy('count', 'desc')
                ->take(10)
                ->get(),
            'top_users' => AuditLog::whereBetween('created_at', [$startDate, $endDate])
                ->select('user_id', \DB::raw('count(*) as count'))
                ->groupBy('user_id')
                ->orderBy('count', 'desc')
                ->take(10)
                ->with('user')
                ->get(),
        ];
    }

    // Clean old logs based on retention policy
    public function cleanOldLogs($days = 365)
    {
        $cutoffDate = now()->subDays($days);
        
        $deleted = [
            'audit_logs' => AuditLog::where('created_at', '<', $cutoffDate)->delete(),
            'access_logs' => AccessLog::where('created_at', '<', $cutoffDate)->delete(),
            'error_logs' => ErrorLog::where('created_at', '<', $cutoffDate)->where('resolved', true)->delete(),
        ];
        
        return $deleted;
    }

    // Detect suspicious activity
    public function detectSuspiciousActivity()
    {
        $suspiciousPatterns = [];
        
        // Multiple failed login attempts from same IP
        $failedLogins = \DB::table('login_attempts')
            ->where('successful', false)
            ->where('created_at', '>=', now()->subHour())
            ->select('ip_address', \DB::raw('count(*) as count'))
            ->groupBy('ip_address')
            ->having('count', '>=', 5)
            ->get();
        
        if ($failedLogins->isNotEmpty()) {
            foreach ($failedLogins as $login) {
                $suspiciousPatterns[] = [
                    'type' => 'Multiple Failed Logins',
                    'ip_address' => $login->ip_address,
                    'count' => $login->count,
                    'severity' => 'high',
                ];
            }
        }
        
        // Unusual access patterns
        $unusualAccess = AccessLog::where('created_at', '>=', now()->subHour())
            ->where('response_code', '>=', 400)
            ->get()
            ->groupBy('user_id')
            ->filter(function($group) {
                return $group->count() >= 10;
            });
        
        if ($unusualAccess->isNotEmpty()) {
            foreach ($unusualAccess as $userId => $logs) {
                $suspiciousPatterns[] = [
                    'type' => 'Unusual Access Pattern',
                    'user_id' => $userId,
                    'count' => $logs->count(),
                    'severity' => 'medium',
                ];
            }
        }
        
        return $suspiciousPatterns;
    }
}
