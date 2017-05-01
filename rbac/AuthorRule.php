<?php

namespace app\rbac;

use yii\rbac\Rule;


class AuthorRule extends Rule
{
    public $name = 'isAuthor';

    public function execute($user, $item, $params)
    {
        /* Cek apakah post milik user tersebut */
        return isset($params['post']) ? $params['post']->createdBy == $user : false;
    }
}
