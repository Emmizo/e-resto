<?php

namespace App\Providers;

use App\Events\DatabaseChanged;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class DatabaseEventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        DB::listen(function ($query) {
            if (str_starts_with($query->sql, 'insert into')) {
                $this->handleInsert($query);
            } elseif (str_starts_with($query->sql, 'update')) {
                $this->handleUpdate($query);
            } elseif (str_starts_with($query->sql, 'delete from')) {
                $this->handleDelete($query);
            }
        });
    }

    /**
     * Handle insert queries
     */
    protected function handleInsert($query)
    {
        $table = $this->getTableName($query->sql);
        event(new DatabaseChanged($table, 'insert', $query->bindings));
    }

    /**
     * Handle update queries
     */
    protected function handleUpdate($query)
    {
        $table = $this->getTableName($query->sql);
        event(new DatabaseChanged($table, 'update', $query->bindings));
    }

    /**
     * Handle delete queries
     */
    protected function handleDelete($query)
    {
        $table = $this->getTableName($query->sql);
        event(new DatabaseChanged($table, 'delete', $query->bindings));
    }

    /**
     * Extract table name from SQL query
     */
    protected function getTableName($sql)
    {
        if (preg_match('/\b(?:into|from|update)\s+`?(\w+)`?/i', $sql, $matches)) {
            return $matches[1];
        }
        return '';
    }
}
