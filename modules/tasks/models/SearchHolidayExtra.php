<?php

namespace app\modules\tasks\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\tasks\models\HolidayExtra;

/**
 * SearchHolidayExtra represents the model behind the search form about `app\modules\tasks\models\HolidayExtra`.
 */
class SearchHolidayExtra extends HolidayExtra
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'holiday_day', 'year'], 'integer'],
            [['disabled_user'], 'safe'],
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
        $query = HolidayExtra::find();

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
            'holiday_day' => $this->holiday_day,
            'disabled_user' => $this->disabled_user,
            'year' => $this->year,
        ]);

        return $dataProvider;
    }
}
