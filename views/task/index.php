<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\JsExpression;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ArrayDataProvider */

$this->title = 'Список заданий';
?>

<h1><?= Html::encode($this->title) ?></h1>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        'id',
        'title',
        'description',
        [
            'attribute' => 'due_date',
            'format' => ['date', 'php:d.m.Y'],
        ],
        [
            'attribute' => 'checked',
            'format' => 'raw',
            'value' => function($model) {
                return Html::checkbox('checked', $model['checked'], [
                    'class' => 'task-checkbox',
                    'data-id' => $model['id'],
                ]);
            },
        ],
    ],
]); ?>

<?php
$js = <<<JS
// Массив состояния задач
var tasksState = {};
document.querySelectorAll('.task-checkbox').forEach(function(checkbox){
    var id = checkbox.dataset.id;
    tasksState[id] = checkbox.checked;

    checkbox.addEventListener('change', function(){
        tasksState[id] = this.checked;
        console.log('Task ID ' + id + ' checked: ' + this.checked);
        // Здесь можно добавить сохранение через AJAX при необходимости
    });
});
JS;

$this->registerJs(new JsExpression($js));
?>
