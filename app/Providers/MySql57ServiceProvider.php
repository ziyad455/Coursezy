<?php

namespace App\Providers;

use App\Database\MySqlGrammar;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

class MySql57ServiceProvider extends ServiceProvider
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
        Connection::resolverFor('mysql', function ($connection, $database, $prefix, $config) {
            $mysqlConnection = new \Illuminate\Database\MySqlConnection($connection, $database, $prefix, $config);
            
            // Use our custom grammar for MySQL 5.7 compatibility
            $grammar = new MySqlGrammar($mysqlConnection);
            $mysqlConnection->setSchemaGrammar($grammar);
            
            return $mysqlConnection;
        });
    }
}
