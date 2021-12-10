<?php

namespace app\components\sendmail\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\components\Sendmail;

/**
 * SearchSendmail represents the model behind the search form about `app\components\Sendmail`.
 */
class SearchSendmail extends Sendmail
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'priority'], 'integer'],
            [['sender', 'to', 'subject', 'body', 'status', 'completed_time', 'attachment', 'response', 'timestamp'], 'safe'],
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
        $query = Sendmail::find();
        $query->orderBy('timestamp DESC');

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
            'priority' => $this->priority,
            'completed_time' => $this->completed_time,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'sender', $this->sender])
            ->andFilterWhere(['like', 'to', $this->to])
            ->andFilterWhere(['like', 'subject', $this->subject])
            ->andFilterWhere(['like', 'body', $this->body])
            ->andFilterWhere(['like', 'status', ($this->status==='-1'?null:$this->status)])
            ->andFilterWhere(['like', 'attachment', $this->attachment])
            ->andFilterWhere(['like', 'response', $this->response]);

        return $dataProvider;
    }
}
