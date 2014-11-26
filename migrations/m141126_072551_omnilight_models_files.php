<?php

use omnilight\models\migrations\FileMigration;
use yii\db\Migration;

class m141126_072551_omnilight_models_files extends Migration
{
    public function up()
    {
        FileMigration::migrateUp($this, '{{%files}}');
    }

    public function down()
    {
        FileMigration::migrateDown($this, '{{%files}}');
    }
}
