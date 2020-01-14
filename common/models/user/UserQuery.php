<?php

namespace common\models\user;

use common\models\Config;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the ActiveQuery class for [[User]].
 *
 * @see User
 */
class UserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return User[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return User|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }

    /**
     * @return $this
     */
    public function random($limit = 1)
    {
        return $this->orderBy(new Expression('rand()'))->limit($limit);
    }

    /**
     * @return $this
     */
    public function inviteBetween($start,$end)
    {
        return $this->andWhere([
            'and',
            ['>=', sprintf('%s.inviter_date', User::tableName()), $start],
            ['<=', sprintf('%s.inviter_date', User::tableName()), $end]
        ]);
    }
    /**
     * @return $this
     */
    public function inviter($user_id)
    {
        return $this->andWhere([
            sprintf('%s.inviter_id', User::tableName()) => $user_id,
        ]);
    }


}
