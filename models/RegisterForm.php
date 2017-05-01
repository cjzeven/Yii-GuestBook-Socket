<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\models\User;


class RegisterForm extends Model
{
    public $username;
    public $password;

    /**
     * inheritdoc
     *
     * @return array
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'string', 'length' => [4, 15]],
            ['password', 'string', 'length' => [6, 20]],
            ['username', 'trim']
        ];
    }

    /**
     * inheritdoc
     *
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('main', 'Username'),
            'password' => Yii::t('main', 'Password')
        ];
    }

    /**
     * Parsing data register ke model User.
     *
     * @return bool
     */
    public function register()
    {
        return User::register($this->username, $this->password);
    }

}
