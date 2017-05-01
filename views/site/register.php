<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('main', 'Registration');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-register">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('main', 'Please fill out the following fields to register:') ?></p>

    <?php

        $template = "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>";

        $form = ActiveForm::begin([
            'id' => 'register-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => $template,
                'labelOptions' => ['class' => 'col-lg-2 control-label']
            ]
        ]); ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">

                <?= Html::submitButton(Yii::t('main', 'Register'), [
                        'class' => 'btn btn-primary',
                        'name' => 'login-button'
                    ]) ?>

            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
