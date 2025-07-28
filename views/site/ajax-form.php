<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;


/* @var $model app\models\AjaxForm */
?>

<h3>Введите  URL сайта </h3>
<?php $form = ActiveForm::begin([
    'id' => 'ajax-form',
    'action' => Url::to(['site/ajax-form-submit']),
    'enableAjaxValidation' => false,
]); ?>

<?= $form->field($model, 'url')->textInput(['placeholder' => 'Введите полный адрес сайта https://...']) ?>

<?= Html::submitButton('Ok', ['class' => 'btn btn-primary']) ?>

<?php ActiveForm::end(); ?>

<div id="qr-container">
    <img id="qr-img" src="" style="display:none; max-width:250px;">
</div>
<div id="ajax-result"></div>
<?php
$js = <<<JS
$('#ajax-form').on('beforeSubmit', function(e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            if (res.success) {
                  $('#ajax-result').text('');
               // $('#ajax-result').text(res.message).css('color', 'green');
                $('#qr-img').attr('src', res.dataUrl).fadeIn();
            } else {
                $('#ajax-result').text('Ошибка: ' + JSON.stringify(res.errors)).css('color', 'red');
            }
        },
        error: function(xhr) {
            $('#ajax-result').text('Ошибка сервера: ' + xhr.status).css('color', 'red');
        }
    });
    return false;
});
JS;
$this->registerJs($js);
?>
