<?php

namespace api\modules\v1\models;


use common\models\user\User as UserBase;
use common\models\user\User;
use yii\helpers\ArrayHelper;

class PublicUser extends UserBase
{
    public function fields()
    {
        return [
            'id',
            'name',
            'facebook_id',
            'facebook_link',
            'city',
            'img' => 'urlImage',
            'state_code',
            'created_at',
            'rank',
            'score'
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInviter()
    {
        return $this->hasOne(PublicUser::className(), ['id' => 'inviter_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getInvited()
    {
        return $this->hasMany(PublicUser::className(), ['inviter_id' => 'id']);
    }

}