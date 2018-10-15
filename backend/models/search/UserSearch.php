<?php

namespace backend\models\search;

use backend\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    public $username;
    public $lastVisit;
    public $role;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['email_verified'], 'boolean'],
            [['username', 'email', 'phone', 'lastVisit', 'role'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = User::find()->select(['{{%user}}.*', '{{%auth_assignment}}.item_name'])
            ->leftJoin('{{%auth_assignment}}', '{{%auth_assignment}}.user_id = {{%user}}.id');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'attributes' => [
                    'id', 'email', 'status', 'email_verified',
                    'username' => [
                        'asc' => ['surname' => SORT_ASC, 'name' => SORT_ASC],
                        'desc' => ['surname' => SORT_DESC, 'name' => SORT_DESC],
                        'default' => SORT_ASC,
                    ],
                    'lastVisit' => [
                        'asc' => ['lastvisit_at' => SORT_ASC],
                        'desc' => ['lastvisit_at' => SORT_DESC],
                        'default' => SORT_ASC,
                    ]
                ],
                'defaultOrder' => ['username' => SORT_ASC]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        if ($this->role === '#N/A') {
            $query->andWhere(['is', '{{%auth_assignment}}.item_name', null]);
        } elseif (!empty($this->role)) {
            $query->andWhere(['{{%auth_assignment}}.item_name' => $this->role]);
        }

        $query->andFilterWhere([
            'status' => $this->status,
            'email_verified' => $this->email_verified,
        ]);

        $query->andFilterWhere(['like', 'name', $this->username])
            ->andFilterWhere(['like', 'surname', $this->username])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}