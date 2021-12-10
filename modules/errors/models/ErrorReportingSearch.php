<?php

namespace app\modules\errors\models;

use app\modules\users\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\errors\models\ErrorReporting;

/**
 * ErrorReportingSearch represents the model behind the search form about `app\modules\errors\models\ErrorReporting`.
 */
class ErrorReportingSearch extends ErrorReporting
{
    public $user_search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'subject'], 'integer'],
            [['message', 'user_agent', 'created_at', 'user_search'], 'safe'],
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
        $query = ErrorReporting::find();
        $query->leftJoin("user", "user.id = error_reporting.user_id");

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'=> ['defaultOrder' => ['created_at'=>SORT_DESC]]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        /**
         * Mindenki csak a saját hibajelentéseit látja.
         * @var $user User
         */
        $user = User::current();
        if(!$user->is_admin)
        	$query->andFilterWhere(['error_reporting.created_at' => $user->id]);

        // grid filtering conditions
        $query->andFilterWhere([
            'error_reporting.id' => $this->id,
            'error_reporting.user_id' => $this->user_id,
            'error_reporting.created_at' => $this->created_at,
            'error_reporting.subject' => $this->subject,
        ]);

        $query->andFilterWhere(['like', 'error_reporting.message', $this->message])
            ->andFilterWhere(['like', 'error_reporting.user_agent', $this->user_agent])
            ->andFilterWhere([
                'or',
                ['like', 'user.name', $this->user_search],
                ['=', 'user.id', $this->user_search],
            ]);

        return $dataProvider;
    }
}
