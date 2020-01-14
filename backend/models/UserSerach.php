<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\User;

/**
 * UserSerach represents the model behind the search form of `common\models\user\User`.
 */
class UserSerach extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'facebook_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'name', 'android_token', 'auth_key', 'password_hash', 'password_reset_token', 'document', 'phone', 'birthday', 'street', 'number', 'neighborhood', 'zip_code', 'complement', 'city', 'state_code', 'country'], 'safe'],
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

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['id' => SORT_DESC]]
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
            'facebook_id' => $this->facebook_id,
            'birthday' => $this->birthday,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'android_token', $this->android_token])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'password_hash', $this->password_hash])
            ->andFilterWhere(['like', 'password_reset_token', $this->password_reset_token])
            ->andFilterWhere(['like', 'document', $this->document])
            ->andFilterWhere(['like', 'phone', $this->phone])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'neighborhood', $this->neighborhood])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'complement', $this->complement])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'state_code', $this->state_code])
            ->andFilterWhere(['like', 'country', $this->country]);

        return $dataProvider;
    }
}
