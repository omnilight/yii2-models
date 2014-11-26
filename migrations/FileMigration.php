<?php

namespace omnilight\models\migrations;

use yii\db\Migration;
use yii\db\Schema;


/**
 * Class File
 */
class FileMigration
{
    /**
     * @param Migration $migration
     * @param string $tableName
     */
    public static function migrateUp($migration, $tableName = '{{%files}}')
    {
        $tableOptions = null;
        if ($migration->db->driverName === 'mysql') {
            $tableOptions = 'ENGINE=InnoDB CHARSET=utf8';
        }

        $migration->createTable($tableName, [
            'id' => Schema::TYPE_PK,
            'original_name' => Schema::TYPE_STRING,
            'file_size' => Schema::TYPE_INTEGER,
            'created_at' => Schema::TYPE_DATETIME,
            'updated_at' => Schema::TYPE_DATETIME,
        ]);
    }

    /**
     * @param Migration $migration
     * @param string $tableName
     */
    public static function migrateDown($migration, $tableName = '{{%files}}')
    {
        $migration->dropTable($tableName);
    }
} 