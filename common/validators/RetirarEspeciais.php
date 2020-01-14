<?php

namespace common\validators;

use yii2mod\helpers\StringHelper;
use yii\validators\FilterValidator;

class RetirarEspeciais extends FilterValidator {
    
    public function init(){
        $this->filter = function($value) {
            return StringHelper::removePunctuationSymbols($value);
        };
        
        parent::init();
    }


}