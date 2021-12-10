<?php

namespace app\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\users\models\Permission;

/**
 * PermissionSearch represents the model behind the search form about `app\modules\users\models\Permission`.
 */
class PermissionSearch extends Permission
{
    public $permission_set_search;
    public $capability_search;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['permission_set_id', 'capability_id'], 'integer'],
            [['created_at', 'permission_set_search', 'capability_search'], 'safe'],
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
        $query = Permission::find();
        $query->leftJoin("capability", "capability.id = permission.capability_id");
        $query->leftJoin("permission_set", "permission_set.id = permission.permission_set_id");

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
            'permission.permission_set_id' => $this->permission_set_id,
            'permission.capability_id' => $this->capability_id,
            'permission.created_at' => $this->created_at,
        ]);
        
        $query->andFilterWhere([ 'like', 'permission_set.name', $this->permission_set_search ]);
        $query->andFilterWhere([ 'like', 'capability.name', $this->capability_search ]);

        return $dataProvider;
    }
}
