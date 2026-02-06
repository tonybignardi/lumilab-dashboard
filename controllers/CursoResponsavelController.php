<?php

namespace app\controllers;

use app\models\CursoResponsavel;
use app\models\CursoResponsavelSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CursoResponsavelController implements the CRUD actions for CursoResponsavel model.
 */
class CursoResponsavelController extends Controller
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
     * Lists all CursoResponsavel models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CursoResponsavelSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CursoResponsavel model.
     * @param int $cursoResponsavelId Curso Responsavel ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($cursoResponsavelId)
    {
        return $this->render('view', [
            'model' => $this->findModel($cursoResponsavelId),
        ]);
    }

    /**
     * Creates a new CursoResponsavel model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new CursoResponsavel();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'cursoResponsavelId' => $model->cursoResponsavelId]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CursoResponsavel model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $cursoResponsavelId Curso Responsavel ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($cursoResponsavelId)
    {
        $model = $this->findModel($cursoResponsavelId);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'cursoResponsavelId' => $model->cursoResponsavelId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CursoResponsavel model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $cursoResponsavelId Curso Responsavel ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($cursoResponsavelId)
    {
        $this->findModel($cursoResponsavelId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CursoResponsavel model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $cursoResponsavelId Curso Responsavel ID
     * @return CursoResponsavel the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($cursoResponsavelId)
    {
        if (($model = CursoResponsavel::findOne(['cursoResponsavelId' => $cursoResponsavelId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
