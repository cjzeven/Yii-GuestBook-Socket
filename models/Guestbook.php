<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "guestbook".
 *
 * @property integer $id
 * @property string $name
 * @property string $email
 * @property integer $read
 * @property string $created_at
 */
class Guestbook extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'guestbook';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'email'], 'required'],
            [['read'], 'integer'],
            [['created_at', 'read'], 'safe'],
            [['name', 'email'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'read' => 'Read',
            'created_at' => 'Created At',
        ];
    }

    public function beforeSave()
    {
        $this->read = $this->read === 1 ? $this->read : 0;
        $this->created_at = $this->created_at ? $this->created_at : date('Y-m-d H:i:s');
        return true;
    }
}
