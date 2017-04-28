<?php

namespace app\controllers;

use Yii;
use app\models\Guestbook;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use ElephantIO\Client as Socket;
use ElephantIO\Engine\SocketIO\Version1X;

/**
 * GuestbookController implements the CRUD actions for Guestbook model.
 */
class GuestbookController extends Controller
{
    protected $socket;

    /**
     * Inisialisasi awal sebelum method dieksekusi.
     * @return void
     */
    public function init()
    {
        $this->socket = new Socket(new Version1X('127.0.0.1:3000'));
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Guestbook models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Guestbook::find()->orderBy(['read' => SORT_ASC, 'created_at' => SORT_DESC]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Guestbook model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Guestbook model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Guestbook();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Emit event "create" ke server
            $this->socket->initialize();
            $this->socket->emit('create_guestbook', []);
            $this->socket->close();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Guestbook model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            $this->socket->initialize();
            $this->socket->emit('update_guestbook', []);
            $this->socket->close();

            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Guestbook model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        $this->socket->initialize();
        $this->socket->emit('delete_guestbook', []);
        $this->socket->close();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Guestbook model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Guestbook the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Guestbook::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Menampilkan data guestbook.
     * @return mixed Render view dan variabel yang didefinisikan.
     */
    public function actionList()
    {
        $readMessages = Guestbook::find()->where(['read' => 1])->count();
        $unreadMessages = Guestbook::find()->where(['read' => 0])->count();

        $dataProvider = new ActiveDataProvider([
            'query' => Guestbook::find()->orderBy(['read' => SORT_ASC, 'created_at' => SORT_DESC]),
            'pagination' => [
                'pageSize' => 5
            ]
        ]);

        return $this->render('list', get_defined_vars());
    }

    /**
     * Menghitung data yang berstatus belum dibaca.
     * @return integer Jumlah data yang berstatus belum dibaca.
     */
    public function actionUnreadCounter()
    {
        return Guestbook::find()->where(['read' => 0])->count();
    }

    /**
     * Menghitung data yang berstatus telah dibaca.
     * @return integer Jumlah data yang berstatus telah dibaca.
     */
    public function actionReadCounter()
    {
        return Guestbook::find()->where(['read' => 1])->count();
    }

    /**
     * Mengubah status "read" di database menjadi 1 yang artinya
     * data tersebut sudah dibaca.
     * @param  integer $id
     * @return integer
     */
    public function actionCheckAsRead($id = null)
    {
        if (!$id) { return 0; }

        $model = $this->findModel($id);
        $model->read = 1;

        if($model->validate() && $model->save()) {
            $this->socket->initialize();
            $this->socket->emit('check_as_read', []);
            $this->socket->close();
            return 1;
        }

        return 0;
    }
}
