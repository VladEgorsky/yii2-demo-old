<?php

namespace console\controllers;

use backend\models\User;
use Yii;
use yii\base\Module;
use yii\console\Controller;
use Faker;

class TestDataController extends Controller
{
    // https://github.com/fzaninotto/Faker#fakerprovideren_usaddress
    protected $faker;

    public function __construct($id, Module $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->faker = Faker\Factory::create();
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\db\Exception
     */
    public function actionFillUserTable()
    {
        $data = [];
        $time = time();
        $statuses = User::getStatusListData();

        for ($n = 1; $n <= 12; $n++) {
            $email = "test$n@mail.ru";

            $data[] = [
                'name' => $this->faker->firstName,
                'surname' => $this->faker->lastName,
                'email' => $email,
                'phone' => $this->faker->e164PhoneNumber,
                'auth_key' => Yii::$app->security->generateRandomString(),
                'password_hash' => Yii::$app->security->generatePasswordHash($email),
                'status' => array_rand($statuses),
                'created_at' => $time,
                'updated_at' => rand($time - 100000, $time),
                'lastvisit_at' => rand($time - 10000, $time),
                'email_verified' => rand(0, 1),
            ];
        }

        array_unshift($data, [
            'name' => 'guest',
            'surname' => 'guest',
            'email' => 'guest@mail.ru',
            'phone' => $this->faker->e164PhoneNumber,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('1'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => $time,
            'updated_at' => rand($time - 100000, $time),
            'lastvisit_at' => rand($time - 10000, $time),
            'email_verified' => 1,]);

        array_unshift($data, [
            'name' => 'user',
            'surname' => 'user',
            'email' => 'user@mail.ru',
            'phone' => $this->faker->e164PhoneNumber,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('1'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => $time,
            'updated_at' => rand($time - 100000, $time),
            'lastvisit_at' => rand($time - 10000, $time),
            'email_verified' => 1,
        ]);

        array_unshift($data, [
            'name' => 'manager',
            'surname' => 'manager',
            'email' => 'manager@mail.ru',
            'phone' => $this->faker->e164PhoneNumber,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('1'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => $time,
            'updated_at' => rand($time - 100000, $time),
            'lastvisit_at' => rand($time - 10000, $time),
            'email_verified' => 1,
        ]);

        array_unshift($data, [
            'name' => 'admin',
            'surname' => 'admin',
            'email' => 'admin@mail.ru',
            'phone' => $this->faker->e164PhoneNumber,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('1'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => $time,
            'updated_at' => rand($time - 100000, $time),
            'lastvisit_at' => rand($time - 10000, $time),
            'email_verified' => 1,
        ]);

        array_unshift($data, [
            'name' => 'Yurik',
            'surname' => 'Mazurchak',
            'email' => 'yurik@mail.ru',
            'phone' => $this->faker->e164PhoneNumber,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('1'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => $time,
            'updated_at' => rand($time - 100000, $time),
            'lastvisit_at' => rand($time - 10000, $time),
            'email_verified' => 1,
        ]);

        array_unshift($data, [
            'name' => 'Vlad',
            'surname' => 'Egorsky',
            'email' => 'vlad@mail.ru',
            'phone' => $this->faker->e164PhoneNumber,
            'auth_key' => Yii::$app->security->generateRandomString(),
            'password_hash' => Yii::$app->security->generatePasswordHash('1'),
            'status' => User::STATUS_ACTIVE,
            'created_at' => $time,
            'updated_at' => rand($time - 100000, $time),
            'lastvisit_at' => rand($time - 10000, $time),
            'email_verified' => 1,
        ]);

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%user}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%user}}', [
            'name', 'surname', 'email', 'phone', 'auth_key', 'password_hash', 'status', 'created_at',
            'updated_at', 'lastvisit_at', 'email_verified',
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionFillSectionTable()
    {
        $data = [];
        $sections = [
            'Science & Education', 'Business & Tech', 'Design & Fashion', 'Picture of the day',
            'Environment', 'Video', 'Culture & Art', 'Travel', 'Real estate', 'Sport', 'Weather', 'Crime'
        ];

        foreach ($sections as $k => $name) {
            $data[] = ['title' => $name, 'ordering' => $k + 1];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%section}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%section}}', [
            'title', 'ordering',
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionFillTagTable()
    {
        $data = [];
        $tags = [
            'Breaking', 'Urgent', 'Developing', 'Moscow', 'St.Petersburg', 'Omsk', 'Novosibirsk',
            'Current affairs', 'Archeology', 'Crime', 'Transport', 'Emergency', 'Crypto', 'Space development',
            'Football', 'Hockey', 'Snowboarding', 'Weird'
        ];

        foreach ($tags as $tag) {
            $data[] = ['title' => $tag];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%tag}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%tag}}', [
            'title',
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    /**
     * @throws \yii\db\Exception
     */
    public function actionFillNewsTable()
    {
        $data = [];
        $time = time();

        for ($n = 1; $n <= 500; $n++) {
            $data[] = [
                'title' => $this->faker->sentence(8),
                'short_content' => $this->faker->sentences(1, true),
                'content' => $this->faker->sentences(10, true),
                'author' => $this->faker->name,
                'comment_count' => rand(0, 1000),
                'status' => rand(0, 1),
                'created_at' => $time,
            ];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%news}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%news}}', [
            'title', 'short_content', 'content', 'author', 'comment_count', 'status', 'created_at'
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    public function actionFillNewsSectionTable()
    {
        $data = [];

        for ($n = 1; $n <= 500; $n++) {
            $data[] = [
                'news_id' => $n,
                'section_id' => rand(1, 11),
            ];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%news_section}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%news_section}}', [
            'news_id', 'section_id'
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    public function actionFillNewsTagTable()
    {
        $data = [];

        for ($n = 1; $n <= 500; $n++) {
            $data[] = [
                'news_id' => $n,
                'tag_id' => rand(1, 18),
            ];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%news_tag}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%news_tag}}', [
            'news_id', 'tag_id'
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    public function actionFillAdvertiseTable()
    {
        $data = [];
        $time = time();

        for ($n = 1; $n <= 100; $n++) {
            $data[] = [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email,
                'content' => $this->faker->sentence(8),
                'status' => rand(0, 1),
                'created_at' => $time,
            ];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%advertise}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%advertise}}', [
            'name', 'email', 'content', 'status', 'created_at'
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    public function actionFillSubscribeTable()
    {
        $data = $data1 = $data2 = [];
        $time = time();

        for ($n = 1; $n <= 100; $n++) {
            $data[] = [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email,
                'status' => rand(0, 1),
                'period' => rand(1, 3),
                'created_at' => $time,
            ];

            $data1[] = [
                'subscribe_id' => $n,
                'section_id' => rand(1, 11),
            ];

            $data2[] = [
                'subscribe_id' => $n,
                'tag_id' => rand(1, 18),
            ];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%subscribe}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%subscribe}}', [
            'name', 'email', 'status', 'period', 'created_at'
        ], $data)->execute();

        Yii::$app->db->createCommand()->truncateTable('{{%subscribe_section}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%subscribe_section}}', [
            'subscribe_id', 'section_id'
        ], $data1)->execute();

        Yii::$app->db->createCommand()->truncateTable('{{%subscribe_tag}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%subscribe_tag}}', [
            'subscribe_id', 'tag_id'
        ], $data2)->execute();

        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }

    public function actionFillStoryTable()
    {
        $data = [];
        $time = time();

        for ($n = 1; $n <= 100; $n++) {
            $data[] = [
                'name' => $this->faker->firstName,
                'email' => $this->faker->email,
                'content' => $this->faker->sentences(5, true),
                'status' => rand(0, 1),
                'created_at' => $time,
            ];
        }

        Yii::$app->db->createCommand('set foreign_key_checks=0')->execute();
        Yii::$app->db->createCommand()->truncateTable('{{%story}}')->execute();
        Yii::$app->db->createCommand()->batchInsert('{{%story}}', [
            'name', 'email', 'content', 'status', 'created_at'
        ], $data)->execute();
        Yii::$app->db->createCommand('set foreign_key_checks=1')->execute();
    }
}