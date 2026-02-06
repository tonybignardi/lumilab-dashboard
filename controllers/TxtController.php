<?php

namespace app\controllers;
use Yii;
use app\models\Aluno;
use app\models\AlunoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\controllers\LumilabController;

/**
 * AlunoController implements the CRUD actions for Aluno model.
 */
class TxtController extends Controller
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
     * Lists all Aluno models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $filename = 'relatorio_lumilab.csv';
        $db = \Yii::$app->db;
        $sql = "select * from relatorio where chave='".@$_GET["chave"]."' order by relatorioId desc limit 0,1";
        $c_sql = $db->createCommand($sql);
        $rs=$c_sql->queryAll();

        $content=@$rs[0]["txt"];

        header('Content-Disposition: attachment; charset=UTF-8; filename="'.$filename.'"');
        $utf8_content = mb_convert_encoding($content, "SJIS", "UTF-8");
        echo $utf8_content;
        exit();
        
        
        
       
    }

}
