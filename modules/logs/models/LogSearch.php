<?php

namespace app\modules\logs\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\logs\models\Log;
use app\modules\users\models\User;

/**
 * LogSearch represents the model behind the search form about `app\modules\logs\models\Log`.
 */
class LogSearch extends Log
{
    public $log_text;
    public $event_name;
    public $created_at_range;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'log_event_id', 'user_id'], 'integer'],
            [['parameters', 'created_at', 'log_text', 'event_name', 'created_at_range'], 'safe'],
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
        $query = Log::find();
        
        $query->leftJoin("log_event", "log_event.id = log.log_event_id");

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

        // grid filtering conditions
        $query->andFilterWhere([
            'log.id' => $this->id,
            'log.log_event_id' => $this->log_event_id,
            'log.user_id' => $this->user_id,
            'log_event.name' => $this->event_name,
        ]);

        $query->andFilterWhere(['like', 'log.parameters', $this->parameters]);
        $query->andFilterWhere(['like', 'log.created_at', $this->created_at]);
        $query->andFilterWhere([
            'or',
            ['like', 'log_event.description', $this->log_text],
            ['like', 'log.parameters', $this->log_text],
            ['like', 'log.cached_text', $this->log_text],
        ]);
        
        if ($this->created_at_range) {
            $range = explode(" - ", $this->created_at_range);
            if (count($range) === 1) {
                $query->andFilterWhere(['like', 'log.created_at', trim($range[0])]);
            } else {
                $query->andFilterWhere(['between', 'log.created_at', trim($range[0]), trim($range[1])]);
            }
        }

        return $dataProvider;
    }
}
