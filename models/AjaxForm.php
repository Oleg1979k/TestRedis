<?php
namespace app\models;

use yii\base\Model;

class AjaxForm extends Model
{
    public $url;

    public function rules()
    {
        return [
            ['url', 'required'],
            ['url', 'string', 'max' => 50],
        ];
    }
}
