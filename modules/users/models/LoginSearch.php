<?php

namespace app\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\users\models\Login;

/**
 * LoginSearch represents the model behind the search form about `app\modules\users\models\Login`.
 */
class LoginSearch extends Login
{
    public $user_search;
    public $group_search;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['start_date', 'end_date', 'token', 'ip_address', 'user_agent', 'user_search'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = Login::find();
        $query->leftJoin("user", "user.id = login.user_id");


        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['start_date'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([">", "login.user_id", 2]);
        $query->andFilterWhere([">", "login.end_date", date('Y-m-d H:i:s')]);


        // grid filtering conditions
        $query->andFilterWhere([
            'login.id' => $this->id,
            'login.start_date' => $this->start_date,
            'login.end_date' => $this->end_date,
        ]);

        $query->andFilterWhere(['like', 'login.token', $this->token])
            ->andFilterWhere(['like', 'login.ip_address', $this->ip_address])
            ->andFilterWhere(['like', 'login.user_agent', $this->user_agent]);

        $query->andFilterWhere([
            'login.id' => $this->id,
            'login.user_id' => $this->user_id,
            'login.start_date' => $this->start_date,
            'login.end_date' => $this->end_date,
        ]);
        
        $query->andFilterWhere([
            'or',
            ["like", "user.name", $this->user_search],
            ["=", "user.id", $this->user_search],
        ]);
          
        $query->andFilterWhere(["like", "group.name", $this->group_search]);


        return $dataProvider;
    }
}
