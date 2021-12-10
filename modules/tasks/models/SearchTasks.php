<?php

namespace app\modules\tasks\models;

use app\modules\apps\models\Apps;
use app\modules\project\models\ProjectMembership;
use app\modules\users\models\User;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * SearchTasks represents the model behind the search form about `app\modules\tasks\models\Tasks`.
 */
class SearchTasks extends Tasks
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'worktype_id', 'workplace_id', 'user_id', 'recommended_hours', 'recognized', 'planned', 'verified'], 'integer'],
            [['text', 'working_datetime_start', 'working_datetime_end', 'comment', 'verified_comment'], 'safe'],
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
        $query = Tasks::find();
        $query->orderBy('date(working_datetime_start) DESC, time(working_datetime_start) ASC');

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
	        $memberLeader = ProjectMembership::find()->where(['user_id' => $user->id, 'leader' => true])->asArray()->indexBy('project_id')->all();
	        $query->andFilterWhere(['or',
		        ['=', 'user_id', $user->id], // Csak saját magát
		        ['in', 'project_id', array_keys($memberLeader)], // Ahol leader azt a csoportot
	        ]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $this->project_id,
            'worktype_id' => $this->worktype_id,
            'workplace_id' => $this->workplace_id,
            'user_id' => $this->user_id,
            'recommended_hours' => $this->recommended_hours,
            'recognized' => $this->recognized,
	        'planned' => $this->planned,
	        'verified' => $this->verified,
        ]);

        $query->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'comment', $this->comment]);

	    $query->andFilterWhere(['like', 'text', $this->text])
		    ->andFilterWhere(['like', 'verified_comment', $this->verified_comment]);

	    $date_explode = explode(" - ", $this->working_datetime_start);
	    if(count($date_explode) === 2)
		    $query->andFilterWhere(['or',
			    ['between', 'date(working_datetime_start)', trim($date_explode[0]), trim($date_explode[1])],
			    ['between', 'date(working_datetime_end)', trim($date_explode[0]), trim($date_explode[1])],
		    ]);

        return $dataProvider;
    }
}
