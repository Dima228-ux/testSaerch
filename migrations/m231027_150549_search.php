<?php

use yii\db\Migration;

/**
 * Class m231027_150549_search
 */
class m231027_150549_search extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%search}}', [
            'id' => $this->primaryKey(),
            'search_string' => $this->text(),
            'json_result' => $this->json(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%search}}');
    }
}
