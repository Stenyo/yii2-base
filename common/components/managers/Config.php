<?php

namespace common\components\managers;

use common\models\website\Setting;
use InvalidArgumentException;
use yii\base\Component;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Class Config
 *
 * @property string $version
 *
 * @property string $support_url
 * @property string $support_email
 *
 * @property bool $maintenance
 * @property string $maintenance_message
 *
 * @property string $seo_tagline
 * @property string $seo_description
 * @property string $seo_keywords
 *
 * @property string $google_analytics
 * @property string $google_analytics_id
 *
 * @property string $facebook
 * @property string $facebook_app_id
 * @property string $facebook_app_secret
 * @property string $facebook_user_registration
 *
 * @property string $monetization_remove_uncomplete_order
 * @property string $monetization_invoice_logo
 * @property string $monetization_invoice_notes
 *
 * @property bool $paypal
 * @property string $paypal_email
 * @property string $paypal_username
 * @property string $paypal_password
 * @property string $paypal_signature
 * @property string $paypal_app_id
 * @property bool $paypal_live
 * @property int $paypal_max_failded_payments
 *
 * @property string $facebook_page
 * @property string $youtube_page
 * @property string $twitter_page
 * @property string $blog_url
 *
 * @property string $vkmail
 * @property string $vkmail_public
 * @property string $vkmail_private
 * @property string $vkmail_newsletter_list
 *
 * @property string $boleto
 *
 * @property bool $user_allow_registration
 */
class Config extends Component
{

    /**
     * @var string Model class
     */
    public $modelClass;

    /**
     * @var bool
     */
    public $cache;

    /**
     * @var string
     */
    public $key = 'key';

    /**
     * @var string[]
     */
    private $settings = [];

    /**
     * @throws InvalidConfigException
     */
    public function init()
    {
        if ($this->modelClass === null) {
            throw new InvalidConfigException("Model class not defined");
        }
    }

    /**
     * @param string $name
     * @return string
     */
    public function __get($name)
    {
        if ($this->cache && array_key_exists($name, $this->settings)) {
            return $this->settings[$name];
        } else if (($model = $this->findModel($name)) !== null) {
            if ($this->cache) {
                $this->settings[$name] = $model->value;
            }

            return $model->value;
        } else {
            throw new InvalidArgumentException("{$name} key not found in settings");
        }

    }

    /**
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (($model = $this->findModel($name)) !== null) {
            $model->value = $value;

            if ($model->save(['value'])) {
                if ($this->cache) {
                    $this->settings[$name] = $value;
                }
            } else {
                throw new InvalidArgumentException("you can't save {$name}");

            }
        } else {
            throw new InvalidArgumentException("{$name} key not found in settings");
        }
    }

    /**
     * @param string $name
     * @return bool
     */
    public function __isset($name)
    {
        return $this->findModel($name) !== null;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isTrue($name)
    {
        return $this->$name == true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isFalse($name)
    {
        return $this->$name == true;
    }

    /**
     * @param $name
     * @return bool
     */
    public function isEmpty($name)
    {
        return empty($this->$name);
    }

    /**
     * Finds the Lesson model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Model the loaded model
     * @throws InvalidArgumentException if the model cannot be found
     */
    protected function findModel($name)
    {
        /* @var $modelClass ActiveRecord */
        $modelClass = $this->modelClass;
        return $modelClass::findOne([$this->key => $name]);
    }
}