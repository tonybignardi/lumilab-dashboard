<?php
namespace app\controllers;
use yii\db\Connection;
use app\models\LoginForm;
use Phpml\Regression\LeastSquares;
use Phpml\Classification\Linear\LogisticRegression;
use Phpml\Math\Statistic\StandardDeviation;

use MCordingley\Regression\Algorithm\GradientDescent\Batch;
use MCordingley\Regression\Algorithm\GradientDescent\Schedule\Adam;
use MCordingley\Regression\Algorithm\GradientDescent\Gradient\Logistic as LogisticGradient;
use MCordingley\Regression\Algorithm\GradientDescent\StoppingCriteria\GradientNorm;
use MCordingley\Regression\Observations;
use MCordingley\Regression\Predictor\Logistic as LogisticPredictor;






class LabController extends \yii\web\Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['avancado'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],]
            
        );
    }

    public function actionNovo()
    {
        
        $minhasConfig=LumilabController::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        $datas = LumilabController::pegaDatas();
        
        $dtInicio = $datas[0];
        $dtFim = $datas[1];
        $debug=false;

        $db = \Yii::$app->db;       
        
        $intervalo = "tudo";
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
        
        $iduser ="";
        $emailuser="";
        $cId="";
        if(isset(\Yii::$app->user->identity->id))
        {
            $iduser=@\Yii::$app->user->identity->id;
            $emailuser = @\Yii::$app->user->identity->username;
        }
    
    
    
        $db->createCommand()->insert('log', [
            'usuario_usuarioId' => @$iduser,
            'usuario_email' => @$emailuser,
            'curso_cursoId' => @$cId,
            'log_rota' => 'lab/novo',
            'log_intervalo' => @$intervalo,
            'log_acao' => 'novo',
            'log_outros' => '',
            
        ])->execute();
        
        
        return $this->render('novo', [
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        'indicador_posicao'=>$indicador_posicao,
        
    ]);   
        

    }

    public function actionAvancado()
    {
        
        $minhasConfig=LumilabController::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        $datas = LumilabController::pegaDatas();
        
        $dtInicio = $datas[0];
        $dtFim = $datas[1];
        $debug=false;
        $cId=isset($_GET["cid"])?$_GET["cid"]:$datas[2];
        $cDesc = $datas[3];


        $db = \Yii::$app->db;       
        
        $intervalo = "ultimo_ano";
        if(@$_GET["intervalo"])
        $intervalo=@$_GET["intervalo"];
        else
        $_GET["intervalo"]=$intervalo;
        $s_paginas = "SELECT * from pagina where paginaTipo='avancado' and paginaFiltro='$intervalo'
        and curso_cursoId=$cId";
        
        
        $c_paginas = $db->createCommand($s_paginas);

        
        $rs_paginas=$c_paginas->queryAll();
        $indicador_posicao=[];
        foreach($rs_paginas as $idid => $rpagina)
        {
            $indicador_posicao[$rpagina["paginaPosicao"]]=$rpagina["paginaHTML"];
        }
        
        $iduser ="";
        $emailuser="";
        if(isset(\Yii::$app->user->identity->id))
        {
            $iduser=@\Yii::$app->user->identity->id;
            $emailuser = @\Yii::$app->user->identity->username;
        }
        $db->createCommand()->insert('log', [
            'usuario_usuarioId' => @$iduser,
            'usuario_email' => @$emailuser,
            'curso_cursoId' => @$cId,
            'log_rota' => 'lab/avancado',
            'log_intervalo' => @$intervalo,
            'log_acao' => 'avancado',
            'log_outros' => '',
        ])->execute();

              
        return $this->render('avancado', [
        'dtInicio'=>$dtInicio,
        'curso_descricao'=>$cDesc,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        'indicador_posicao'=>$indicador_posicao,
        
    ]);   
    }
}

