<?php

namespace app\modules\users\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\users\models\User;

/**
 * UserSearch represents the model behind the search form about `app\modules\users\models\User`.
 */
class UserSearch extends User
{
    public $permission_set_search;
    public $created_by_search;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'permission_set_id', 'is_admin', 'suspended', 'created_by'], 'integer'],
            [['name', 'email', 'created_at', 'password_hash', 'created_by_search', 'permission_set_search', 'rfid', 'quickmenu', 'last_login'], 'safe'],
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
        $query = User::find();
        $query->leftJoin("user as creator", "creator.id = user.id");
        $query->leftJoin("permission_set", "user.permission_set_id = permission_set.id");
        $query->orderBy('is_admin DESC, permission_set_id, name');
        
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

        if(!User::current()->is_admin) {
	        $query->andFilterWhere(['like', 'user.is_admin', 0]); // Így nem jelenünk meg a listában!
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user.created_at' => $this->created_at,
            'user.permission_set_id' => $this->permission_set_id,
            'user.is_admin' => $this->is_admin,
            'user.suspended' => $this->suspended,
            'user.created_by' => $this->created_by,
	        'user.quickmenu' => $this->quickmenu,
	        'user.last_login' => $this->last_login,
        ]);

        $query->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'user.email', $this->email])
            ->andFilterWhere(['like', 'user.rfid', $this->rfid])
            ->andFilterWhere(['like', 'user.password_hash', $this->password_hash]);
            
        $query->andFilterWhere(['like', 'creator.name', $this->created_by_search]);
        $query->andFilterWhere(['like', 'permission_set.name', $this->permission_set_search]);

        //var_dump($this->is_admin); die();
        //var_dump($query -> createCommand() ->sql); die();

        return $dataProvider;
    }
}
