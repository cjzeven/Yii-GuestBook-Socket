<?php

namespace app\models;

class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const SCENARIO_REGISTER = 'register';
    const SCENARIO_LOGIN = 'login';

    /**
     * inheritdoc
     *
     * @return string
     */
    public static function tableName()
    {
        return 'users';
    }

    /**
     * inheritdoc
     *
     * @return array
     */
    public function rules()
    {
        return [
            [
                ['username', 'password'],
                'required',
                'on' => [
                    self::SCENARIO_LOGIN,
                    self::SCENARIO_REGISTER
                ]
            ],
            ['username', 'unique', 'on' => self::SCENARIO_REGISTER]
        ];
    }


    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['accessToken' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username]);
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return \Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * Registrasi user baru ke database.
     *
     * @param  string $username
     * @param  string $password
     * @return bool
     */
    public static function register($username, $password)
    {
        $model = new User();
        $model->scenario = self::SCENARIO_REGISTER;
        $model->username = $username;
        $model->password = $password;
        $model->created_at = date('Y-m-d H:i:s');

        if ($model->validate() && $model->save()) {
            return true;
        }

        return false;
    }

    /**
     * inheritdoc
     *
     * @return bool
     */
    public function beforeSave()
    {
        /* Skenario untuk proses REGISTER */
        if ($this->scenario == self::SCENARIO_REGISTER) {
            $this->password = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }

        /* Skenario untuk proses LOGIN */
        if ($this->scenario == self::SCENARIO_LOGIN) {

        }

        return true;
    }
}
