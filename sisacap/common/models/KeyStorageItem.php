<?php

namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "key_storage_item".
 *
 * @property integer $key
 * @property integer $value
 */
class KeyStorageItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%key_storage_item}}';
    }

    public function behaviors()
    {
        return [
            [
              'class' => TimestampBehavior::className(),
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['key'], 'string', 'max'=>128],
            [['value', 'comment'], 'safe'],
            [['key'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'key' => Yii::t('common', 'Nombre de variable'),
            'value' => Yii::t('common', 'Valor'),
            'comment' => Yii::t('common', 'Descripción'),
        ];
    }
}
