<?php

namespace app\modules\partners\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchPartners represents the model behind the search form about `app\modules\partners\models\Partners`.
 */
class SearchPartners extends Partners
{
	public $contactEvent;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'alert_day'], 'integer'],
            [['contactEvent', 'name', 'note', 'user_ids'], 'safe'],
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
        $query = Partners::find();
	    $query->joinWith(['contactEvent']);
        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

	    $dataProvider->sort->attributes['contactEvent'] = [
		    'asc' => ['contact_event.when' => SORT_ASC],
		    'desc' => ['contact_event.when' => SORT_DESC],
	    ];

	    if (!($this->load($params) && $this->validate())) {
		    return $dataProvider;
	    }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'note', $this->note])
            ->andFilterWhere(['like', 'alert_day', $this->alert_day])
            ->andFilterWhere(['like', 'contact_event.when', !$this->contactEvent ? $this->contactEvent : "$this->contactEvent"])
            ->andFilterWhere(['like', 'user_ids', !$this->user_ids ? $this->user_ids : "\"$this->user_ids\""]);

        return $dataProvider;
    }
}
