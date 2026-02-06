<?php

namespace app\controllers;

use app\models\CursoCarga;
use app\models\CursoCargaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\controllers\LumilabController;
use app\models\LoginForm;

/**
 * CursoCargaController implements the CRUD actions for CursoCarga model.
 */
class CursoCargaController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ],
            @\Yii::$app->user->identity->username!=LumilabController::buscaConfig(array("email_admin"))["email_admin"] ?
            [
                'access' => [
                    'class' => \yii\filters\AccessControl::class,
                    'rules' => [
                        [
                            'allow' => false,
                            'roles' => ['@'],
                        ],
                    ],
                ],]:[]
        );
    }

    /**
     * Lists all CursoCarga models.
     *
     * @return string
     */
    public function actionIndex()
    {
        
        $searchModel = new CursoCargaSearch();
        
        $dataProvider = $searchModel->search($this->request->queryParams);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CursoCarga model.
     * @param int $cursoCargaId Curso Carga ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cursoCargaId)
    {
        return $this->render('view', [
            'model' => $this->findModel($cursoCargaId),
        ]);
    }

    /**
     * Creates a new CursoCarga model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CursoCarga();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cursoCargaId' => $model->cursoCargaId]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CursoCarga model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cursoCargaId Curso Carga ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cursoCargaId)
    {
        $model = $this->findModel($cursoCargaId);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cursoCargaId' => $model->cursoCargaId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CursoCarga model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cursoCargaId Curso Carga ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cursoCargaId)
    {
        $this->findModel($cursoCargaId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CursoCarga model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cursoCargaId Curso Carga ID
     * @return CursoCarga the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cursoCargaId)
    {
        if (($model = CursoCarga::findOne(['cursoCargaId' => $cursoCargaId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
