<?php

namespace backend\models\search;

use backend\models\Subscribe;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SubscribeSearch represents the model behind the search form of `backend\models\Subscribe`.
 */
class SubscribeSearch extends Subscribe
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'period'], 'integer'],
            [['name', 'email', 'created_at'], 'safe'],
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
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Subscribe::find()->with(['sections', 'tags']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => ['id' => SORT_DESC],
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id'         => $this->id,
            'status'     => $this->status,
            'period'     => $this->period,
        ]);

        if ($this->created_at) {
            $d = strtotime($this->created_at);
            $query->andFilterWhere(['between', 'created_at', $d, $d + 86399]);
        }

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'email', $this->email]);

        return $dataProvider;
    }
}
