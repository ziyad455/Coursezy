<?php
echo "SAPI: " . php_sapi_name() . "\n";
echo "PHP Version: " . phpversion() . "\n";
echo "Loaded Configuration File: " . php_ini_loaded_file() . "\n";
echo "PDO MySQL Extension Loaded: " . (extension_loaded('pdo_mysql') ? 'Yes' : 'No') . "\n";
echo "Available PDO Drivers: " . implode(', ', PDO::getAvailableDrivers()) . "\n";
