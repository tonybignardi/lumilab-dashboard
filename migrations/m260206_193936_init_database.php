<?php

use yii\db\Migration;

/**
 * Migration: Initialize database schema
 * Executes: php yii migrate/up
 */
class m260206_193936_init_database extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        return $this->executeSqlFile(__DIR__ . '/../database.sql');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m260206_193936_init_database cannot be reverted.\n";
        return false;
    }

    /**
     * Execute SQL file
     */
    protected function executeSqlFile($filePath)
    {
        if (!file_exists($filePath)) {
            throw new \Exception("SQL file not found: $filePath");
        }

        $sql = file_get_contents($filePath);
        $statements = array_filter(array_map('trim', explode(';', $sql)));
        
        echo "\n📋 Total de statements no arquivo: " . count($statements) . "\n";
        
        $count = 0;
        $errors = [];
        
        foreach ($statements as $idx => $statement) {
            // Skip empty lines and comments
            if (empty($statement) || strpos(trim($statement), '--') === 0) {
                continue;
            }
            
            // Skip MySQL directive comments like /*!40101 ...*/
            if (strpos(trim($statement), '/*!') === 0) {
                continue;
            }
            
            try {
                $this->db->createCommand($statement)->execute();
                $count++;
            } catch (\Exception $e) {
                $errorMsg = $e->getMessage();
                
                // Log INSERT errors explicitly
                if (strpos($statement, 'INSERT INTO') === 0) {
                    $preview = substr($statement, 0, 100);
                    $errors[] = "❌ INSERT FAILED [stmt $idx]: $preview... | Error: $errorMsg";
                }
                // Log CREATE errors
                elseif (strpos($statement, 'CREATE TABLE') === 0) {
                    preg_match('/`(\w+)`/', $statement, $m);
                    $table = $m[1] ?? 'unknown';
                    // Only log if it's not "already exists"
                    if (strpos($errorMsg, 'already exists') === false) {
                        $errors[] = "⚠️  CREATE TABLE $table FAILED: $errorMsg";
                    }
                }
                // Log other errors
                else {
                    $preview = substr(trim($statement), 0, 50);
                    $errors[] = "⚠️  STATEMENT [$idx] FAILED: $preview... | $errorMsg";
                }
            }
        }
        
        // Display any errors
        if (!empty($errors)) {
            echo "\n🔴 ERRORS FOUND:\n";
            foreach ($errors as $error) {
                echo "$error\n";
            }
        }
        
        echo "\n✓ Executed $count / " . count($statements) . " SQL statements\n";
        return true;
    }
}
