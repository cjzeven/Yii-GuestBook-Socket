<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = Yii::t('main', 'Login User');
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="site-login">

    <?php if (Yii::$app->session->hasFlash('registerSuccess')): ?>

        <div class="alert alert-success" role="alert">
            <?= Yii::$app->session->getFlash('registerSuccess') ?>
        </div>

    <?php endif; ?>

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Yii::t('main', 'Please fill out the following fields to login:') ?></p>

    <?php

        $template = "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-7\">{error}</div>";

        $form = ActiveForm::begin([
            'id' => 'login-form',
            'layout' => 'horizontal',
            'fieldConfig' => [
                'template' => $template,
                'labelOptions' => ['class' => 'col-lg-2 control-label'],
            ]
        ]) ?>

        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password')->passwordInput() ?>

        <?php

            $template  = "<div class=\"col-lg-offset-2 col-lg-3\">{input} {label}</div>\n";
            $template .= "<div class=\"col-lg-8\">{error}</div>";

            echo $form->field($model, 'rememberMe')->checkbox([
                'template' => $template,
            ]) ?>

        <div class="form-group">
            <div class="col-lg-offset-2 col-lg-10">
                <?= Html::submitButton(Yii::t('main', 'Login'), ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
