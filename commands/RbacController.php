<?php

namespace app\commands;

use Yii;
use yii\console\Controller;


class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;

        /* Buat role admin dan author */
        $admin = $auth->createRole('admin');
        $author = $auth->createRole('author');

        /* Buat permission untuk aksi createPost */
        $createPost = $auth->createPermission('createPost');
        $createPost->description = 'Create a new post';

        /* Buat permission untuk aksi updatePost */
        $updatePost = $auth->createPermission('updatePost');
        $updatePost->description = 'Update a post';

        /* Masukkan semua object ke dalam auth */
        $auth->add($createPost);
        $auth->add($updatePost);
        $auth->add($admin);
        $auth->add($author);

        /* Tambah author sebagai parent dari create post */
        $auth->addChild($author, $createPost);
        /* Tambah admin sebagai parent dari create post */
        $auth->addChild($admin, $updatePost);
        /* Tambah admin sebagai parent dari author */
        $auth->addChild($admin, $author);

        /* Tambahakn rule */
        $rule = new \app\rbac\AuthorRule;
        $auth->add($rule);

        $updateOwnPost = $auth->createPermission('updateOwnPost');
        $updateOwnPost->description = 'Update own post';
        $auth->add($updateOwnPost);

        $auth->addChild($updateOwnPost, $updatePost);
        $auth->addChild($author, $updateOwnPost);

        /* Berikan role ke admin berdasarkan id (id dari user) */
        $auth->assign($author, 2);
        $auth->assign($admin, 1);

    }
}
