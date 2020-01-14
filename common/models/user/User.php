<?php

namespace common\models\user;

use common\models\address\State;
use common\models\auction\Bid;
use common\models\Config;
use common\models\daily\DailyAward;
use common\models\notification\Notification;
use common\models\order\Order;
use common\models\product\Product;
use common\models\product\ProductLike;
use common\models\tip\TipUsed;
use common\models\tip\UserTip;
use common\validators\RetirarEspeciais;
use DateTime;
use Faker\Factory;
use OAuth2\Storage\UserCredentialsInterface;
use Yii;
use yii\base\ErrorException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;
use yiibr\brvalidator\CpfValidator;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $name
 * @property string $android_token
 * @property string $auth_key
 * @property string $email_hash
 * @property string $facebook_id
 * @property string $document
 * @property string $phone
 * @property string $street
 * @property string $number
 * @property string $neighborhood
 * @property string $zip_code
 * @property string $complement
 * @property string $city
 * @property string $state_code
 * @property string $country
 * @property string $birthday
 * @property integer $inviter_id
 * @property integer $created_at
 * @property integer $updated_at
 *
 */
class User extends ActiveRecord implements IdentityInterface, UserCredentialsInterface
{

    const STATUS_USER_ACTIVE = 10;
    const STATUS_USER_BLOCK = 0;






    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            //required on update API
            ['document', RetirarEspeciais::class],
            [['birthday'], 'formatDateAPI'],
            //['document', CpfValidator::class],
            [['name', 'email', 'status'], 'required'],
            [['facebook_id', 'inviter_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['email', 'name', 'street', 'neighborhood', 'complement', 'country'], 'string', 'max' => 255],
            [['android_token'], 'string'],
            [['auth_key'], 'string', 'max' => 32],
            [['document'], 'string', 'max' => 11],
            [['birthday'], 'string', 'max' => 11],
            [['password_hash'], 'string', 'min' => 8, 'max' => 50, 'skipOnEmpty' => false],
            [['name'], 'string', 'min' => 3, 'max' => 64, 'skipOnEmpty' => false],
            [['password_hash'], 'hash'],
            [['number'], 'string', 'max' => 40],
            [['zip_code'], 'string', 'max' => 8],
            [['state_code'], 'string', 'max' => 2],
            [['phone'], 'string', 'max' => 14],
            [['phone'], 'unique', 'skipOnEmpty' => true],
            [['email'], 'unique'],
            [['email'], 'email'],
            [['document'], 'unique', 'skipOnEmpty' => true],
            [['facebook_id'], 'unique'],
        ];
    }


    public function formatDateAPI()
    {
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $this->birthday)) {
            $this->birthday = DateTime::createFromFormat('d/m/Y', $this->birthday)->format('Y-m-d');
        } else if (!preg_match('/^\d{4}-\d{1,2}-\d{1,2}$/', $this->birthday)) {
            $this->addError($this->birthday, "Formato de data inválido, usar o formato: 01/01/1990");
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'email' => Yii::t('app', 'Email'),
            'name' => Yii::t('app', 'Nome'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'facebook_id' => Yii::t('app', 'Facebook'),
            'document' => Yii::t('app', 'CPF'),
            'street' => Yii::t('app', 'Logradouro'),
            'number' => Yii::t('app', 'Número'),
            'city' => Yii::t('app', 'Cidade'),
            'state_code' => Yii::t('app', 'UF'),
            'neighborhood' => Yii::t('app', 'Bairro'),
            'zip_code' => Yii::t('app', 'CEP'),
            'complement' => Yii::t('app', 'Complemento'),
            'country' => Yii::t('app', 'País'),
            'phone' => Yii::t('app', 'Telefone'),
            'birthday' => Yii::t('app', 'Data de Nascimento'),
            'inviter_id' => Yii::t('app', 'Convidante'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Cradastrado em'),
            'updated_at' => Yii::t('app', 'Atualizado em'),
        ];
    }


    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }



    /**
     * @return array
     */
    public static function listStatus()
    {
        return [
            self::STATUS_USER_ACTIVE => Yii::t('app', 'Ativo'),
            self::STATUS_USER_BLOCK => Yii::t('app', 'Bloqueado'),
        ];
    }


    /**
     * @return string
     */
    public function statusMsg()
    {
        return self::listStatus()[$this->status];
    }


    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return User the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_USER_ACTIVE]);
    }

    /**
     * @param $facebookId
     * @return static
     */
    public static function findByFacebookId($facebookId)
    {
        return static::findOne(['facebook_id' => $facebookId]);
    }

    /**
     * @param $facebookId
     * @return static
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
        /** @var \filsh\yii2\oauth2server\Module $module */
        $module = Yii::$app->getModule('oauth2');
        $token = $module->getServer()->getResourceController()->getToken();
        return !empty($token['user_id'])
            ? static::findIdentity($token['user_id'])
            : null;
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Grant access tokens for basic user credentials.
     *
     * Check the supplied username and password for validity.
     *
     * You can also use the $client_id param to do any checks required based
     * on a client, if you need that.
     *
     * Required for OAuth2::GRANT_TYPE_USER_CREDENTIALS.
     *
     * @param $facebook_acces_token
     * Username to be check with.
     * @param $password
     * Password to be check with.
     *
     * @return
     * TRUE if the username and password are valid, and FALSE if it isn't.
     * Moreover, if the username and password are valid, and you want to
     *
     * @see http://tools.ietf.org/html/rfc6749#section-4.3
     *
     * @ingroup oauth2_section_4
     */
    public function checkUserCredentials($facebook_id, $password)
    {
        $email = explode("email:", $facebook_id);
        if (count($email) > 1) {
            $user = self::find()->andWhere(['email' => $email[1]])->one();
            return empty($user->password_hash) ? false : Yii::$app->getSecurity()->validatePassword($password, $user->password_hash);
        } else {
            $user = static::findByFacebookId($facebook_id);
        }
        if (empty($user)) {
            return false;
        }
        return true;
    }

    public function hash()
    {
        $this->password_hash = Yii::$app->getSecurity()->generatePasswordHash($this->password_hash);
    }

    /**
     * @return
     * ARRAY the associated "user_id" and optional "scope" values
     * This function MUST return FALSE if the requested user does not exist or is
     * invalid. "scope" is a space-separated list of restricted scopes.
     * @code
     * return array(
     *     "user_id"  => USER_ID,    // REQUIRED user_id to be stored with the authorization code or access token
     *     "scope"    => SCOPE       // OPTIONAL space-separated list of restricted scopes
     * );
     * @endcode
     */
    public function getUserDetails($facebook_id)
    {
        $email = explode("email:", $facebook_id);
        if (count($email) > 1) {
            $user_id = self::find()->andWhere(['email' => $email[1]])->one()->id;
        } else {
            $user_id = static::findByFacebookId($facebook_id)->id;
        }
        return [
            'user_id' => $user_id,
            'scope' => 'all',
        ];
    }


}
