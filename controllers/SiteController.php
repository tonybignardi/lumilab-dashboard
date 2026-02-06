<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\controllers\LumilabController;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => @AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => @VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *//*
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
*/
    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
      
        $minhasConfig=LumilabController::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        $datas = LumilabController::pegaDatas();
        
        $dtInicio = $datas[0];
        $dtFim = $datas[1];
        $debug=false;

        $db = \Yii::$app->db;       
        
        $intervalo = "ultimo_ano";
        if(@$_GET["intervalo"])
        $intervalo=@$_GET["intervalo"];
        else
        $_GET["intervalo"]=$intervalo;
        $s_paginas = "SELECT * from pagina where paginaTipo='novo' and paginaFiltro='$intervalo'";
        
        
        $c_paginas = $db->createCommand($s_paginas);

        
        $rs_paginas=$c_paginas->queryAll();
        $indicador_posicao=[];
        foreach($rs_paginas as $idid => $rpagina)
        {
            $indicador_posicao[$rpagina["paginaPosicao"]]=$rpagina["paginaHTML"];
        }
        
              
        return $this->render('../lab/novo', [
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        'indicador_posicao'=>$indicador_posicao,
        
    ]);   
        
        
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        #echo '<i style="display:none">logando</i>';
        if (!@Yii::$app->user->isGuest) {
            //return $this->goHome();
            return @$this->redirect(['lab/novo','intervalo'=>'tudo']);
        }
        $model = new LoginForm();
        if (@$model->load(@Yii::$app->request->post()) && @$model->login()) {
            //return $this->goBack();
            return @$this->redirect(['lab/avancado','intervalo'=>'tudo']);
        }
        @$model->password = '';
        return @$this->render('login', [
            'model' => $model,
        ]);
        exit();
        
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {   
        #echo '<i style="display:none">saindo</i>';
        @Yii::$app->user->logout();
        return $this->redirect(['lab/novo','intervalo'=>'tudo']);
        return $this->refresh();
        exit();

        
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    
    public function actionAjuda()
    {
        return $this->render('ajuda');
    }
    public function actionEquipe()
    {
        return $this->render('equipe');
    }
    public function actionTermos()
    {
        return $this->render('termos');
    }

    public function actionPolitica()
    {
        return $this->render('politica');
    }
}
