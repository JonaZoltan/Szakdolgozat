<?php

namespace app\modules\project\models;

use app\modules\users\models\User;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\project\models\Project;

/**
 * SearchProject represents the model behind the search form about `app\modules\project\models\Project`.
 */
class SearchProject extends Project
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'area_id'], 'integer'],
            [['name', 'text', 'color', 'timestamp','partner_ids'], 'safe'],
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
        $query = Project::find();

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

	    $user = User::current();
	    if(!$user->can('view_all_tasks')) {
		    $members = ProjectMembership::find()->where(['user_id' => $user->id])->asArray()->indexBy('project_id')->all();
		    $query->andFilterWhere(['or',
			    ['in', 'id', array_keys($members)],
		    ]);
	    }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'area_id' => $this->area_id,
            'timestamp' => $this->timestamp,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'color', $this->color])
	        ->andFilterWhere(['like', 'partner_ids', !$this->partner_ids ? $this->partner_ids : "\"$this->partner_ids\""]);

        $query->orderBy('area_id, name');

        return $dataProvider;
    }
}
