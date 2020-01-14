<?php

namespace console\migrations\base;

use yii\db\Migration as MigrationBase;

class Migration extends MigrationBase
{

    public function getDefaultTableOptions()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        return $tableOptions;
    }

    /**
     * @param $values string[] with values
     * @param bool $notNull if or not "not null"
     * @return string
     */

    public function enum($values, $notNull = false)
    {
        $values = implode($values, "', '");
        return "ENUM ('$values')" . ($notNull ? ' NOT NULL' : '');
    }

}