<?php

use yii\db\Migration;

/**
 * Книги каталога.
 */
class m200101_000003_create_book_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'year' => $this->integer()->notNull(),
            'description' => $this->text(),
            'isbn' => $this->string(20)->notNull(),
            'photo' => $this->string(255),
            'created_by' => $this->integer(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-book-isbn', '{{%book}}', 'isbn', true);
        $this->createIndex('idx-book-year', '{{%book}}', 'year');
        $this->createIndex('idx-book-created_by', '{{%book}}', 'created_by');

        $this->addForeignKey(
            'fk-book-created_by',
            '{{%book}}',
            'created_by',
            '{{%user}}',
            'id',
            'SET NULL',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%book}}');
    }
}
