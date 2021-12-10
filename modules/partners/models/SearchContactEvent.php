<?php

namespace app\modules\partners\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\partners\models\ContactEvent;
use yii\i18n\Formatter;

/**
 * SearchContactEvent represents the model behind the search form about `app\modules\partners\models\ContactEvent`.
 */
class SearchContactEvent extends ContactEvent
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'partner_id', 'contact_id', 'type'], 'integer'],
            [['when', 'note'], 'safe'],
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
        $query = ContactEvent::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'partner_id' => $this->partner_id,
            'contact_id' => $this->contact_id,
            'type' => $this->type,
        ]);

        $query->andFilterWhere(['like', 'user_id', $this->user_id])
              ->andFilterWhere(['like', 'partner_id', $this->partner_id])
              ->andFilterWhere(['like', 'contact_id', $this->contact_id])
              ->andFilterWhere(['like', 'type', $this->type])
              ->andFilterWhere(['like', 'when', !$this->when ? $this->when : "$this->when"]);

        return $dataProvider;
    }
}
