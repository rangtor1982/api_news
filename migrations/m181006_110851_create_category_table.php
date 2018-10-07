<?php

use yii\db\Migration;

/**
 * Handles the creation of table `category`.
 */
class m181006_110851_create_category_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'post_count' => $this->integer()->defaultValue(0)
        ]);
        
        $this->batchInsert('category', ['name'], [['IT'], ['Politics']]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('category');
    }
}
