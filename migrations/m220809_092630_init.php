<?php

use yii\db\Migration;

/**
 * Class m220809_092630_init
 */
class m220809_092630_init extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(128)->notNull(),
            'password' => $this->string(128)->notNull(),
            'email' => $this->string(128)->notNull(),
            'profile' => $this->text(),
            'auth_key' => $this->text()
        ], $tableOptions);

        $this->createTable('{{%lookup}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'code' => $this->integer()->notNull(),
            'type' => $this->string(128)->notNull(),
            'position' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(128)->notNull(),
            'content' => $this->text()->notNull(),
            'tags' => $this->text(),
            'status' => $this->integer()->notNull(),
            'create_time' => $this->integer(),
            'update_time' => $this->integer(),
            'author_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'FK_post_author',
            '{{%post}}',
            'author_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('{{%comment}}', [
            'id' => $this->primaryKey(),
            'content' => $this->text()->notNull(),
            'status' => $this->integer()->notNull(),
            'create_time' => $this->integer(),
            'author' => $this->string(128)->notNull(),
            'email' => $this->string(128)->notNull(),
            'url' => $this->string(128),
            'post_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey(
            'FK_comment_post',
            '{{%comment}}',
            'post_id',
            '{{%post}}',
            'id',
            'CASCADE',
            'RESTRICT'
        );

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(128)->notNull(),
            'frequency' => $this->integer()->defaultValue(1),
        ], $tableOptions);

        $this->insert('{{%lookup}}', ['name' => 'Draft', 'type' => 'PostStatus', 'code' => 1, 'position' => 1]);
        $this->insert('{{%lookup}}', ['name' => 'Published', 'type' => 'PostStatus', 'code' => 2, 'position' => 2]);
        $this->insert('{{%lookup}}', ['name' => 'Archived', 'type' => 'PostStatus', 'code' => 3, 'position' => 3]);
        $this->insert('{{%lookup}}', ['name' => 'Pending Approval', 'type' => 'CommentStatus', 'code' => 1, 'position' => 1]);
        $this->insert('{{%lookup}}', ['name' => 'Approved', 'type' => 'CommentStatus', 'code' => 2, 'position' => 2]);

        $this->insert('{{%user}}', [
            'username' => 'demo',
            'password' => '$2a$10$JTJf6/XqC94rrOtzuF397OHa4mbmZrVTBOQCmYD9U.obZRUut4BoC',
            'email' => 'webmaster@example.com',
            'auth_key' => ''
        ]);

        $this->insert('{{%post}}', [
            'title' => 'Welcome!',
            'content' => 'This blog system is developed using Yii. It is meant to demonstrate how to use Yii to build a complete real-world application. Complete source code may be found in the Yii releases.
Feel free to try this system by writing new posts and posting comments.',
            'status' => 2,
            'create_time' => 1230952187,
            'update_time' => 1230952187,
            'author_id' => 1,
            'tags' => 'yii, blog'
        ]);

        $this->insert('{{%post}}', [
            'title' => 'A Test Post',
            'content' => 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.',
            'status' => 2,
            'create_time' => 1230952187,
            'update_time' => 1230952187,
            'author_id' => 1,
            'tags' => 'test'
        ]);

        $this->insert('{{%comment}}', [
            'content' => 'This is a test comment.',
            'status' => 2,
            'create_time' => 1230952187,
            'author' => 'Tester',
            'email' => 'tester@example.com',
            'post_id' => 2
        ]);

        $this->insert('{{%tag}}', ['name' => 'yii']);
        $this->insert('{{%tag}}', ['name' => 'blog']);
        $this->insert('{{%tag}}', ['name' => 'test']);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%lookup}}');
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%comment}}');
        $this->dropTable('{{%tag}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m220809_092630_init cannot be reverted.\n";

        return false;
    }
    */
}
