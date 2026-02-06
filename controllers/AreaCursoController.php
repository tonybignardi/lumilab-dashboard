<?php

namespace app\controllers;
use Yii;
use app\models\AreaCurso;
use app\models\AreaCursoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\controllers\LumilabController;

/**
 * AreaCursoController implements the CRUD actions for AreaCurso model.
 */
class AreaCursoController extends Controller
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
     * Lists all AreaCurso models.
     *
     * @return string
     */
    public function actionIndex()
    {
        
        $searchModel = new AreaCursoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single AreaCurso model.
     * @param int $areaCursoId Area Curso ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($areaCursoId)
    {
        return $this->render('view', [
            'model' => $this->findModel($areaCursoId),
        ]);
    }

    /**
     * Creates a new AreaCurso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new AreaCurso();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'areaCursoId' => $model->areaCursoId]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing AreaCurso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $areaCursoId Area Curso ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($areaCursoId)
    {
        $model = $this->findModel($areaCursoId);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'areaCursoId' => $model->areaCursoId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing AreaCurso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $areaCursoId Area Curso ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($areaCursoId)
    {
        $this->findModel($areaCursoId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the AreaCurso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $areaCursoId Area Curso ID
     * @return AreaCurso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($areaCursoId)
    {
        if (($model = AreaCurso::findOne(['areaCursoId' => $areaCursoId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
