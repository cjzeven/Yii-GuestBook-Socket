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
                'header' => 'Mark as Read',
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function($url, $model) {
                        if (!$model->read) {
                            $span = '<span class="glyphicon glyphicon-ok"></span>';
                            return Html::a($span, "/guestbook/check-as-read?id={$model->id}", [
                                'class' => 'view_message',
                                'value' => $model->id,
                                'title' => 'Mark as read'
                            ]);
                        }
                    }
                ]
            ],
        ],
    ]); ?>
</div>