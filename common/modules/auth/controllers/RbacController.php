<?php

namespace common\modules\auth\controllers;

use Yii;
use common\modules\auth\models\AuthItem;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * RbacController implements the CRUD actions for AuthItem model.
 */
class RbacController extends Controller
{
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
     * Lists all AuthItem models.
     * @return mixed
     */

     //Create Rule
    public function actionCreate_rule(){
      $auth = Yii::$app->authManager;

      // add the rule
      $rule = new \common\modules\auth\rbac\AuthorRule;
      $auth->add($rule);

      // add the "updateOwnPost" permission and associate the rule with it.
      $updateOwnPost = $auth->createPermission('updateOwnPost');
      $updateOwnPost->description = 'Update own post';
      $updateOwnPost->ruleName = $rule->name;
      $auth->add($updateOwnPost);

      $updatePost = $auth->createPermission('auth/production/update');
      // "updateOwnPost" will be used from "updatePost"
      $auth->addChild($updateOwnPost, $updatePost);
      $author = $auth->createPermission('author');
      // allow "author" to update their own posts
      $auth->addChild($author, $updateOwnPost);
    }
    /* create Permisson and Roles */

    public function actionCreate_pr()
    {
       //Load authManager
       $auth = Yii::$app->authManager;

       //Permissions
       $indexProd = $auth->createPermission('production/index');
       $indexProd->description = 'Товар';
       $auth->add($indexProd);

       $updateProd = $auth->createPermission('production/update');
       $updateProd->description = 'Обновить товар';
       $auth->add($updateProd);

       $deleteProd = $auth->createPermission('production/delete');
       $deleteProd->description = 'Удалить товар';
       $auth->add($deleteProd);

       $createProd = $auth->createPermission('production/create');
       $createProd->description = 'Добавить товар';
       $auth->add($createProd);

       $viewProd = $auth->createPermission('production/view');
       $viewProd->description = 'Просмотр товара';
       $auth->add($viewProd);

       //Roles
       $user = $auth->createRole('user');
       $auth->add($user);
       $auth->addChild($user, $indexProd);

       $author = $auth->createRole('author');
       $auth->add($author);
       $auth->addChild($author, $createProd);
       $auth->addChild($author, $viewProd);
       $auth->addChild($author, $indexProd);

       $admin = $auth->createRole('admin');
       $auth->add($admin);
       $auth->addChild($admin, $updateProd);
       $auth->addChild($admin, $deleteProd);
       $auth->addChild($admin, $createProd);
       $auth->addChild($admin, $indexProd);
       $auth->addChild($admin, $viewProd);

       //Assign roles to users
       $auth->assign($user, 3);
       $auth->assign($author, 2);
       $auth->assign($admin, 1);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => AuthItem::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new AuthItem model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new AuthItem();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing AuthItem model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param string $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->name]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing AuthItem model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param string $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return AuthItem the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = AuthItem::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
