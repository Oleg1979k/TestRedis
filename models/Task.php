<?php

namespace app\models;
use yii\redis\ActiveRecord;
use Yii;

class Task extends ActiveRecord
{
    public function attributes()
    {
        return ['id', 'order', 'title', 'checked'];
    }

    public function rules()
    {
        return [
            [['id', 'order'], 'integer'],
            [['title'], 'string', 'max' => 255],
            [['checked'], 'boolean'],
        ];
    }

    public static function keyPrefix()
    {
        return 'task';
    }
 const CACHE_KEY = 'tasks';
    public static function saveAll(array $tasks)
    {
       
        
        return Yii::$app->cache->set(self::CACHE_KEY, $tasks);
    }
   
    public static function findById($id): ?array
    {
        $tasks = Yii::$app->cache->get('tasks');
          foreach ($tasks as $item) {
        if (isset($item['id']) && $item['id'] == $id) {
            return $item;
        }
    }
    return null;
    }
   
}
