<?php

namespace common\controllers;

use Yii;
use common\models\Orders;
use common\models\OrdersSearch;
use common\models\OrderForm;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller implements ViewContextInterface
{
    /**
     * @inheritdoc
     */

    /*public function getViewPath()
    {
     return Yii::getAlias('@frontend/views/orders/');
 }*/
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
     * Lists all Orders models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new Orders();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Creates a new Orders model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionOrderForm() {
        $form = new OrderForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            $fname = Html::encode($form->fname);
            $lname = Html::encode($form->lname);
            $email = Html::encode($form->email);
            $phone = Html::encode($form->phone);
            $address = Html::encode($form->address);
        }
        else {
            $fname = '';
            $lname = '';
            $email = '';
            $phone = '';
            $address = '';
        }
        return $this->render('form',[
        'form' => $form,
        'lname' => $lname,
        'fname' => $fname,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        ]);
    }
}
