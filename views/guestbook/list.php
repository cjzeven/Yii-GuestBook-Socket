<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Guestbook List';
$this->params['breadcrumbs'][] = ['label' => 'Guestbooks', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="guestbook-list">

    <button class="btn btn-success" type="button">
        Read <span class="badge read-counter"><?= $readMessages ? $readMessages : 0 ?></span>
    </button>

    <button class="btn btn-danger" type="button">
        Unread <span class="badge unread-counter"><?= $unreadMessages ? $unreadMessages : 0 ?></span>
    </button>

    <h1><?= Html::encode($this->title) ?></h1>

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
        ]) ?>

    </div>
</div>

<?php

$this->registerCss('
    .summary {
        display: inline-block;
    }
    .pagination {
        margin: 0px;
        float: right !important;
    }
    .guestbook-content table > tbody > tr > td:last-of-type {
        text-align: center;
    }
');
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/socket.io/1.7.3/socket.io.min.js');
$this->registerJs('
    const socket = io("127.0.0.1:3000")
    const url = window.location.origin

    function replaceListTable() {
        $.ajax({
            url: url + "/guestbook/list-partial",
            method: "get",
            success: function(data) {
                $(".guestbook-content").replaceWith(data)
            }
        })
    }

    function replaceUnreadCounter() {
        $.ajax({
            url: url + "/guestbook/unread-counter",
            method: "get",
            success: function(data) {
                $(".unread-counter").text(data)
            }
        })
    }

    function replaceReadCounter() {
        $.ajax({
            url: url + "/guestbook/read-counter",
            method: "get",
            success: function(data) {
                $(".read-counter").text(data)
            }
        })
    }

    function checkAsRead(id, url) {
        $.ajax({
            url: url,
            method: "get",
            success: function(data) {}
        })
    }

    socket.on("replace_guestbook", function(data) {
        replaceListTable()
    })

    socket.on("replace_unread_counter", function() {
        replaceUnreadCounter()
    })

    socket.on("replace_read_counter", function() {
        replaceReadCounter()
    })

    $(".guestbook-list").on("click", ".view_message", function(e) {
        e.preventDefault()
        checkAsRead($(this).attr("value"), $(this).attr("href"))
    })
');

?>
