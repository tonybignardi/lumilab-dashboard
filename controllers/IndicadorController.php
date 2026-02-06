<?php

namespace app\controllers;
use Yii;
use app\models\Indicador;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\controllers\LumilabController;

/**
 * IndicadorController implements the CRUD actions for Indicador model.
 */
class IndicadorController extends Controller
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
     * Lists all Indicador models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Indicador::find(),
            /*
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'indicadorId' => SORT_DESC,
                ]
            ],
            */
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Indicador model.
     * @param int $indicadorId Indicador ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($indicadorId)
    {
        return $this->render('view', [
            'model' => $this->findModel($indicadorId),
        ]);
    }

    /**
     * Creates a new Indicador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Indicador();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'indicadorId' => $model->indicadorId]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Indicador model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $indicadorId Indicador ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($indicadorId)
    {
        $model = $this->findModel($indicadorId);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'indicadorId' => $model->indicadorId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Indicador model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $indicadorId Indicador ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($indicadorId)
    {
        $this->findModel($indicadorId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Indicador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $indicadorId Indicador ID
     * @return Indicador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($indicadorId)
    {
        if (($model = Indicador::findOne(['indicadorId' => $indicadorId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
