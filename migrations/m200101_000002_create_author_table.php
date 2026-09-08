<?php

use yii\db\Migration;

/**
 * Авторы книг (ФИО).
 */
class m200101_000002_create_author_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(255)->notNull(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-author-full_name', '{{%author}}', 'full_name');
    }

    public function safeDown()
    {
        $this->dropTable('{{%author}}');
    }
}
