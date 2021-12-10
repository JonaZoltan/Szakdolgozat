<?php

namespace app\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\users\models\PermissionSet;

use app\modules\users\models\User;

/**
 * PermissionSetSearch represents the model behind the search form about `app\modules\users\models\PermissionSet`.
 */
class PermissionSetSearch extends PermissionSet
{
    public $user_search;
    public $capability_search;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'user_search', 'capability_search'], 'safe'],
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
        $query = PermissionSet::find();
        $query->leftJoin("user", "user.id = permission_set.user_id");
         $query->leftJoin("permission", "permission.permission_set_id = permission_set.id");
        $query->leftJoin("capability", "capability.id = permission.capability_id");

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
            'permission_set.id' => $this->id,
            'permission_set.user_id' => $this->user_id,
            'capability.id' => $this->capability_search,
        ]);

        $query->andFilterWhere(['like', 'permission_set.name', $this->name]);
        $query->andFilterWhere(['like', 'user.name', $this->user_search]);

        return $dataProvider;
    }
}
