<?php

namespace app\controllers;

use app\models\Task;
use Yii;
use yii\web\Controller;
use yii\data\ArrayDataProvider;

class TaskController extends Controller
{
    public function actionIndex()
    {
        
        // Получаем данные из кэша
    $tasks = Yii::$app->cache->get('tasks');
    // Проверяем, если данных нет в кэше
    if ($tasks === false) {
        Yii::info('No tasks found in cache.');  // Логируем отсутствие данных в кэше
    }


    

        $dataProvider = new ArrayDataProvider([
            'allModels' => $tasks,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionSave()
    {
        $tasks = [
            ['id' => 1, 'title' => 'Задание 1', 'description' => 'Приготовить обед', 'due_date' => '2025-09-20', 'checked' => true],
            ['id' => 2, 'title' => 'Задание 2', 'description' => 'Сходить за хлебом', 'due_date' => '2025-09-25', 'checked' => false],
            ['id' => 3, 'title' => 'Задание 3', 'description' => 'Отремонтировать окно', 'due_date' => '2025-09-30', 'checked' => true],
        ];
        if(Task::saveAll($tasks)) return 'Данные записаны';

        return 'Ошибка записи данных';
    }
    public function actionSearch($id)
    {
        $task = Task::findById($id);

         $dataProvider = new ArrayDataProvider([
            'allModels' => [$task],
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
       
    }
}
