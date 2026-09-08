<?php

use yii\db\Migration;

/**
 * Подписки гостей на новые книги авторов.
 */
class m200101_000005_create_subscription_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%subscription}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'created_at' => $this->integer()->notNull(),
        ]);

        // Один гость (номер телефона) подписывается на автора только один раз.
        $this->createIndex('idx-subscription-author_phone', '{{%subscription}}', ['author_id', 'phone'], true);

        $this->addForeignKey(
            'fk-subscription-author_id',
            '{{%subscription}}',
            'author_id',
            '{{%author}}',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropTable('{{%subscription}}');
    }
}
