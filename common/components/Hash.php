<?php

namespace common\components;

use Hashids\Hashids;
use yii\base\Component;

class Hash extends Component
{

    public $salt;

    public $minHashLenght = 4;

    public $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    /**
     * @var Hashids
     */
    private $_hasheIds;

    public function init()
    {
        $this->_hasheIds = new Hashids($this->salt, $this->minHashLenght, $this->alphabet);
    }

    /**
     * @param $id array | integer
     * @return string
     */
    public function encode($id)
    {
        return $this->_hasheIds->encode($id);
    }


    /**
     * @param $hash string
     */
    public function decode($hash)
    {
        return $this->_hasheIds->decode($hash)[0];
    }
}