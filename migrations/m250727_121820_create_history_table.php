<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%history}}`.
 */
class m250727_121820_create_history_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%history}}', [
            'id' => $this->primaryKey(),
            'name' => $this->text()->notNull()->comment('Имя'),
            'ip' => $this->text()->notNull()->comment('IP адрес'),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP')->comment('Создано'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%history}}');
    }
}
