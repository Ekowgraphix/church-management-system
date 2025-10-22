<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait DatabaseCompatible
{
    /**
     * Check if using SQLite database
     */
    protected function isSQLite(): bool
    {
        return config('database.default') === 'sqlite';
    }

    /**
     * Get database-compatible MONTH expression
     */
    protected function monthExpression(string $column): string
    {
        if ($this->isSQLite()) {
            return "CAST(strftime('%m', {$column}) AS INTEGER)";
        }
        return "MONTH({$column})";
    }

    /**
     * Get database-compatible YEAR expression
     */
    protected function yearExpression(string $column): string
    {
        if ($this->isSQLite()) {
            return "CAST(strftime('%Y', {$column}) AS INTEGER)";
        }
        return "YEAR({$column})";
    }

    /**
     * Get database-compatible DAY expression
     */
    protected function dayExpression(string $column): string
    {
        if ($this->isSQLite()) {
            return "CAST(strftime('%d', {$column}) AS INTEGER)";
        }
        return "DAY({$column})";
    }

    /**
     * Scope for filtering by month (database-agnostic)
     */
    protected function scopeWhereMonth($query, string $column, int $month)
    {
        if ($this->isSQLite()) {
            return $query->whereRaw("CAST(strftime('%m', {$column}) AS INTEGER) = ?", [$month]);
        }
        return $query->whereMonth($column, $month);
    }

    /**
     * Scope for filtering by year (database-agnostic)
     */
    protected function scopeWhereYear($query, string $column, int $year)
    {
        if ($this->isSQLite()) {
            return $query->whereRaw("CAST(strftime('%Y', {$column}) AS INTEGER) = ?", [$year]);
        }
        return $query->whereYear($column, $year);
    }

    /**
     * Scope for filtering by day (database-agnostic)
     */
    protected function scopeWhereDay($query, string $column, int $day)
    {
        if ($this->isSQLite()) {
            return $query->whereRaw("CAST(strftime('%d', {$column}) AS INTEGER) = ?", [$day]);
        }
        return $query->whereDay($column, $day);
    }
}
