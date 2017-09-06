<?php
namespace common\controllers;

use Yii;
use common\models\Production;
use common\models\ProductionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\UploadedFile;
/**
* ProductionController implements the CRUD actions for Production model.
*/
class ProductionController extends Controller
{
    /**
    * @inheritdoc
    */
    public function behaviors()
    {
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                    'matchCallback' => function ($rule, $action) {
                        $action = Yii::$app->controller->action->id;
                        $controller = Yii::$app->controller->id;
                        $route = "$controller/$action";
                        $post = Yii::$app->request->post();
                        if (\Yii::$app->user->can($route)) {
                            return true;
                        }
                    }
                ],
            ],
        ];
        return $behaviors;
    }

    /**
    * Lists all Production models.
    * @return mixed
    */
    public function actionIndex()
    {
        $searchModel = new ProductionSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
    * Displays a single Production model.
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
    * Creates a new Production model.
    * If creation is successful, the browser will be redirected to the 'view' page.
    * @return mixed
    */
    public function actionCreate()
    {
        $model = new Production();
        if ($model->load(Yii::$app->request->post())) {
            $img = UploadedFile::getInstance($model,'photo');
            $date = date("JmY_His", time());
            $uid = \Yii::$app->user->getId();
            $imgname = 'prod_' . $uid .'_'.$date. '.'. $img->extension;
            $model->photo = $imgname;
            if ($model->save()) {
                $img->saveAs('uploads/prod/'.$imgname);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->createdBy = \Yii::$app->user->getId()  ;
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
    * Updates an existing Production model.
    * If update is successful, the browser will be redirected to the 'view' page.
    * @param integer $id
    * @return mixed
    */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $img = UploadedFile::getInstance($model,'photo');
            $date = date("JmY_His", time());
            $uid = \Yii::$app->user->getId();
            $imgname = 'prod_' . $uid .'_'.$date. '.'. $img->extension;
            $model->photo = $imgname;
            if ($model->save()){
                $img->saveAs('uploads/prod/'.$imgname);
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
    * Deletes an existing Production model.
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
    * Finds the Production model based on its primary key value.
    * If the model is not found, a 404 HTTP exception will be thrown.
    * @param integer $id
    * @return Production the loaded model
    * @throws NotFoundHttpException if the model cannot be found
    */

    public function actionDelphoto($id){
        $photo = Production::find()->where(['id'=>$id])->one()->photo;
        if ($photo) {
            if (!unlink($photo)) {
                return false;
            }
        }
        $prod = Production::findOne($id);
        $prod->photo = NULL;
        $prod->update();
        return $this->redirect(['update', 'id' => $id]);
    }

    protected function findModel($id)
    {
        if (($model = Production::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function findUserModel($id)
    {
        if (($model = Production::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function getAllProds()
    {
        $arr = Production::find()->all();
        return $arr;
    }

    public function actionCreateOrder()
    {
        $postData = Yii::$app->request->post();
        return json_encode([
            'success' => Yii::$app->cart->createOrder()
        ]);
    }

    public function actionAddInCart()
    {
        $postData = Yii::$app->request->post();
        return json_encode([
            'success' => Yii::$app->cart->add($postData['product_id'], $postData['price'], $postData['count']),
            'cartStatus' => Yii::$app->cart->status
        ]);
    }

    public function actionSetCount()
    {
        $postData = Yii::$app->request->post();
        return json_encode([
            'success' => Yii::$app->cart->setCount($postData['product_id'], $postData['count']),
            'cartStatus' => Yii::$app->cart->status
        ]);
    }

    public function actionDeleteFromCart()
    {
        $postData = Yii::$app->request->post();
        return json_encode([
            'success' => Yii::$app->cart->delete($postData['product_id']),
            'cartStatus' => Yii::$app->cart->status
        ]);
    }

    public function actionStatus() {
        $status = Yii::$app->cart->status;
        return $status;
    }
}
