<?php

namespace backend\models;

use DateTime;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\user\User;

/**
 * UserSearch represents the model behind the search form about `common\models\user\User`.
 */
class UserSearch extends User
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'facebook_id', 'inviter_id', 'status', 'updated_at'], 'integer'],
            [['email', 'name', 'document', 'street', 'number', 'neighborhood', 'zip_code', 'complement', 'city', 'state_code', 'country', 'phone','created_at', 'gender'], 'safe'],
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
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        if (!empty($this->created_at)) {
            $dataRanger = explode(' - ', $this->created_at);

            if (count($dataRanger) == 2) {
                $data_inicio = DateTime::createFromFormat('d/m/Y H:i:s', $dataRanger[0] .  ' 00:00:00')->format('U');
                $data_fim = DateTime::createFromFormat('d/m/Y H:i:s', $dataRanger[1]. ' 23:59:59')->format('U');
                $query->andWhere([
                    'and',
                    ['>=', sprintf('%s.created_at', User::tableName()),$data_inicio],
                    ['<=', sprintf('%s.created_at', User::tableName()),$data_fim]
                ]);
            }
        }
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'facebook_id' => $this->facebook_id,
            'inviter_id' => $this->inviter_id,
            'status' => $this->status,
            'state_code' => $this->state_code,
            'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'auth_key', $this->auth_key])
            ->andFilterWhere(['like', 'document', $this->document])
            ->andFilterWhere(['like', 'street', $this->street])
            ->andFilterWhere(['like', 'number', $this->number])
            ->andFilterWhere(['like', 'neighborhood', $this->neighborhood])
            ->andFilterWhere(['like', 'zip_code', $this->zip_code])
            ->andFilterWhere(['like', 'complement', $this->complement])
            ->andFilterWhere(['like', 'city', $this->city])
            ->andFilterWhere(['like', 'country', $this->country])
            ->andFilterWhere(['like', 'phone', $this->phone]);
        $query->orderBy('id DESC');
        return $dataProvider;
    }
}
