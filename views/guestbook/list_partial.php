<?php

use yii\grid\GridView;
use yii\helpers\Html;

?>

<div class="guestbook-content">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'layout' => '{items} {summary} {pager}',
        'rowOptions' => function($model) {
            if (!$model->read) return ['class' => 'danger'];
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'email:email',
            'created_at',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '<center>Mark as Read</center>',
                'template' => '{view}',
                'visibleButtons' => [
                    'view' => function($model) {
                        return !$model->read;
                    }
                ],
                'buttons' => [
                    'view' => function($url, $model) {
                        $span = '<span class="glyphicon glyphicon-ok"></span>';
                        return Html::a($span, "/guestbook/check-as-read?id={$model->id}", [
                            'class' => 'view_message',
                            'value' => $model->id,
                            'title' => 'Mark as read'
                        ]);
                    }
                ]
            ],
        ],
    ]); ?>
</div>