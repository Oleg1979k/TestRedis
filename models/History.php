<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Модель для таблицы "history".
 *
 * @property int $id
 * @property string $name
 * @property string $ip
 * @property string $created_at
 */
class History extends ActiveRecord
{
    /**
     * Название таблицы
     */
    public static function tableName()
    {
        return '{{%history}}';
    }

    /**
     * Правила валидации
     */
    public function rules()
    {
        return [
            [['name', 'ip'], 'required'],
            [['name', 'ip'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * Подписи к полям
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Имя',
            'ip' => 'IP адрес',
            'created_at' => 'Создано',
        ];
    }

    /**
     * Перед сохранением автоматически проставляем IP
     */
    public function beforeValidate()
    {
        if (parent::beforeValidate()) {
            if (empty($this->ip)) {
                $this->ip = Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }
}
