#!/usr/bin/env php
<?php
/**
 * Setup script - Initialize database with migrations
 * 
 * Usage:
 *   php setup.php              # Run all pending migrations
 *   php setup.php --fresh      # Drop all and re-run migrations
 */

// Load environment
require 'config/env-loader.php';

use yii\console\Application;
use yii\db\Connection;

// Set up Yii
defined('YII_DEBUG') or define('YII_DEBUG', getenv('APP_DEBUG') !== 'false');
defined('YII_ENV') or define('YII_ENV', getenv('APP_ENV') ?: 'dev');

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/config/migrate.php';

// Create app
try {
    $app = new Application($config);
    
    // Get arguments
    $fresh = in_array('--fresh', $_SERVER['argv']);
    
    echo "╔════════════════════════════════════════╗\n";
    echo "║      Database Setup - Migrations       ║\n";
    echo "╚════════════════════════════════════════╝\n\n";
    
    // Create DB if not exists
    echo "📦 Creating database if not exists...\n";
    $host = getenv('DB_HOST') ?: 'localhost';
    $user = getenv('DB_USER') ?: 'root';
    $pass = getenv('DB_PASSWORD') ?: '';
    $dbname = getenv('DB_NAME') ?: 'lumilab23';
    
    try {
        $pdo = new PDO("mysql:host=$host", $user, $pass);
        $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "✓ Database ready: $dbname\n\n";
    } catch (PDOException $e) {
        echo "❌ Database creation failed: " . $e->getMessage() . "\n";
        exit(1);
    }
    
    // Fresh install - drop all migrations
    if ($fresh) {
        echo "🗑️  Fresh install mode - rolling back all migrations...\n";
        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
            $pdo->exec("DROP TABLE IF EXISTS migration");
            echo "✓ Migration history cleared\n\n";
        } catch (PDOException $e) {
            echo "⚠ Warning: " . $e->getMessage() . "\n\n";
        }
    }
    
    // Run migrations
    echo "🔄 Running migrations...\n";
    $exitCode = $app->runAction('migrate/up', ['interactive' => false]);
    
    if ($exitCode === 0) {
        echo "\n✅ Database setup completed successfully!\n";
        echo "\nYour application is ready to run:\n";
        echo "  php yii serve\n\n";
        echo "Login credentials:\n";
        echo "  - Any email from the database\n";
        echo "  - Password: " . (getenv('MASTER_PASSWORD') ?: '@lumilab789') . "\n";
    } else {
        echo "\n❌ Migration failed!\n";
        exit(1);
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    if (YII_DEBUG) {
        echo $e->getTraceAsString() . "\n";
    }
    exit(1);
}
