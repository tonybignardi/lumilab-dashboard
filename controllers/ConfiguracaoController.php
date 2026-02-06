<?php

namespace app\controllers;
use Yii;
use app\models\Configuracao;
use app\models\ConfiguracaoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\controllers\LumilabController;

/**
 * ConfiguracaoController implements the CRUD actions for Configuracao model.
 */
class ConfiguracaoController extends Controller
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
     * Lists all Configuracao models.
     *
     * @return string
     */
    public function actionIndex()
    {
        
        $searchModel = new ConfiguracaoSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Configuracao model.
     * @param int $configuracaoId Configuracao ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($configuracaoId)
    {
        return $this->render('view', [
            'model' => $this->findModel($configuracaoId),
        ]);
    }

    /**
     * Creates a new Configuracao model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Configuracao();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'configuracaoId' => $model->configuracaoId]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Configuracao model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $configuracaoId Configuracao ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($configuracaoId)
    {
        $model = $this->findModel($configuracaoId);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'configuracaoId' => $model->configuracaoId]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Configuracao model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $configuracaoId Configuracao ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($configuracaoId)
    {
        $this->findModel($configuracaoId)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Configuracao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $configuracaoId Configuracao ID
     * @return Configuracao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($configuracaoId)
    {
        if (($model = Configuracao::findOne(['configuracaoId' => $configuracaoId])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
