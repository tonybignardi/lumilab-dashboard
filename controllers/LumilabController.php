<?php
namespace app\controllers;
use yii\db\Connection;
use app\models\LoginForm;
#use Phpml\Regression\LeastSquares;
#use Phpml\Classification\Linear\LogisticRegression;
use Phpml\Math\Statistic\StandardDeviation;

use MCordingley\Regression\Algorithm\GradientDescent\Batch;
use MCordingley\Regression\Algorithm\GradientDescent\Schedule\Adam;
use MCordingley\Regression\Algorithm\GradientDescent\Gradient\Logistic as LogisticGradient;
use MCordingley\Regression\Algorithm\GradientDescent\StoppingCriteria\GradientNorm;
use MCordingley\Regression\Observations;
use MCordingley\Regression\Predictor\Logistic as LogisticPredictor;

use MCordingley\Regression\Algorithm\LeastSquares;
#use MCordingley\Regression\Observations;
use MCordingley\Regression\Predictor\Linear;
use MCordingley\Regression\StatisticsGatherer\Linear as Stat;






class LumilabController extends \yii\web\Controller
{

    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
            'access' => [
                'class' => \yii\filters\AccessControl::class,
                'only' => ['avancado','questionario','forum','estatistica'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],]
            
        );
    }

public function actionSalvaPagina()
{
    if(@$_GET["acao"]=="sentimento")
    {
    $db = \Yii::$app->db;
    $url = \Yii::$app->basePath.'/assets/vadersentiment.php';
    #echo \Yii::$app->basePath;
    #exit();
    
        
    include $url;

    $fid=$_GET["fid"];
    

    $sel_post = "select p.forumPostId as id,p.forumPostMensagem as msg 
    from forumpost p join forumtopico t on 
    (t.forumTopicoId=p.forumtopico_forumTopicoId) 
    where t.forum_forumId=".$fid;
   
    $c_post = $db->createCommand($sel_post);
    $rs_post=$c_post->queryAll();
    
    #echo $sel_post;
    
    $sentimenter = new \SentimentIntensityAnalyzer();
    
    
    foreach($rs_post as $ind => $vpost)
    {
        
        
        #exit();
        
        #$result = $sentimenter->getSentiment($vpost["msg"]);
        $result=[];
        try{
            
        $result = $sentimenter->getSentiment(substr(strip_tags($vpost["msg"]),0,300));
        if(!$result)
        echo "ERRRRROOOOOO";
        
        }catch(Exception $e)
        {
            #echo 'eroumesmo';
            print_r($e);
        }
        
        $in = "update  forumpost set 
        sent_total='".@$result["compound"]."',sent_pos='".@$result["pos"]."',
        sent_neu='".@$result["neu"]."',sent_neg='". @$result["neg"]."'
         where forumPostId=".$vpost["id"];
        
        
       
        $db->createCommand("update forumpost set 
        sent_total=:c1,sent_pos=:c2,
        sent_neu=:c3,sent_neg=:c4 where forumPostId=:c5")
        ->bindValue(":c1",@$result["compound"])
        ->bindValue(":c5",@$vpost["id"])
        ->bindValue(":c2",@$result["pos"])
        ->bindValue(":c3",@$result["neu"])
        ->bindValue(":c4",@$result["neg"])->execute();
        
        echo "update".$vpost["id"]."<br>";
        
       

        
    }

    exit();
    }

    if(@$_GET["acao"]=="pegaCurso")
    {
        $db = \Yii::$app->db;
        $sql_q = "select * from curso where cursoVisivel=1";
        
        
        if(isset($_GET["ids"]))
        {
            $sql_q.=" and cursoId in (".$_GET["ids"].")";
        }

        
        $command = $db->createCommand($sql_q);
        $rs_geral=$command->queryAll();
      
       
        
        foreach($rs_geral as $rid =>$rv)
        {
            
        echo $rv["cursoId"]."->";
        
          
        $sel3 = "select * from forum where curso_cursoId=".$rv["cursoId"]."";
          
         $command3 = $db->createCommand($sel3);
         
         $rows3 = $command3->queryAll();
         $linha = "";
         foreach($rows3 as $ind3=>$vpost)
         $linha.=$vpost["forumId"].",";

         echo substr($linha,0,-1)."\n";

        }
        exit();

    }
    if(@$_GET["acao"]=="novo")
    {
        $pos = array(@$_GET["p"]);
        $_GET["r"]="lumilab/novo";
        $_GET["acao"]="novo";
    }

   
    if(@$_GET["acao"]=="avancado")
    {
        $pos = array($_GET["p"]);
        $_GET["r"]="lumilab/avancado";
        $_GET["acao"]="avancado";
    }

    if(@$_GET["acao"]=="questionario")
    {
        $pos = array($_GET["p"]);
        $_GET["r"]="lumilab/questionario";
        $_GET["acao"]="questionario";
    }

    
    
    $inter =@$_GET["intervalo"];
    $datas = self::pegaDatas();
    @$_GET["dti"] = $datas[0];
    @$_GET["dtf"] = $datas[1];
    

    $cid=@$_GET["cid"];
    
    @$_GET["areas"]=!isset($_GET["areas"])?"":$_GET["areas"];
    @$_GET["cargas"]=!isset($_GET["cargas"])?"":$_GET["cargas"];
    @$_GET["_csrf"]= \Yii::$app->request->getCsrfToken();
    
    foreach($pos as $ii => $vv)
    {
        $_GET["posicao"]=$vv;
        
        echo "construindo pagina $inter [".@$_GET["acao"]."] .. posicao ". $vv ."<br>";
        if($_GET["acao"]=="questionario")
        {
        
            $db = \Yii::$app->db;
            $sql_q = "select distinct quizId,r.questaoTentativa tent from questaoresposta r join questao a on (r.questao_questaoId=a.questaoId) join quiz q on (q.quizId=a.quiz_quizId)
            where q.curso_cursoId='$cid' and r.questaoRespostaData between '$datas[0]' and '$datas[1]' and r.resultado in('gradedwrong','gradedright','gradedpartial') and r.questaoTentativa<4 order by quizId,tent;";
             
    
            $command = $db->createCommand($sql_q);
            $rs_geral=$command->queryAll();
            
            foreach($rs_geral as $ind => $row)
            {
                
               
                    $_GET["tentativa"]=$row["tent"];
                    $_GET["quiz"]=$row["quizId"];
                    self::actionCarregaPosicao();
             
            }
            
            
        }
        else
        {
            self::actionCarregaPosicao();
        }
        
        
        if(@$_GET["acao"]=="novo" && in_array($vv,array("51","52")))
        {
            @$_GET["indicador"]=$vv=="51"?"inscricoes":"certificados";
            @$_GET["opcao"]="dia";
            self::actionSample();
            
            
            @$_GET["opcao"]="mês";
            self::actionSample();

            
            @$_GET["opcao"]="ano";
            self::actionSample();

        }
        if(@$_GET["acao"]=="avancado" && in_array($vv,array("21","22")))
        {
            @$_GET["indicador"]=$vv=="21"?"inscricoes":"certificados";
            @$_GET["opcao"]="dia";
            self::actionSample();
            
            @$_GET["opcao"]="mês";
            self::actionSample();

            @$_GET["opcao"]="ano";
            self::actionSample();

        }
    }

    
    
}


public function actionCarregaQuestao()
{
    if(!isset($_GET['qid']) || @$_GET['qid']=="#1")
    return '<b class="ativo_q">Sem Dados</b>';
    
    $qid=explode("#",$_GET['qid']);

    

    $db = \Yii::$app->db;
    $sql_q = "select questaoTexto from questao where questaoId=".$qid[0];
     
     $command = $db->createCommand($sql_q);
     
     
     $rs_geral=$command->queryAll();
     return "<p class='ativo_q'><b>Q".$qid[1].")</b>".strip_tags($rs_geral[0]["questaoTexto"])."</p>";
    

}

function tDistribution($tValue, $df)
{
    $tDist = $this->tDistributionCDF($tValue, $df);
    return 1 - $tDist; // P-value é calculado como 1 - CDF(t)
}

// Função para calcular a distribuição acumulada (CDF) da distribuição t de Student
function tDistributionCDF($tValue, $df)
{
    $x = $tValue / sqrt($df);
    $it = 1 / (1 + $x * $x / $df);
    $prob = 0.5 + 0.5 * ($it + $x * sqrt($it * (1 - $it) / $df));
    return $prob;
}

function double_fac($n){
    if(($n <= 1))return 1;
    else return $n*$this->double_fac($n-2);
}

function dist($x,$graus){
    
    if($graus>300){
        return 0.3989422804014326779*exp(-$x*$x*0.5);
    }else{
        
        $coeff = $this->double_fac($graus-1) / (sqrt($graus) * $this->double_fac($graus-2) * ($graus % 2 == 0 ? 2 : pi()));
        return $coeff*pow(1+$x*$x/$graus,-0.5*($graus+1));
    }

    
}

function trapezoid_rule($a,$b,$graus){
    if($a==$b)
        return 0;

    $trap_sum = 0;
    for($i=1;$i<1000;$i++)
    {
        
        $trap_sum += $this->dist($a + $i*($b-$a)*0.001,$graus);
    }  
    
    $retorno = 0.0005*abs($b-$a)*($this->dist($a,$graus)+$this->dist($b,$graus)+2*$trap_sum);   
    return 1-2*$retorno;
}

// Definir o valor t e os graus de liberdade

public function erf($x) {
    # constants
    $a1 =  0.254829592;
    $a2 = -0.284496736;
    $a3 =  1.421413741;
    $a4 = -1.453152027;
    $a5 =  1.061405429;
    $p  =  0.3275911;

    # Save the sign of x
    $sign = 1;
    if ($x < 0) {
        $sign = -1;
    }
    $x = abs($x);

    # A&S formula 7.1.26
    $t = 1.0/(1.0 + $p*$x);
    $y = 1.0 - ((((($a5*$t + $a4)*$t) + $a3)*$t + $a2)*$t + $a1)*$t*exp(-$x*$x);

    return $sign*$y;
}
public function regressaoLinear($group2,$arquivo1,$salvaarq = true)
{
   
   
    
    $regression = new LeastSquares();
    
   
    $amostral = [];
    $labels = [];
    $dados1 =[];
    $dados2 =[];
    
   
    $maximo=1;
    if($salvaarq)
    $arquivo="v1;v2;reta\n";
    else
    $arquivo="";

    if($arquivo1!="")
    $arquivo.=$arquivo1;

    $reta = $arquivo1!="" && $salvaarq?"retaLumina":"retaCurso";
    $labelsujo = array();
    foreach($group2 as $i=>$v)
    {
        $labels[]=[floatval($v["vperfil"])];
        $labelsujo[]=floatval($v["vperfil"]);
        $amostral[]=floatval($v["val"]);
        $dados2[]=array($v["vperfil"],$v["val"]);
        $maximo = max($v["vperfil"],$maximo);
        $arquivo.=$v["vperfil"].";".$v["val"].";".$reta."\n";
    }

    if($salvaarq)
    {
    $db = \Yii::$app->db;
    $db->createCommand()->insert('relatorio', [
        'chave' => sha1('abc'.\Yii::$app->user->identity->id),
        'txt' => $arquivo,
    ])->execute();
    }

    $obs = new Observations;

// Load the data
    foreach ($labels as $ind =>$dataum) {
    // Note addition of a constant for the first feature.
    
    $obs->add(array_merge([1.0], $dataum),$amostral[$ind]);
    }
    #$obs = Observations::fromArray($labels, $amostral);
    #echo 'pearson';
    #exit();
    #echo stats_stat_correlation($labelsujo,$amostral);
    
    
    
    $coef = $regression->regress($obs);
    
    
    
    
    
    $linear = new Linear($coef);
    
    
    
    $gatherer = new Stat($obs, $coef, $linear);
    
    #echo $gatherer->getRSquared();
    #echo $gatherer->getFStatistic();
    #$gatherer->getTStatistics();
    #exit();
    #print_r($gatherer->getTStatistics());
    #exit();
    $tstat = array();
    foreach($gatherer->getTStatistics() as $i =>$v)
        $tstat[] = number_format($v,4,".",".");
   
   
    $retav=[];
    $retal=[];
    $todos_is=[];
    
    $i=0;
    $iinterno=0;
    

    while($i<=$maximo)
    {
        
        $ponto = $linear->predict(array_merge([1.0], [$i]));
        $retav[$iinterno]=number_format($ponto,2,".",".");
        $retal[]=array(number_format($i,2,".","."),$retav[$iinterno]);
        $i+=($maximo/10);
        $iinterno++;
        
    }
    
    #echo  "df ".$gatherer->getDegreesOfFreedomTotal();        
    
    #echo "<br>f sta".$gatherer->getFStatistic();
    
    $erro = $gatherer->getStandardErrorCoefficients();
    #echo "<br>erro".$erro;

    #$erro=1;
     #= number_format( (1 - 0.5 * (1 + $this->erf($tvalue / sqrt(2)))),6,".",".");
     #echo "tvalue" .$tvalue;
     #echo "<br>t0:".$gatherer->getTStatistics()[0]."<Br>";
     #echo "<br>t1:".$gatherer->getTStatistics()[1]."<Br>";
     
     #echo "<pre>";
     
     
     
     $p_value=$this->trapezoid_rule(0,$tstat[1],$gatherer->getDegreesOfFreedomTotal());
    #$p_value[] = $this->fDistsribution($gatherer->getFStatistic(),$gatherer->getDegreesOfFreedomTotal(),0);
    #return array($retal,$dados2,array($erro,$coef[1],$gatherer->getRSquared(),$p_value),$coef[0],$arquivo);
    $rajustado = 1-((count($dados2)-1)/((count($dados2)-1)-1))*(1-$gatherer->getRSquared());
    return array($retal,$dados2,array(count($dados2),$coef[0],$coef[1],$rajustado,$p_value),$arquivo);
    //$labels   = [[2.35], [9.25], [7.28], [17.42], [8.70]];

}
public function calculate_median($arr) {
    $count = count($arr); //total numbers in array
    $middleval = floor(($count)/2); // find the middle value, or the lowest middle value
    if($count % 2) { // odd number, middle is the median
        $median = $arr[$middleval];
    } else { // even number, calculate avg of 2 medians
        $low = $arr[$middleval];
        $high = $arr[$middleval+1];
        $median = (($low+$high)/2);
    }
    return $median;
}
public function regressaoLogistica($group1,$arquivo1="",$salvaarq=false)
{
    $combined=[];
    $vetregressao =[];
    $resregressao=[];
    $maxmax=0;
    $reta = "reta1";
    
    $arquivo="";
    if($salvaarq)
    $arquivo="variavel;certificado;reta\n";
    if($arquivo1!="")
    {
    $arquivo.=$arquivo1;
    $reta = "reta2";
    }
    $soma = 0;
    foreach($group1 as $i=>$v)
    {
        
        $cert = substr($v["val"],0,10)=="0000-00-00"?0:1;
        $vetregressao[]=[$v["vperfil"]];
        $resregressao[]=$cert;
        if(floatval($v["vperfil"])>$maxmax)
        $maxmax=floatval($v["vperfil"]);
        $soma+=floatval($v["vperfil"]);
        
        $arquivo.=$v["vperfil"].";".$cert.";$reta\n";
    }
    if($salvaarq)
    {
    $db = \Yii::$app->db;
    $db->createCommand()->insert('relatorio', [
        'chave' => sha1('abc'.\Yii::$app->user->identity->id),
        'txt' => $arquivo,
    ])->execute();
    }

    $media = $soma/sizeof($vetregressao);
    $novovet = [];
    foreach($vetregressao as $vi =>$vr)
    {
        $calc = ($vr[0]-$media)/$maxmax;
        $novovet[]=[$calc];
    }
    
    #$modelRelo = new LogisticRegression();
    #$modelRelo->train($vetregressao, $resregressao);
    
    

    $algorithm = new Batch(new LogisticGradient, new Adam, new GradientNorm);
    #print_r(Observations::fromArray($vetregressao, $resregressao));

    $coefficients = $algorithm->regress(Observations::fromArray($novovet,$resregressao));

    $predictor = new LogisticPredictor($coefficients);
    
    $results = [];
    $minmin = 0;
    $incr=0.05;
    
    
    if($maxmax>2)
    $incr=0.5;
    $vals=$minmin;
    $vetvals=[];
    while($vals<=$maxmax)
    {
        #$vval = $modelRelo->predictProbability([$vals],'1');
        #$vval = $modelRelo->predict([$vals]);
        $vval = $predictor->predict([$vals]);
        #$vval = $modelRelo->predictProbability([$vals],'1');
        $results[] = [number_format($vals,2,".","."),number_format($vval,2,".",".")];
        $vetvals[]=number_format($vals,2,".",".");
        $vals+=$incr;
    }
    
    
    
    return [$vetvals,$results,$arquivo];
    
    

}

public function mannWhitneyUTest($group1, $group2) {
    // Combine the two groups
    

    // Rank the combined data
    
    
    $ranks = [];
    $rank = 1;
    $combined=[];
    $repeticoes =[];
    $GG=[];
    
    $arquivo="variavel;CxE\n";
    foreach($group1 as $i=>$v)
    {
        $combined[]=$v["val"];
        @$repeticoes[$v["val"]]++;
        @$GG['g1'][$v["val"]]++;
        $arquivo.=$v["val"].";C\n";
        
    }
    $ordgroup=$combined;
    sort($ordgroup);
    
    $ng1 = sizeof($group1);
    $medianag1= $this->calculate_median($ordgroup);    
    $q1g1 = $ng1 %2==0?($ng1+2)/4:($ng1+3)/4;
    $q3g1 = $ng1 %2==0?(3*$ng1+2)/4:(3*$ng1+1)/4;

    $q1g1 = is_int($q1g1)?$ordgroup[$q1g1-1]:($ordgroup[$q1g1-2]+$ordgroup[$q1g1-1])/2;
    $q3g1 = is_int($q3g1)?$ordgroup [$q3g1-1]:($ordgroup[$q3g1-2]+$ordgroup[$q3g1-1])/2;
    
    $maxmax = number_format(($q3g1+1.5*($q3g1-$q1g1)),2,".",".");
    $minmin = number_format(($q1g1-1.5*($q3g1-$q1g1)),2,".",".");

    $maxmax = $maxmax>$ordgroup[$ng1-1]?$ordgroup[$ng1-1]:$maxmax;
    $minmin = $minmin<$ordgroup[0]?$ordgroup[0]:$minmin;
    
    
    
    $ordgroup=$combined;
    $ordgroup2 = [];
    foreach($group2 as $i=>$v)
    {
        $combined[]=$v["val"];
        $ordgroup2[]=$v["val"];
        @$repeticoes[$v["val"]]++;
        @$GG['g2'][$v["val"]]++;
        $arquivo.=$v["val"].";E\n";
    }

    #$infile = "insert into txt values('".\Yii::$app->user->identity->id."',NOW(),'$arquivo')";
    $db = \Yii::$app->db;
    $db->createCommand()->insert('relatorio', [
        'chave' => sha1('abc'.\Yii::$app->user->identity->id),
        'txt' => $arquivo,
    ])->execute();

    
    sort($ordgroup2);




    
    $ng2 = sizeof($group2);
    
    
    $medianag2= $this->calculate_median($ordgroup2);    
    $q1g2 = $ng2 %2==0?($ng2+2)/4:($ng2+3)/4;
    $q3g2 = $ng2 %2==0?(3*$ng2+2)/4:(3*$ng2+1)/4;
    
    

    $q1g2 = is_int($q1g2)?$ordgroup2[$q1g2-1]:($ordgroup2[$q1g2-2]+$ordgroup2[$q1g2-1])/2;
    $q3g2 = is_int($q3g2)?$ordgroup2[$q3g2-1]:($ordgroup2[$q3g2-2]+$ordgroup2[$q3g2-1])/2;
    
    
    

    $maxmax2 = number_format(($q3g2+1.5*($q3g2-$q1g2)),2,".",".");
    $minmin2 = number_format(($q1g2-1.5*($q3g2-$q1g2)),2,".",".");

    
    
    $maxmax2 = $maxmax2>$ordgroup2[$ng2-1]?$ordgroup2[$ng2-1]:$maxmax2;
    $minmin2 = $minmin2<$ordgroup2[0]?$ordgroup2[0]:$minmin2;
    
  
    sort($combined);
    $somador=[];
    foreach ($combined as $value) {
        $ranks[] = $rank;
        @$somador[$value]+=$rank;
        $rank++;
    }
    $ii=0;
    $g1=0;
    $g2=0;
    foreach($GG['g1'] as $value => $qtd)
    {
        $g1+=(@$somador[$value]/@$repeticoes[$value])*$qtd;
        
    }
    foreach($GG['g2'] as $value => $qtd)
    {
        $g2+=(@$somador[$value]/@$repeticoes[$value])*$qtd;
    }


    
   
    $n1 = count($group1);
    $n2 = count($group2);

    $u1 = $n1*$n2+(($n1*($n1+1))/2)-$g1;

    $u2 = $n1*$n2+(($n2*($n2+1))/2)-$g2;

    

    // Calculate the Mann-Whitney U statistic
    
    $mean_U = ($n1 * $n2 / 2);
    $std_dev_U = sqrt(($n1 * $n2 * ($n1 + $n2 + 1)) / 12);

    // Calculate the z-score
    $z = abs((max($u1,$u2) - $mean_U) / $std_dev_U);

    // Calculate the p-value using a normal distribution approximation
    $p_value = 2 * (1 - 0.5 * (1 + $this->erf($z / sqrt(2))));

    return array('pvalue'=>$p_value,'u1'=>$u1,'u2'=>$u2,
    'g1'=>array($minmin,$q1g1,$medianag1,$q3g1,$maxmax),
    'g2'=>array($minmin2,$q1g2,$medianag2,$q3g2,$maxmax2));
    #'g1'=>array($ordgroup),
    #'g2'=>array($ordgroup2));
}


//This is sample you can modify according your need
public function actionCarregaPosicao()
{
    
    
    $minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
    $posicao=@$_GET['posicao'];
    $g_posicao="";
    
    
    #$datas = self::pegaDatas();
    $dtInicio=$_GET["dti"];
    $dtFim=$_GET["dtf"];
    $cId=@$_GET["cid"];
    $mantergrupo = @$_GET["manter_grupo_filtro"];
    $grupo_filtro = @$_GET["grupo_filtro"];
    $curso_filtro = @$_GET["curso_filtro"];
    $vteste = @$_GET["v_teste"];
    
    $iduser ="";
    $emailuser="";

    if(isset(\Yii::$app->user->identity->id))
    {
        $iduser=@\Yii::$app->user->identity->id;
        $emailuser = @\Yii::$app->user->identity->username;
    }
    $db = \Yii::$app->db;
    $db->createCommand()->insert('log', [
        'usuario_usuarioId' => @$iduser,
        'usuario_email' => @$emailuser,
        'curso_cursoId' => @$cId,
        'log_rota' => 'avancado-'.@$_GET['acao'],
        'log_intervalo' => @$intervalo . "-".$dtInicio."-".$dtFim,
        'log_acao' => @$_GET["acao"],
        'log_outros' => $posicao . "-".$curso_filtro."-".$grupo_filtro 
        ."-q".@$_GET['quiz'] ."-f".@$_GET['forum'].'-t'.@$_GET['tentativa'],
    ])->execute();
    
    if(@$_GET["acao"]=="forum")
    {

      
       
        
        $forum = @$_GET["forum"];
        $forumdesc = substr(@$_GET["forum_desc"],0,40);

        $sqlarea1="";
        $sqlarea2 = "";
        if(@$_GET["areas"]!="" or @$_GET["cargas"]!="")
        {
            if(@$_GET["areas"]!="")
            $sqlarea2=" and c.areaCurso_areaCursoId in (".$_GET["areas"].")";
            else
            $sqlarea2="";
            if(@$_GET["cargas"]!="")
            {
                $vcargas = explode(",",$_GET["cargas"]);
                $auxcarga= "";
                foreach($vcargas as $icarga =>$vcarga)
                {
                    $v =explode("-",$vcarga);
                    $auxcarga.="ch.cargahoraria between $v[0] and $v[1] or ";
                }
               
                $sqlarea2.=" and (".substr($auxcarga,0,-3).")";
            }
            $sqlarea1=" left join curso c on (c.cursoId=p.curso_cursoId) left join cursocarga ch on
            (ch.curso_cursoId=c.cursoId) ";
        }
        
        switch($posicao){
            case '11':
                $g_posicao = $this->filtrar_indicador([true],[],"lista_forum",[$dtInicio,$dtFim],array($cId),['0','12'],[],[],'<h5 class="texto-com-icone"><i class="icone icone-livro"></i> <strong>Fóruns / respostas:</strong></h5>');
                break; 
            case '12':
                    $g_posicao = $this->filtrar_indicador([true],[],"lista_forum",[$dtInicio,$dtFim],array($cId),['12','12'],[],[],'-<h5 class="texto-com-icone"><i class="icone icone-livro"></i> <strong>Fóruns:</strong></h5>');
                    break; 
            case '41':
               
                
                $testatistica="<i class='ativo2'>Lúmina</i>";   
                $db = \Yii::$app->db;
                
                $arrfor = array("$sqlarea1 where p.curso_cursoId!=$cId and p.forumPostData between '$dtInicio' and '$dtFim' $sqlarea2","where p.curso_cursoId='$cId' and p.forumPostData between '$dtInicio' and '$dtFim' and p.forumtopico_forumTopicoId=$forum");
                $arrafinal=[];
                foreach($arrfor as $ind =>$forinterno)
                {

                    $sel_curso = "SELECT p.aluno_alunoId as aluno ,count(1) as val FROM forumpost p 
                    $forinterno
                    group by aluno";
                    
                    
                    
                    $c_curso = $db->createCommand($sel_curso);
                    $rs_curso=$c_curso->queryAll();
                    #echo $sel_curso;
                    #echo "<br>";

                    if(sizeof($rs_curso)!=0)
                    {
                    
                        $ordgroup=[];
                        foreach($rs_curso as $i=>$v)
                        {
                            $ordgroup[]=$v["val"];
                        }
                        if(count($ordgroup)<3)
                        $ordgroup[]=1;
                        sort($ordgroup);
                        
                    

                        $ng1 = sizeof($ordgroup);
                        $medianag1= $this->calculate_median($ordgroup);
                        #$medianag1=10;
                        $q1g1 = $ng1 %2==0?($ng1+2)/4:($ng1+3)/4;
                        $q3g1 = $ng1 %2==0?(3*$ng1+2)/4:(3*$ng1+1)/4;

                        $q1g1 = is_int($q1g1)?$ordgroup[$q1g1-1]:($ordgroup[$q1g1-2]+$ordgroup[$q1g1-1])/2;
                        $q3g1 = is_int($q3g1)?$ordgroup [$q3g1-1]:($ordgroup[$q3g1-2]+$ordgroup[$q3g1-1])/2;
                        
                        $maxmax = number_format(($q3g1+1.5*($q3g1-$q1g1)),2,".",".");
                        $minmin = number_format(($q1g1-1.5*($q3g1-$q1g1)),2,".",".");

                        $maxmax = $maxmax>$ordgroup[$ng1-1]?$ordgroup[$ng1-1]:$maxmax;
                        $minmin = $minmin<$ordgroup[0]?$ordgroup[0]:$minmin;

                        $datadata2= array($minmin,$q1g1,$medianag1,$q3g1,$maxmax);
                        $arrafinal[]=$datadata2;
                    
                    

                    
                    
                    
                    $testatistica.="<br>Total: ". array_sum($ordgroup)."<br>";
                    $testatistica.="Média: ". number_format((array_sum($ordgroup)/$ng1),2,".",".")."<br>";
                    $testatistica.="Desvio: ". number_format(StandardDeviation::population($ordgroup),2,".",".")."<br><br>";
                    if($ind==0)
                    $testatistica.="<i class='ativo_c'>Fórum:</i>";
                    }
                    else
                    {
                    $testatistica.="<br>Total: - <br>";
                    $testatistica.="Média: -<br>";
                    $testatistica.="Desvio: - <br><br>";
                    $arrafinal[]=array(0,0,0,0,0);
                    }
                }
                
                
                
                /*
                $url = \Yii::$app->basePath.'/assets/vadersentiment.php';
        
                include $url;

                

                $sel_post = "select p.forumPostMensagem as msg from forumpost p join forumtopico t on (t.forumTopicoId=p.forumtopico_forumTopicoId) where p.curso_cursoId='$cId' and p.forumPostData between '$dtInicio' and '$dtFim' and t.forum_forumId=$forum";
                
                $c_post = $db->createCommand($sel_post);
                $rs_post=$c_post->queryAll();
                
                
                
                $sentimenter = new \SentimentIntensityAnalyzer();
                echo $sel_post;
                exit();
                foreach($rs_post as $ind => $vpost)
                {
                    
                    echo $vpost["msg"];
                   # $result = $sentimenter->getSentiment($vpost["msg"]);
                   # print_r($result);
                    echo "<br>";
                    #exit();

                    
                }
                 
            

                #$textToTest = "Eu estou um pouco triste hoje";
        
                #
                
                */
               
                $g_posicao =  $this->chartBox([['data'=>@$arrafinal[0],'name'=>'Lúmina'],['data'=>@$arrafinal[1],'name'=>'Curso']], 'Fórum:'.$forumdesc, 'Posts por Estudantes', 300).
                "#DIVISAO#".@$testatistica;
                    
                break;

            case '31':

                    $testatistica="<i class='ativo2'>Lúmina</i>";   
                    $db = \Yii::$app->db;
                    
                    $arrfor = array("$sqlarea1 where p.curso_cursoId!=$cId and p.forumPostData between '$dtInicio' and '$dtFim' $sqlarea2","where p.curso_cursoId='$cId' and p.forumPostData between '$dtInicio' and '$dtFim' and p.forumtopico_forumTopicoId=$forum");
                    $arrafinal=[];
                    
                    foreach($arrfor as $ind =>$forinterno)
                    {
    
                        $sel_curso = "select ((LENGTH(p.forumPostMensagem) - LENGTH(replace(p.forumPostMensagem,' ','')))+1) as cont FROM forumpost p ".@$forinterno;
                        
                        $c_curso = @$db->createCommand(@$sel_curso);
                        
                       #echo $sel_curso;
                        $rs_curso=@$c_curso->queryAll();
                        
                        if(sizeof($rs_curso)!=0)
                        {
                         
                            $ordgroup=[];
                            
                            foreach($rs_curso as $i=>$v)
                            {
                                #$cont= @sizeof(explode(" ",@$v["msg"]));
                                $ordgroup[]=$v["cont"];
                            }
                            if(count($ordgroup)<3)
                            
                            $ordgroup[]=1;
                            sort($ordgroup);
                            
                            $ng1 = sizeof($ordgroup);
                            $medianag1= $this->calculate_median($ordgroup);    
                            $q1g1 = $ng1 %2==0?($ng1+2)/4:($ng1+3)/4;
                            $q3g1 = $ng1 %2==0?(3*$ng1+2)/4:(3*$ng1+1)/4;
        
                            $q1g1 = is_int($q1g1)?$ordgroup[$q1g1-1]:($ordgroup[$q1g1-2]+$ordgroup[$q1g1-1])/2;
                            $q3g1 = is_int($q3g1)?$ordgroup [$q3g1-1]:($ordgroup[$q3g1-2]+$ordgroup[$q3g1-1])/2;
                            
                            $maxmax = number_format(($q3g1+1.5*($q3g1-$q1g1)),2,".",".");
                            $minmin = number_format(($q1g1-1.5*($q3g1-$q1g1)),2,".",".");
        
                            $maxmax = $maxmax>$ordgroup[$ng1-1]?$ordgroup[$ng1-1]:$maxmax;
                            $minmin = $minmin<$ordgroup[0]?$ordgroup[0]:$minmin;
        
                            $datadata2= array($minmin,$q1g1,$medianag1,$q3g1,$maxmax);
        
                            $arrafinal[]=$datadata2;
                            
                        
                            $testatistica.="<br>Total: ". array_sum($ordgroup)."<br>";
                            $testatistica.="Média: ". number_format((array_sum($ordgroup)/$ng1),2,".",".")."<br>";
                            $testatistica.="Desvio: ". number_format(StandardDeviation::population($ordgroup),2,".",".")."<br><br>";
                            if($ind==0)
                            $testatistica.="<i class='ativo_c'>Fórum:</i>";
                        }
                        else
                        {
                            $testatistica.="<br>Total: - <br>";
                            $testatistica.="Média: -<br>";
                            $testatistica.="Desvio: - <br><br>";
                            $arrafinal[]=array(0,0,0,0,0);
                        }
                        
                    }
                    $g_posicao =  $this->chartBox([["data"=>@$arrafinal[0],"name"=>"Lúmina"],["data"=>@$arrafinal[1],"name"=>"Curso"]], 'Fórum:'.@$forumdesc, "Palavras em posts", 300).
                    "#DIVISAO#".@$testatistica;
                        
                    break;
            case '21':  

                
                $descsent = array("Sentimento Negativo","Sentimento Neutro","Sentimento Positivo","Escala sentimento");
                $corsent = ['#d9534f','#f0ad4e','#5cb85c','#0275d8'];
                $db = \Yii::$app->db;
                 
                $arrfor = array("p.sent_neg as cont","p.sent_neu as cont","p.sent_pos as cont","p.sent_total as cont");
                $arrafinal=[];
                $testatistica="<table><tr>";
                foreach($arrfor as $ind =>$forinterno)
                {

                    $sel_curso = "select $forinterno from forumpost p where p.curso_cursoId='$cId' and
                    sent_neg!='' and p.sent_pos!='' and p.sent_neu!='' and p.sent_total!=''
                     and p.forumPostData between '$dtInicio' and '$dtFim' and p.forumtopico_forumTopicoId=$forum";
                    
                    $c_curso = @$db->createCommand(@$sel_curso);
                    
                   #echo $sel_curso;
                    $rs_curso=@$c_curso->queryAll();
                    
                    
                    if(sizeof($rs_curso)!=0)
                    {
                        $ordgroup=[];
                        $unit =0;
                        foreach($rs_curso as $i=>$v)
                        {
                            #$cont= @sizeof(explode(" ",@$v["msg"]));
                           
                            if($v["cont"]!=0)
                            {
                                $ordgroup[]=$v["cont"];
                                $unit++;
                            }
                            
                        }
                        if(count($ordgroup)<3)
                        
                        $ordgroup[]=1;
                        sort($ordgroup);
                        
                        $ng1 = sizeof($ordgroup);
                        $medianag1= $this->calculate_median($ordgroup);    
                        $q1g1 = $ng1 %2==0?($ng1+2)/4:($ng1+3)/4;
                        $q3g1 = $ng1 %2==0?(3*$ng1+2)/4:(3*$ng1+1)/4;
    
                        $q1g1 = is_int($q1g1)?$ordgroup[$q1g1-1]:($ordgroup[$q1g1-2]+$ordgroup[$q1g1-1])/2;
                        $q3g1 = is_int($q3g1)?$ordgroup [$q3g1-1]:($ordgroup[$q3g1-2]+$ordgroup[$q3g1-1])/2;
                        
                        $maxmax = number_format(($q3g1+1.5*($q3g1-$q1g1)),2,".",".");
                        $minmin = number_format(($q1g1-1.5*($q3g1-$q1g1)),2,".",".");
    
                        $maxmax = $maxmax>$ordgroup[$ng1-1]?$ordgroup[$ng1-1]:$maxmax;
                        $minmin = $minmin<$ordgroup[0]?$ordgroup[0]:$minmin;
    
                        $datadata2= array($minmin,$q1g1,$medianag1,$q3g1,$maxmax);
    
                        $arrafinal[]=$datadata2;
                        
                        $testatistica.="<td><i style='margin:2px;background-color:".$corsent[$ind].";color:white'>".$descsent[$ind]."</i>";  
                        $testatistica.="<br>Soma: ". array_sum($ordgroup)."<br>";
                        $testatistica.="Média: ". number_format((array_sum($ordgroup)/$unit),2,".",".")."<br>";
                        $testatistica.="Desvio: ". number_format(StandardDeviation::population($ordgroup),2,".",".")."<br>";
                        $testatistica.="Qtd Respostas: ". $unit."<br></td>";
                        
                        
                    }
                    else
                    {
                        $testatistica.="<td><br>Total: - <br>";
                        $testatistica.="Média: -<br>";
                        $testatistica.="Desvio: -<br>";
                        $testatistica.="Qtd Respostas: - <br></td>";
                        $arrafinal[]=array(0,0,0,0,0);
                    }
                    if($ind==1)
                    $testatistica.="</tr><tr>";

                    
                }
                    $g_posicao =  $this->chartBox([["data"=>@$arrafinal[0],"name"=>"Negativo"],
                    ["data"=>@$arrafinal[1],"name"=>"Neutro"],["data"=>@$arrafinal[2],"name"=>"Positivo"],
                    ["data"=>@$arrafinal[3],"name"=>"Escala"]], 'Fórum:'.@$forumdesc, "Sentimentos nos posts", 300).
                    "#DIVISAO#".@$testatistica."</tr></table>";
                    
                    break;

            }
            return $g_posicao;
    }

    if($_GET["acao"]=="estatistica")
    {
        
            
        $sel_perfil=@$_GET["sel_perfil"];
        
        $vobjetivos = @$_GET["v_objetivo"];
        $vvars = @$_GET["v_vars"];
        $varx = @$_GET["varx"];
        $vcontadores = @$_GET["v_contadores"];
        $acontadores = explode(",",$vcontadores);
        

        if($vteste=="will1" or $vteste=="reli1" or $vteste=="relo1")
        {
        return $this->chartBox([["data"=>[0,1,2,3,4],"name"=>""],["data"=>[0,1,2,3,4],"name"=>""]], '..Processando', "Processando", 400).
        "#DIVISAO#..Processando";
        }
                

        $sqlarea1="";
        $sqlarea2 = "";
        if(@$_GET["areas"]!="" or @$_GET["cargas"]!="")
        {
            if(@$_GET["areas"]!="")
            $sqlarea2=" and c.areaCurso_areaCursoId in (".$_GET["areas"].")";
            else
            $sqlarea2="";
            if(@$_GET["cargas"]!="")
            {
                $vcargas = explode(",",$_GET["cargas"]);
                $auxcarga= "";
                foreach($vcargas as $icarga =>$vcarga)
                {
                    $v =explode("-",$vcarga);
                    $auxcarga.="ch.cargahoraria between $v[0] and $v[1] or ";
                }
               
                $sqlarea2.=" and (".substr($auxcarga,0,-3).")";
            }
            $sqlarea1=" left join curso c on (c.cursoId=ai.curso_cursoId) left join cursocarga ch on
            (ch.curso_cursoId=c.cursoId) ";
        }







        $db = \Yii::$app->db;
        

        
        

        $varr = explode(",",$vvars);
        
        
        
        $oquebuscar = "ai.alunoInscricaoNotaFinal";
        $oquefiltra = "ai.alunoInscricaoDataCertificado";
        $wherebuscar ="1=1"; 
        $m_estatistica = "<b> nota final, para concluintes,</b>";
        $filtro_perfil = ",";
        $tabelaBuscar = "alunoinscricao ai join aluno a on (a.alunoId=ai.aluno_alunoId)";
        $tabelaBuscar2 = "alunoinscricao ai join aluno a on (a.alunoId=ai.aluno_alunoId) $sqlarea1";
        $having="";
        $nothaving="";
       
        if($sel_perfil!="")
        {
            $asel = explode(";",$sel_perfil);
            $ahaving  =[];
            
            
            array_pop($asel);
                
            foreach($asel as $i =>$linha)
            {
                $alinha = explode(":",$linha);
                $ahaving[substr($alinha[0],0,-2)][]=$alinha[1]=="Não respondido"?"":$alinha[1];
                
                
            }
            
            $ind=0;
            foreach($ahaving as $i =>$v)
            {   
                
                if(sizeof($v)!=$acontadores[$ind])
                {
                    if(in_array("",$v))
                    $having.="(a.".$i." in('".implode("','",$v)."') or a.".$i." is null) and ";
                    else
                    $having.="a.".$i." in('".implode("','",$v)."') and ";
                    $nothaving.="a.".$i." not in('".implode("','",$v)."') and ";
                    $filtro_perfil .= "a.".$i.",";
                }
                $ind++;
            }
            
            $having = "and ". substr($having,0,-4);
            $nothaving = "and ". substr($nothaving,0,-4);
            
        }




        if($having=="")
        {
        $tabelaBuscar = "alunoinscricao ai ";
        $tabelaBuscar2 = "alunoinscricao ai  $sqlarea1";
        }
        

        if($mantergrupo=="S" && $grupo_filtro!="S")
            $nothaving=$having;
        
        #echo "mg".$mantergrupo . "gf".$grupo_filtro;
        

        $filtro_perfil = substr($filtro_perfil,0,-1);
        //echo "selPe". $sel_perfil;
        
        if(in_array("nf_concluintes",$varr))
        {
            
            $wherebuscar ="ai.alunoInscricaoDataCertificado is not null  and ai.alunoInscricaoDataCertificado!='0000-00-00 00:00:00' and ai.alunoInscricaoDataCertificado>ai.alunoInscricaoData and (ai.alunoInscricaoNotaFinal>0) "; 
            $m_estatistica = "<b> nota final, para concluintes,</b>";
        }
        if(in_array("n1_concluintes",$varr))
        {
            $oquebuscar="ai.alunoInscricaoNota1Tent";
            $wherebuscar ="ai.alunoInscricaoDataCertificado is not null  and ai.alunoInscricaoDataCertificado!='0000-00-00 00:00:00' and ai.alunoInscricaoDataCertificado>ai.alunoInscricaoData and (ai.alunoInscricaoNota1Tent>0)"; 
            $m_estatistica = "<b> nota da 1 tentativa, para concluintes,</b>";
        }
        
        if(in_array("dias_certificado",$varr))
        {
            $oquebuscar="ai.diasUnicosNoCurso";
            $wherebuscar ="ai.alunoInscricaoDataCertificado is not null  and ai.alunoInscricaoDataCertificado!='0000-00-00 00:00:00' and ai.alunoInscricaoDataCertificado>ai.alunoInscricaoData"; 
            $m_estatistica = "<b> qtd de dias únicos no curso, para concluintes,</b>";
        }

        if(in_array("dias_ativos",$varr))
        {
            $oquebuscar="ai.diasUnicosNoCurso";
            $oquefiltra = "ai.alunoInscricaoData";
            $wherebuscar ="ai.diasUnicosNoCurso>0"; 
            $m_estatistica = "<b> qtd de dias únicos no curso, de todos os cursantes</b>";
            
        }

        if(in_array("mod_completos",$varr))
        {
            $oquebuscar="ai.modulosCompletos";
            $oquefiltra = "ai.alunoInscricaoData";
            #$wherebuscar ="ai.diasUnicosNoCurso>0"; 
            #$wherebuscar ="ai.alunoInscricaoDataCertificado is not null  and ai.alunoInscricaoDataCertificado!='0000-00-00' and ai.alunoInscricaoDataCertificado>ai.alunoInscricaoData"; 
            $wherebuscar ="ai.alunoInscricaoDataCertificado is not null  and ai.alunoInscricaoDataCertificado!='0000-00-00 00:00:00' and ai.alunoInscricaoDataCertificado>ai.alunoInscricaoData and (ai.modulosCompletos>0)"; 
            $m_estatistica = "<b> Módulos Completos</b>";
          
            
           
            
        }
        $gab_varx = array("nf_concluintes2"=>"alunoInscricaoNotaFinal","n1_concluintes2"=>"alunoInscricaoNota1Tent",
        "dias_ativos2"=>"diasUnicosNoCurso","dias_certificado2"=>"diasUnicosNoCurso","mod_completos2"=>"modulosCompletos");
       
        
        if($varx!="")
        {
            #$wherebuscar ="ai.diasUnicosNoCurso>0"; 
          
            
            $filtro_perfil=",ai.". $gab_varx[$varx]." as vperfil";
            #$wherebuscar="(ai.".$gab_varx[$varx].">0)"; 
        }
        
       
            $porc_amostra_filtro=@$_GET["p_amostra_filtro"];
            $porc_amostra_curso=@$_GET["p_amostra_curso"];
            if($porc_amostra_filtro<=1)
            {
                $s_geral = "SELECT count(1) as totrows $filtro_perfil from $tabelaBuscar2 where $oquefiltra
                between '$dtInicio' and '$dtFim' and $wherebuscar and ai.curso_cursoId!=$cId $sqlarea2 $nothaving ";
               
                
                $c_geral = $db->createCommand($s_geral);
                $rs_geral=$c_geral->queryAll();
                
               
                $totrows = @$rs_geral[0]["totrows"];
                
                  
                if($totrows==0)
                return "<p class='alert alert-info'>A busca não retornou nenhum registro </p>#DIVISAO#<b><p class='alert alert-warning'>Tente um novo filtro!</p</b>";
                
                $porc_amostra_filtro=floor($totrows*$porc_amostra_filtro);
                
            }
            if($porc_amostra_curso<=1)
            {
                $s_geral = "SELECT count(1) as totrows $filtro_perfil from $tabelaBuscar where $oquefiltra
                between '$dtInicio' and '$dtFim' and $wherebuscar and ai.curso_cursoId=$cId $having";
               
                #echo $s_geral;
                $c_geral = $db->createCommand($s_geral);
                $rs_geral=$c_geral->queryAll();
                $totrows2 = @$rs_geral[0]["totrows"];
                
                
               
                
                if($totrows2==0)
                return "<p class='alert alert-info'>A busca não retornou nenhum registro para o Curso </p>#DIVISAO#<b><p class='alert alert-warning'>Tente um novo filtro!</p</b>";
                
                $porc_amostra_curso=floor($totrows2*$porc_amostra_curso);
            
            }
            

            #dados da amostra
            $aleatorizar1 = $porc_amostra_filtro!=1? "order by RAND()":"";
            $aleatorizar2 = $porc_amostra_curso!=1? "order by RAND()":"";

            if($vobjetivos=='Correlacionar' && $grupo_filtro!="S")
            {
                $porc_amostra_filtro=$porc_amostra_curso;
            }

            $s_geral = "SELECT $oquebuscar as val $filtro_perfil from $tabelaBuscar2 where $oquefiltra
            between '$dtInicio' and '$dtFim' and $wherebuscar and ai.curso_cursoId!=$cId $sqlarea2 $having $aleatorizar1 limit 0,".$porc_amostra_filtro ;
        
            #echo $s_geral;
            $c_geral = $db->createCommand($s_geral);
            
            
            $rs_geral=$c_geral->queryAll();
            
            $group1 = $rs_geral;
            $ng1 = sizeof($group1);
            
            if($ng1==0)
            return "<p class='alert alert-info'>A busca não retornou nenhum registro </p>#DIVISAO#<b><p class='alert alert-warning'>Tente um novo filtro!</p</b>";
            
            
            
            $s_geral = "SELECT $oquebuscar as val $filtro_perfil from $tabelaBuscar where $oquefiltra
            between '$dtInicio' and '$dtFim' and $wherebuscar and ai.curso_cursoId=$cId $nothaving $aleatorizar2 limit 0,".$porc_amostra_curso;
            
            #echo "<br>".$s_geral;
            #exit();
           
            $c_geral = $db->createCommand($s_geral);
            $rs_geral=$c_geral->queryAll();
            $group2 = $rs_geral;
            
            $ng2 = sizeof($group2);
            if($ng2==0)
            return "<p class='alert alert-info'>A busca não retornou nenhum registro </p>#DIVISAO#<b><p class='alert alert-warning'>Tente um novo filtro!</p</b>";
            
            
            
            
            if($vobjetivos=="Comparar")    
            {
                
                $mannDados = $this->mannWhitneyUTest($group1, $group2);
                
                $datadata2=$mannDados['g2'];
                $datadata=$mannDados['g1'];
                
                $dif_mediana = "<b>IGUAL</b>";
                
                if($mannDados['g2'][2]!=$mannDados['g1'][2])
                {
                    if($mannDados['g2'][2]>$mannDados['g1'][2])
                    $dif_mediana = "<b>MAIOR</b> do que";
                    else
                    $dif_mediana = "<b>MENOR</b> do que";
                }
                
                


                $testatistica="Um teste de Mann-Whitney indicou que a $m_estatistica do grupo experimental (n=".$ng2.", mediana = ".$mannDados['g2'][2]."), foi $dif_mediana a do grupo controle (n=".$ng1." mediana = ".$mannDados['g1'][2]."), com <b style='text-decoraration:underline;'>Ua = ".$mannDados['u1'].",Ub = ".$mannDados['u2']." e p=".number_format($mannDados['pvalue'],6,".",".")."</b>";    
                return $this->chartBox([["data"=>$datadata,"name"=>"Grupo Controle"],["data"=>$datadata2,"name"=>"Grupo experimental"]], substr($m_estatistica,4,-5), "Teste Willcoxon-Mann-Whitney", 400).
                "#DIVISAO#".@$testatistica;
            

            }
            else{

                             
                    $descpontos="";
                    $testatistica="";
                  /*  if($grupo_filtro!="S")
                    {
                        $datadata = $this->regressaoLinear($group1, $group2,"",true);
                    }
                    else
                    {*/
                    
                    $datadatag = $this->regressaoLinear($group2,"",false);
                    
                    $datadata = $this->regressaoLinear($group1,$datadatag[3],true);
                    #$ccf = array("Coeficiente","R2","p-value");
                    
                    $ccf = array("Total de Pontos","Intercept","Coeficiente Beta","R2 Ajustado","p-value");
                    

                    #}
                 
                    $testatistica="<br><br><i class='ativo2'>Lúmina</i><br>"; 
                   
                        
                    $nn="";
                    foreach($ccf as $ind=> $v)
                    {
                        $nn = $ind==0?intval($datadata[2][$ind]):number_format($datadata[2][$ind],4,".",".");
                        $testatistica.="$v".": <b>" . $nn . "</b><br>";  
                    }
                    
                    $testatistica.="<br><br><i class='ativo_c'>Curso</i><br>"; 
                    $nn="";
                    foreach($ccf as $ind=> $v)
                    {
                        $nn = $ind==0?intval($datadatag[2][$ind]):number_format($datadatag[2][$ind],4,".",".");
                        $testatistica.="$v".": <b>" . $nn . "</b><br>";    
                    }

                    
                    
                    
                   
                    
                    
                    return $this->chartRegressaoLinear([["data"=>$datadata[0],"name"=>"Reta Lúmina","type"=>"line"],["data"=>$datadata[1],"name"=>"Pontos Lúmina","type"=>"scatter"],["data"=>$datadatag[0],"name"=>"Reta Curso","type"=>"line"],["data"=>$datadatag[1],"name"=>"Pontos Curso","type"=>"scatter"]
                ], $varx, $varr, "Regressão Linear", 400)."#DIVISAO#".$testatistica;
               
               /*     stat
                    
                    $datadata = $this->regressaoLogistica($group1);
                    $datadatag = $this->regressaoLogistica($group2,$datadata[2],true);
                    
                        #echo "<pre>";
                    $gab_varx = array("nf_concluintes"=>"Nota Final","n1_concluintes"=>"Nota 1 Tentativa",
                                    "dias_ativos"=>"Dias ativos no curso");
                              #print_r($datadata[0]);
                        #echo "<br>";
                        #print_r($datadata[1]);
                    $testatistica="<b>Regressão Logística</b> <br> Probabilidade de Certificação considerando <b>".$gab_varx[$varx]."</b>"; 
    
                    return $this->chartLine($datadata[0],[['name'=>'Lúmina','type'=>'line','data'=>$datadata[1]],
                    ['name'=>'Curso','type'=>'line','data'=>$datadatag[1]]],'Regressão Logística',400)."#DIVISAO#".$testatistica;
                }
                */
            
            }
             
       
            
            
        

        


    }
    
    if($_GET["acao"]=="itr")
    {
        
        
        if(!isset($_GET["dados"]) || @$_GET["dados"]=="")
        return '<b class="ativo_q">Sem Dados</b>';
        $dados = @$_GET["dados"];
        $adados = explode("#",$dados);
        
        array_pop($adados);
        //echo "<pre>";
        $maxh = array();
        foreach($adados as $ii => $vv)
        {
            $linha = explode(";",$vv);
            $maxh[]=$linha[4];
            if($ii==0)
            $q1=$linha[0];
        }
        $ini= -3;
        $fim = 3;
        $itensh = array();
        //$itensitr = array();
        #print_r($adados);
        #exit();
        foreach($adados as $ii => $vv)
        {
            $ini= -3;
            $fim = 3;
            $linha = explode(";",$vv);
            array_pop($linha);
            $descricao[]=$linha[0];
            if($linha[1]=="inf")
            $linha[1]=1000000000000000;
            
            while($ini<=$fim)
            {
                $itensh[intval($ii)][]=$ini;
                $itensitr[intval($ii)][]=number_format(($linha[3]) + ((1-$linha[3])/(1+2.718**(-1*$linha[2]*($ini-$linha[1])))),2);
                $ini+=0.1;
            }
        }
        
          
        
        
        foreach($adados as $ii =>$vv)
        {
            foreach($itensh[0] as $ii2 =>$vv2)
            {
            $vettodos[$ii][$ii2]["data"]=$vv2;
            $vettodos[$ii][$ii2]["total"]=$itensitr[intval($ii)][intval($ii2)];
            }
            
        }

        $descricao[0]="TRI - $q1";
        

        

        
        
        
        
        $g_posicao =  $this->indicador_grafico($vettodos,$descricao);
        return $g_posicao;
    }




        
    if(@$_GET["acao"]=="questionario")
    {
       

        $quiz = @$_GET["quiz"];
        $tent = @$_GET["tentativa"];
        
        if(isset($_GET["intervalo"]) && @$_GET["ajax"]=="S" && !in_array($posicao,array("11","12")))
        {
        $intervalo=$_GET["intervalo"];
        $db = \Yii::$app->db;
        $tipo="questionario";
        $cId=@$_GET["cid"];
       
        $opcao= $quiz."#".$tent;
        $posicao = $_GET["posicao"];
        $s_paginas = "SELECT * from pagina where paginaTipo='$tipo' and paginaFiltroInterno='$opcao' and paginaFiltro='$intervalo'
        and curso_cursoId=$cId and paginaPosicao='$posicao'";
        
        
        $c_paginas = $db->createCommand($s_paginas);
    
        $rs_paginas=$c_paginas->queryAll();
    
        return isset($rs_paginas[0]["paginaHTML"])?$rs_paginas[0]["paginaHTML"]:'<b class="ativo_q">Sem Dados</b>'."<script>"."$"."exibeAlt('#1'); </script>";
        
    
        }
    

   
        switch($posicao){
            case '11':
                $g_posicao = $this->filtrar_indicador([true],[],"lista_quiz",[$dtInicio,$dtFim],array($cId),['0','15'],[],[],'<h5 class="texto-com-icone"><i class="icone icone-livro"></i> <strong>Questionários / qtd questões:</strong></h5>');
                break; 
            case '12':
                    $g_posicao = $this->filtrar_indicador([true],[],"lista_quiz",[$dtInicio,$dtFim],array($cId),['15','100'],[],[],'-<h5 class="texto-com-icone"><i class="icone icone-livro"></i> <strong>Questionários:</strong></h5>');
                    break; 
            case '21':
                    $g_posicao = $this->filtrar_indicador([true],[],"lista_questao_tent",[$dtInicio,$dtFim],array($quiz,$tent),[],[],array('[filtroPlataforma]'),"");
                    break;
            case '32':
                $db = \Yii::$app->db;
                $sql_q = "SELECT q.questaoId,q.questaoOrdem,r.questaoTentativa,r.questao_questaoId,sum(case when r.resultado='gradedwrong' then 1 else 0 end ) as erros,
                sum(case when r.resultado in('gradedright','gradedpartial') then r.questaoRespostaNota else 0 end ) as acertos,
                sum(case when r.resultado in('gradedright','gradedpartial') then r.questaoRespostaNota else 0 end )/count(1) as media
                 FROM questaoresposta r
                join questao q on (q.questaoId=r.questao_questaoId)
                where r.questao_quiz_quizId=$quiz and r.resultado in ('gradedwrong','gradedright','gradedpartial')
                and r.questaoTentativa<4 and r.questaoRespostaData between '$dtInicio' and '$dtFim'
                group by q.questaoId,r.questaoTentativa
                order by q.questaoOrdem ASC,r.questaoTentativa ASC";
                 
                 
                 $command = $db->createCommand($sql_q);
                
                
                 $rs_geral=$command->queryAll();
                 $descricao=array();
                 $i=0;
                 $anterior=1;
                 $primeiro = true;
                 $q1="";
                 
                 $anterior=@$rs_geral[0]["questaoOrdem"];
                 foreach($rs_geral as $ind =>$item)
                 {  
                    if($primeiro)
                    {
                        $q1 = "Q".$item["questaoOrdem"].")";
                        $primeiro=false;
                    }
                    #echo $item["questaoOrdem"] ."-".$anterior."<br>";
                            
                        if($item["questaoOrdem"]!=$anterior)
                            $i++;
                        $vettodos[$i][]=array('data'=>$item["questaoTentativa"],'total'=>$item["media"]);
                        $descricao[$i]=$item["questaoOrdem"].")";
                        $anterior=$item["questaoOrdem"];
                 }
                 if(!isset($vettodos))
                {
                 echo $sql_q;
                 exit();
                }
                
                 $descricao[0]="Questões x Tentativas - $q1";
                 
                 
                 
                 
                 $g_posicao =  $this->indicador_grafico($vettodos,$descricao);
                
                
                
                
                break;


            }

            if(isset($_GET["intervalo"]) && !isset($_GET["ajax"]))
            {
            $db = \Yii::$app->db;
            #$del = "delete from pagina where paginaTipo='avancado' and paginaPosicao='$posicao' and paginaFiltro='".@$_GET["intervalo"]."' and curso_cursoId=$cId";
            $db->createCommand()->delete('pagina',['paginaTipo' => 'questionario',
            'paginaFiltro' => @$_GET["intervalo"],
            'paginaPosicao' => $posicao,
            'paginaFiltroInterno'=>@$quiz."#".$tent,
            'curso_cursoId' => $cId,])->execute();
            #echo $del;

            $db->createCommand()->insert('pagina', [
             'paginaHTML' => $g_posicao,
             'paginaTipo' => 'questionario',
             'paginaFiltro' => @$_GET["intervalo"],
             'paginaPosicao' => $posicao,
             'paginaFiltroInterno'=>@$quiz."#".$tent,
             'curso_cursoId' => $cId,
         ])->execute();
            }

            return $g_posicao;
        }

     
      




    if(@$_GET["acao"]=="avancado")
    {
        
        $sel_perfil=@$_GET["sel_perfil"];
        
        $having="";
        $nothaving="";
        $arr_sel_perfil[0]="";
        $vcontadores = @$_GET["v_contadores"];
        $acontadores = explode(",",$vcontadores);
        if($sel_perfil!="")
        {
            
            $asel = explode(";",$sel_perfil);
            $ahaving  =[];
            array_pop($asel);
                
            foreach($asel as $i =>$linha)
            {
                $alinha = explode(":",$linha);
                $ahaving[substr($alinha[0],0,-2)][]=$alinha[1]=="Não respondido"?"":$alinha[1];
                
            }
            
            $ind=0;
            foreach($ahaving as $i =>$v)
            {   
                
                if(sizeof($v)!=$acontadores[$ind])
                {
                    if(in_array("",$v))
                    $having.="(a.".$i." in('".implode("','",$v)."') or a.".$i." is null) and ";
                    else
                    $having.="a.".$i." in('".implode("','",$v)."') and ";
                    $nothaving.="a.".$i." not in('".implode("','",$v)."') and ";
                    
                    
                }
                $ind++;
            }
            
            $having = "and ". substr($having,0,-4);
            $nothaving = "and ". substr($nothaving,0,-4);
            $arr_sel_perfil[0]=$having;
        }
        
        #echo @$_GET["areas"];
        $sqlarea1="";
        $sqlarea2 = "";
        if(@$_GET["areas"]!="" or @$_GET["cargas"]!="")
        {
            if(@$_GET["areas"]!="")
            $sqlarea2=" and c.areaCurso_areaCursoId in (".$_GET["areas"].")";
            else
            $sqlarea2="";
            if(@$_GET["cargas"]!="")
            {
                $vcargas = explode(",",$_GET["cargas"]);
                $auxcarga= "";
                foreach($vcargas as $icarga =>$vcarga)
                {
                    $v =explode("-",$vcarga);
                    $auxcarga.="ch.cargahoraria between $v[0] and $v[1] or ";
                }
               
                $sqlarea2.=" and (".substr($auxcarga,0,-3).")";
            }
            $sqlarea1=" left join curso c on (c.cursoId=a.curso_cursoId) left join cursocarga ch on
            (ch.curso_cursoId=c.cursoId) ";
        }
        
        switch($posicao){
            case '11':
                
            $aux = $this->filtrar_indicador([false],[],"lista_perfil",[],array($dtInicio,$dtFim,$sqlarea2),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_genero"],$sqlarea1),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-genero"></i> <strong>Gênero</strong></p>');
        
            $g_posicao= $this->filtrar_indicador($aux[0],$aux[1],"lista_perfil",[],array($dtInicio,$dtFim," and a.curso_cursoId=".$cId),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_genero"],""),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-genero"></i> <strong>Gênero</strong></p>',false,false,true,false,true);
            break;
            case '12':
            $aux = $this->filtrar_indicador([false],[],"lista_perfil",[],array($dtInicio,$dtFim,$sqlarea2),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_idade"],$sqlarea1),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-faixa-etaria"></i> <strong>Faixa Etária</strong></p>');
            
            $g_posicao= $this->filtrar_indicador($aux[0],$aux[1],"lista_perfil",[],array($dtInicio,$dtFim," and a.curso_cursoId=".$cId),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_idade"],""),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-faixa-etaria"></i> <strong>Faixa Etária</strong></p>',false,false,true,false,true);
            break;
            case '13':
            $aux = $this->filtrar_indicador([false],[],"lista_perfil",[],array($dtInicio,$dtFim,$sqlarea2),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_escolaridade"],$sqlarea1),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-escolaridade"></i> <strong>Escolaridade</strong></p>');
            
            $g_posicao= $this->filtrar_indicador($aux[0],$aux[1],"lista_perfil",[],array($dtInicio,$dtFim," and a.curso_cursoId=".$cId),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_escolaridade"],""),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-escolaridade"></i> <strong>Escolaridade</strong></p>',false,false,true,false,true);
            break;
            case '14':
            
            $aux = $this->filtrar_indicador([false],[],"lista_perfil",[],array($dtInicio,$dtFim,$sqlarea2),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_racial"],$sqlarea1),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-racial"></i> <strong>Identificação Racial</strong></p>');
            
            $g_posicao= $this->filtrar_indicador($aux[0],$aux[1],"lista_perfil",[],array($dtInicio,$dtFim," and a.curso_cursoId=".$cId),[],array('alunoinscricao','aluno_alunoId',$minhasConfig["field_racial"],""),array('[filtroPlataforma]','[filtroData]'),'<p class="texto-com-icone"><i class="icone icone-racial"></i> <strong>Identificação Racial</strong></p>',false,false,true,false,true);
            break;
            
            case '21':
            $_GET["opcao"]="mês";    
            $insc1=$this->filtrar_indicador([false],[],"inscricoes",array($dtInicio,$dtFim,$sqlarea2),[],$arr_sel_perfil,["EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoData)"],["[filtroCurso]"],"Inscrições - Lúmina",false,true);
            $g_posicao= $this->filtrar_indicador($insc1[0],$insc1[1],"inscricoes",array($dtInicio,$dtFim,""),[$cId],$arr_sel_perfil,["EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoData)"],[],"Inscrições - curso",false,true);
            break;
            case '22':
            $_GET["opcao"]="mês";
            $cert1=$this->filtrar_indicador([false],[],"certificados",array($dtInicio,$dtFim,$sqlarea2),[],$arr_sel_perfil,["EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoDataCertificado)"],["[filtroCurso]"],"Certificados - Lúmina",false,true);
            $g_posicao= $this->filtrar_indicador($cert1[0],$cert1[1],"certificados",array($dtInicio,$dtFim,""),[$cId],$arr_sel_perfil,["EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoDataCertificado)"],[],"Certificados - curso",false,true);
            break;
            case '31':
                $dias1=$this->filtrar_indicador([false],[],"dias_no_curso",array($dtInicio,$dtFim,$sqlarea2),[],$arr_sel_perfil,[],["[filtroCurso]"],"Dias ativos no curso - Lúmina",false,false,true);
                $g_posicao= $this->filtrar_indicador($dias1[0],$dias1[1],"dias_no_curso",array($dtInicio,$dtFim,""),[$cId],$arr_sel_perfil,[],[],"Dias ativos no curso - curso",false,false,true);
            break;
           
            case '32':
                $dias2=$this->filtrar_indicador([false],[],"dias_para_certificado",array($dtInicio,$dtFim,$sqlarea2),[],$arr_sel_perfil,[],["[filtroCurso]"],"Dias para obter certificado - Lúmina",false,false,true);
                $g_posicao= $this->filtrar_indicador($dias2[0],$dias2[1],"dias_para_certificado",array($dtInicio,$dtFim,""),[$cId],$arr_sel_perfil,[],[],"Dias para obter certificado  - curso",false,false,true);
            break;
    
            case '41':
                
                $ativ1=$this->filtrar_indicador([false],[],"atividades_concluidas",array($dtInicio,$dtFim),[$cId,$arr_sel_perfil[0]],[],[],["[filtroField]"],"Atividades concluídas - Outras",false,false,false,true);
                
                $ativ2=$this->filtrar_indicador([false],[],"atividades_concluidas",array($dtInicio,$dtFim),[$cId,$arr_sel_perfil[0]],[],["'url'"],["[filtroPlataforma]"],"Vídeos/Links",false,false,false,true);
                
                $ativ3=$this->filtrar_indicador([false],[],"atividades_concluidas",array($dtInicio,$dtFim),[$cId,$arr_sel_perfil[0]],[],["'resource'"],["[filtroPlataforma]"],"Arquivos/Páginas",false,false,false,true);
        
                $ativ4=$this->filtrar_indicador([false],[],"atividades_concluidas",array($dtInicio,$dtFim),[$cId,$arr_sel_perfil[0]],[],["'forum'"],["[filtroPlataforma]"],"Fóruns",false,false,false,true);
                
                
                
                

                $g_posicao =$this->filtrar_indicador(array($ativ1[0][0],$ativ2[0][0],$ativ3[0][0],$ativ4[0][0]),array($ativ1[1][0],$ativ2[1][0],$ativ3[1][0],$ativ4[1][0]),
            "atividades_concluidas",array($dtInicio,$dtFim),[$cId,$arr_sel_perfil[0]],[],["'quiz'"],["[filtroPlataforma]"],"Questionários",false,false,false,true);
            break;
    
        }
        

        if(isset($_GET["intervalo"]) && !isset($_GET["ajax"]))
        {
        $db = \Yii::$app->db;
        #$del = "delete from pagina where paginaTipo='avancado' and paginaPosicao='$posicao' and paginaFiltro='".@$_GET["intervalo"]."' and curso_cursoId=$cId";
        $db->createCommand()->delete('pagina',['paginaTipo' => 'avancado',
        'paginaFiltro' => @$_GET["intervalo"],
        'paginaPosicao' => $posicao,
        'curso_cursoId' => $cId,])->execute();
        #echo $del;
        $db->createCommand()->insert('pagina', [
         'paginaHTML' => $g_posicao,
         'paginaTipo' => 'avancado',
         'paginaFiltro' => @$_GET["intervalo"],
         'paginaPosicao' => $posicao,
         'curso_cursoId' => $cId,
     ])->execute();
        }


        return $g_posicao;

    }

    if(@$_GET["acao"]=="novo")
    {

        #echo @$_GET["areas"];

        if(in_array($posicao,array("11","21","22","23","24")) && isset($_GET["ajax"]))
        {
        $intervalo=$_GET["intervalo"];
        $db = \Yii::$app->db;
        $tipo="novo";
        
       
        
        $s_paginas = "SELECT * from pagina where paginaTipo='$tipo' and paginaFiltro='$intervalo' and paginaPosicao='$posicao'";
        
            
        $c_paginas = $db->createCommand($s_paginas);
    
        $rs_paginas=$c_paginas->queryAll();
    
        return @$rs_paginas[0]["paginaHTML"];
            
        }


        $sqlarea1="";
        $sqlarea2 = "";
        if(@$_GET["areas"]!="" or @$_GET["cargas"]!="")
        {
            if(@$_GET["areas"]!="")
            $sqlarea2=" and c.areaCurso_areaCursoId in (".$_GET["areas"].")";
            else
            $sqlarea2="";
            if(@$_GET["cargas"]!="")
            {
                $vcargas = explode(",",$_GET["cargas"]);
                $auxcarga= "";
                foreach($vcargas as $icarga =>$vcarga)
                {
                    $v =explode("-",$vcarga);
                    $auxcarga.="ch.cargahoraria between $v[0] and $v[1] or ";
                }
               
                $sqlarea2.=" and (".substr($auxcarga,0,-3).")";
            }
            $sqlarea1=" left join curso c on (c.cursoId=a.curso_cursoId) left join cursocarga ch on
            (ch.curso_cursoId=c.cursoId) ";
        }
        


   switch ($posicao) {

    case '11':
        # code...
        $g_posicao = $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,""),[],[],array('aluno','alunoId',$minhasConfig["field_genero"],""),array('[filtroPlataforma]','[filtroGroup]','[filtroCurso]'),"Novos Usuários:",false,false,false);
        break;
    case '21':
        $g_posicao = $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,""),[],array($dtInicio,$dtFim,""),array('aluno','alunoId',$minhasConfig["field_genero"],""),array('[filtroPlataforma]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-genero"></i> <strong>Gênero</strong></p>',false,false,true,false);
        break;
        # code...
    case '22':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,""),[],array($dtInicio,$dtFim,""),array('aluno','alunoId',$minhasConfig["field_idade"],""),array('[filtroPlataforma]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-faixa-etaria"></i> <strong>Faixa Etária</strong></p>',false,false,true,false);
        break;
    case '23':
      $g_posicao = $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,""),[],array($dtInicio,$dtFim,""),array('aluno','alunoId',$minhasConfig["field_escolaridade"],""),array('[filtroPlataforma]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-escolaridade"></i> <strong>Escolaridade</strong></p>',false,false,true,false);
      break;
    case '24':
        $g_posicao =  $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,""),[],array($dtInicio,$dtFim,""),array('aluno','alunoId',$minhasConfig["field_racial"],""),array('[filtroPlataforma]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-racial"></i> <strong>Identificação Racial</strong></p>',false,false,true,false);
        break;
    case '31':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,$sqlarea2),[],array($dtInicio,$dtFim,$sqlarea2),array('alunoinscricao','aluno_alunoId',$minhasConfig["field_genero"],$sqlarea1),array('[filtroData]','[filtroGroup]','[filtroCurso]'),"Certificados Emitidos:",false,false,false);
        break;
    case '41':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,$sqlarea2),[],array($dtInicio,$dtFim,$sqlarea2),array('alunoinscricao','aluno_alunoId',$minhasConfig["field_genero"],$sqlarea1),array('[filtroData]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-genero"></i> <strong>Gênero</strong></p>',false,false,true);
        break;
    case '42':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,$sqlarea2),[],array($dtInicio,$dtFim,$sqlarea2),array('alunoinscricao','aluno_alunoId',$minhasConfig["field_idade"],$sqlarea1),array('[filtroData]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-faixa-etaria"></i> <strong>Faixa Etária</strong></p>',false,false,true);
        break;
    case '43':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,$sqlarea2),[],array($dtInicio,$dtFim,$sqlarea2),array('alunoinscricao','aluno_alunoId',$minhasConfig["field_escolaridade"],$sqlarea1),array('[filtroData]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-escolaridade"></i> <strong>Escolaridade</strong></p>',false,false,true);
        break;
    case '44':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_perfil",array($dtInicio,$dtFim,$sqlarea2),[],array($dtInicio,$dtFim,$sqlarea2),array('alunoinscricao','aluno_alunoId',$minhasConfig["field_racial"],$sqlarea1),array('[filtroData]','[filtroCurso]'),'<p class="texto-com-icone"><i class="icone icone-racial"></i> <strong>Identificação Racial</strong></p>',false,false,true);
        break;
    case '51':
        $_GET["opcao"]="mês";
        $g_posicao=$this->filtrar_indicador([true],[],"inscricoes",array($dtInicio,$dtFim,$sqlarea2),[],[""],['EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoData)'],["[filtroCurso]","[filtroPlataforma]"],"Inscrições - ",false,true);
        break;
    case '52':
        $_GET["opcao"]="mês";
        $g_posicao=$this->filtrar_indicador([true],[],"certificados",array($dtInicio,$dtFim,$sqlarea2),[],[""],['EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoDataCertificado)'],["[filtroCurso]"],"Certificados - ",false,true);
        break;
    case '61':
        $g_posicao= $this->filtrar_indicador([true],[],"lista_inscritos_curso",array($dtInicio,$dtFim,$sqlarea2),[],[],[],array('[filtroPlataforma]','[filtroCurso]'),"<h5><strong>Top Inscrições</strong></h5>",false,false,false);
        break;
    case '62':
        $g_posicao = $this->filtrar_indicador([true],[],"lista_certificados_no_curso",array($dtInicio,$dtFim,$sqlarea2),[],[],[],array('[filtroPlataforma]','[filtroCurso]'),"<h5><strong>Cursos com mais certificações</strong></h5>",false,false,false);
        break;
    
   }
   if(isset($_GET["intervalo"]) && !isset($_GET["ajax"]))
   {
   $db = \Yii::$app->db;
   #$del = "delete from pagina where paginaTipo='novo' and paginaPosicao='$posicao' and paginaFiltro='".@$_GET["intervalo"]."'";
    $db->createCommand()->delete('pagina',['paginaTipo' => 'novo',
    'paginaFiltro' => @$_GET["intervalo"],
    'paginaPosicao' => $posicao,])->execute();
   
    $db->createCommand()->insert('pagina', [
    'paginaHTML' => $g_posicao,
    'paginaTipo' => 'novo',
    'paginaFiltro' => @$_GET["intervalo"],
    'paginaPosicao' => $posicao,
])->execute();
   }
   return $g_posicao;
}
    //novos alunos
    
   
    
   
}

public function actionSample()
{
   

    if(isset($_GET["intervalo"]) && @$_GET["ajax"]=="S")
    {
    $intervalo=$_GET["intervalo"];
    $db = \Yii::$app->db;
    $tipo="grafico-avancado";
    
    if(!isset($_GET["cid"]) or @$_GET["cid"]=="")
    {
        $cId=0;
        $tipo = "grafico-novo";
    }else
    {
        $cId=@$_GET["cid"];
    }
    $opcao= $_GET["opcao"];
    $posicao = $_GET["posicao"];
    $s_paginas = "SELECT * from pagina where paginaTipo='$tipo' and paginaFiltroInterno='$opcao' and paginaFiltro='$intervalo'
    and curso_cursoId=$cId and paginaPosicao='$posicao'";
    
        
    $c_paginas = $db->createCommand($s_paginas);

    $rs_paginas=$c_paginas->queryAll();

    return @$rs_paginas[0]["paginaHTML"];
    

    }


  
    #$data = \Yii::$app->request->post();
      
    $dtInicio=$_GET["dti"];
    $dtFim=$_GET["dtf"];
    
    $sqlarea1="";
    $sqlarea2 = "";
    if(@$_GET["areas"]!="" or @$_GET["cargas"]!="")
        {
            if(@$_GET["areas"]!="")
            $sqlarea2=" and c.areaCurso_areaCursoId in (".$_GET["areas"].")";
            else
            $sqlarea2="";
            if(@$_GET["cargas"]!="")
            {
                $vcargas = explode(",",$_GET["cargas"]);
                $auxcarga= "";
                foreach($vcargas as $icarga =>$vcarga)
                {
                    $v =explode("-",$vcarga);
                    $auxcarga.="ch.cargahoraria between $v[0] and $v[1] or ";
                }
               
                $sqlarea2.=" and (".substr($auxcarga,0,-3).")";
            }
            $sqlarea1=" left join curso c on (c.cursoId=a.curso_cursoId) left join cursocarga ch on
            (ch.curso_cursoId=c.cursoId) ";
        }
    
    if(!isset($_GET["cid"]) or @$_GET["cid"]=="")
    {
        
        
        if($_GET["indicador"]=="inscricoes")
        {
        if($_GET["opcao"]=="mês")    
        $search = $this->filtrar_indicador([true],[],"inscricoes",array($dtInicio,$dtFim,$sqlarea2),[],[""],["EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoData)"],["[filtroCurso]"],"Inscrições - ",false,true);
        if($_GET["opcao"]=="dia")    
        $search = $this->filtrar_indicador([true],[],"inscricoes",array($dtInicio,$dtFim,$sqlarea2),[],[""],['DATE(ai.alunoInscricaoData)'],["[filtroCurso]"],"Inscrições - ",true,true);
        if($_GET["opcao"]=="ano")
        $search = $this->filtrar_indicador([true],[],"inscricoes",array($dtInicio,$dtFim,$sqlarea2),[],[""],["year(ai.alunoInscricaoData)"],["[filtroCurso]"],"Inscrições Lúmina - ",false,true);
        }
        if($_GET["indicador"]=="certificados")
        {
            
        if($_GET["opcao"]=="mês")    
        $search = $this->filtrar_indicador([true],[],"certificados",array($dtInicio,$dtFim,$sqlarea2),[],[""],["EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoDataCertificado)"],["[filtroCurso]"],"Certificados - ",false,true);
        if($_GET["opcao"]=="dia")    
        $search =$this->filtrar_indicador([true],[],"certificados",array($dtInicio,$dtFim,$sqlarea2),[],[""],['DATE(ai.alunoInscricaoDataCertificado)'],["[filtroCurso]"],"Certificados - ",true,true);
        if($_GET["opcao"]=="ano")
        $search = $this->filtrar_indicador([true],[],"certificados",array($dtInicio,$dtFim,$sqlarea2),[],[""],['year(ai.alunoInscricaoDataCertificado)'],["[filtroCurso]"],"Certificados - ",false,true);
        }
    }
    else
    {
        
        $sel_perfil=@$_GET["sel_perfil"];

        $having="";
        $nothaving="";
        $arr_sel_perfil[0]="";
        $vcontadores = @$_GET["v_contadores"];
        $acontadores = explode(",",$vcontadores);
        if($sel_perfil!="")
        {
            $asel = explode(";",$sel_perfil);
            $ahaving  =[];
            array_pop($asel);
                
            foreach($asel as $i =>$linha)
            {
                $alinha = explode(":",$linha);
                $ahaving[substr($alinha[0],0,-2)][]=$alinha[1]=="Não respondido"?"":$alinha[1];
                
            }
            
            $ind=0;
            foreach($ahaving as $i =>$v)
            {   
                
                if(sizeof($v)!=$acontadores[$ind])
                {
                    if(in_array("",$v))
                    $having.="(a.".$i." in('".implode("','",$v)."') or a.".$i." is null) and ";
                    else
                    $having.="a.".$i." in('".implode("','",$v)."') and ";
                    $nothaving.="a.".$i." not in('".implode("','",$v)."') and ";
                    
                }
                $ind++;
            }
            
            $having = "and ". substr($having,0,-4);
            $nothaving = "and ". substr($nothaving,0,-4);
            $arr_sel_perfil[0]=$having;
        }
        







        $cId = $_GET["cid"];
        $ind = $_GET["indicador"];
        $op = $_GET["opcao"];
        $dados["inscricoes"]["dia"]=array('Inscrições ','DATE(ai.alunoInscricaoData)',true);
        $dados["inscricoes"]["mês"]=array('Inscrições ',"EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoData)",false);
        $dados["inscricoes"]["ano"]=array('Inscrições ','year(ai.alunoInscricaoData)',false);
        
        $dados["certificados"]["dia"]=array('Certificados ','DATE(ai.alunoInscricaoDataCertificado)',true);
        $dados["certificados"]["mês"]=array('Certificados ',"EXTRACT(YEAR_MONTH FROM ai.alunoInscricaoDataCertificado)",false);
        $dados["certificados"]["ano"]=array('Certificados ','year(ai.alunoInscricaoDataCertificado)',false);

        
        $insc1 = $this->filtrar_indicador([false],[],$ind,array($dtInicio,$dtFim,$sqlarea2),[],$arr_sel_perfil,[$dados[$ind][$op][1]],["[filtroCurso]"],$dados[$ind][$op][0]." - Lúmina",$dados[$ind][$op][2],true);
        $search = $this->filtrar_indicador($insc1[0],$insc1[1],$ind,array($dtInicio,$dtFim,""),[$cId],$arr_sel_perfil,[$dados[$ind][$op][1]],[],"curso",$dados[$ind][$op][2],true);
    }

    if(isset($_GET["intervalo"]) && !isset($_GET["ajax"]))
    {
    $db = \Yii::$app->db;
    $cId=!isset($_GET["cid"])?0:@$_GET["cid"];
    $posicao = @$_GET["posicao"];
    
    $db->createCommand()->delete('pagina',['paginaTipo' => 'grafico-'.@$_GET["acao"],
    'paginaFiltro' => @$_GET["intervalo"],
    'paginaPosicao' => $posicao,
    'paginaFiltroInterno'=>@$_GET["opcao"],
    'curso_cursoId'=>$cId,])->execute();
    #echo $del;
    $db->createCommand()->insert('pagina', [
     'paginaHTML' => $search,
     'paginaTipo' => 'grafico-'.@$_GET["acao"],
     'paginaFiltro' => @$_GET["intervalo"],
     'paginaPosicao' => $posicao,
     'paginaFiltroInterno'=>@$_GET["opcao"],
     'curso_cursoId'=>$cId,
 ])->execute();
    }
    
    
    
    return $search;

   
  //}
}
    public function actionNovo()
    {
        
        $minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        $datas = self::pegaDatas();
        
        $dtInicio = $datas[0];
        $dtFim = $datas[1];
        $debug=false;
        
        
        $v =  $this->render('novo', [
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        
    ]);   
     
        return $v;
        

    }

    public function actionAvancado()
    {
        
        $minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        
        $datas = self::pegaDatas();
        $dtInicio=$datas[0];
        $dtFim=$datas[1];
        $cId=$datas[2];
        $cDesc = $datas[3];
        $debug=false;
       
        return $this->render('avancado', [
        'curso_descricao'=>$cDesc,
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug
    ]);   
    }

    public function actionEstatistica()
    {
        
        $minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        
        $datas = self::pegaDatas();
        $dtInicio=$datas[0];
        $dtFim=$datas[1];
        $cId=$datas[2];
        $cDesc = $datas[3];
        $debug=false;

        $db = \Yii::$app->db;
        
        
        
        $s_genero = "SELECT *  from info where infoField=".$minhasConfig["field_genero"];
        
        
        $c_genero = $db->createCommand($s_genero);

        $rs_genero=$c_genero->queryAll();
        
        

        $ret_infos[]=$rs_genero;

        
        $s_racial = "SELECT *  from info where infoField=".$minhasConfig["field_racial"];
        
        
        $c_racial = $db->createCommand($s_racial);

        $rs_racial=$c_racial->queryAll();
        
        $ret_infos[]=$rs_racial;
        #pega itens escolaridade
        
        
        $s_escola = "SELECT *  from info where infoField=".$minhasConfig["field_escolaridade"];
        
        $c_escola = $db->createCommand($s_escola);

        $rs_escola=$c_escola->queryAll();
        
        $ret_infos[]=$rs_escola;

        # pega itens idade
        $s_idade = "SELECT *  from info where infoField=".$minhasConfig["field_idade"];
        
        
        
        $c_idade = $db->createCommand($s_idade);

        $rs_idade=$c_idade->queryAll();
        
        $ret_infos[]=$rs_idade;


        #dados totais /amostra
        
        $l_areas = self::buscaAreas();
        $area_sel = [];
        if(isset($_GET["area"]))
        {
            $area_sel=$_GET["area"];
        }
        $areaval="";
        foreach($area_sel as $iarea =>$varea)
            $areaval.="$varea,";
        $areaval=substr($areaval,0,-1);

        if(sizeof($area_sel)==sizeof($l_areas))
        $areaval="";

        $area_cargas = [];
        if(isset($_GET["carga"]))
        {
            $area_cargas=$_GET["carga"];
        }
        $cargas="";
        foreach($area_cargas as $icarga =>$vcarga)
            $cargas.="$vcarga,";
        $cargas=substr($cargas,0,-1);

        if(sizeof($area_cargas)==3)
            $cargas="";

        
        
        $sqlarea1="";
        $sqlarea2 = "";
        if(@$areaval!="" or @$cargas!="")
        {
            if(@$areaval!="")
            $sqlarea2=" and c.areaCurso_areaCursoId in (".$areaval.")";
            else
            $sqlarea2="";
            if(@$cargas!="")
            {
                $vcargas = explode(",",$cargas);
                $auxcarga= "";
                foreach($vcargas as $icarga =>$vcarga)
                {
                    $v =explode("-",$vcarga);
                    $auxcarga.="ch.cargahoraria between $v[0] and $v[1] or ";
                }
               
                $sqlarea2.=" and (".substr($auxcarga,0,-3).")";
            }
            $sqlarea1=" left join curso c on (c.cursoId=ai.curso_cursoId) left join cursocarga ch on
            (ch.curso_cursoId=c.cursoId) ";
        }
        
        $wherebuscar ="alunoInscricaoDataCertificado is not null  and alunoInscricaoDataCertificado!='0000-00-00 00:00:00'"; 
        $s_geral = "SELECT count(1) as totrows from alunoinscricao ai $sqlarea1 where  ai.alunoInscricaoDataCertificado
        between '$dtInicio' and '$dtFim' and $wherebuscar $sqlarea2";
        
         
        $c_geral = $db->createCommand($s_geral);
        $rs_geral=$c_geral->queryAll();
        $totrows = @$rs_geral[0]["totrows"];
        

        $s_geral = "SELECT count(1) as totrows from alunoinscricao ai where ai.alunoInscricaoDataCertificado
        between '$dtInicio' and '$dtFim' and $wherebuscar and ai.curso_cursoId=$cId";
        
        $c_geral = $db->createCommand($s_geral);
        $rs_geral=$c_geral->queryAll();
        $totrows2 = $rs_geral[0]["totrows"];
        
        $csscor2=$totrows2==0?'color:red !important':'';
        $csscor=$totrows==0?'color:red !important':'';
        $alerta_dados= "<div align='center' class='alert alert-info'><strong>Registros</strong><br>";
        $alerta_dados.= "<span style='".$csscor2."'>Curso Concluintes: <strong>$totrows2</strong><br></span>";
        $alerta_dados.= "<span style='".$csscor."'>Lúmina Concluintes: <strong>$totrows</strong><br></span></div>";
        
        
       


       
        return $this->render('estatistica', [
        'curso_descricao'=>$cDesc,
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        'ret_infos'=>$ret_infos,
        'totcurso'=>$totrows2,
        'totlumina'=>$totrows, 
    ]);   
    }


    public function actionQuestionario()
    {
        $minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        
        $datas = self::pegaDatas();
        $dtInicio=$datas[0];
        $dtFim=$datas[1];
        $cId=$datas[2];
        $cDesc = $datas[3];
        $debug=false;
        $model=new LoginForm();
        
        /*$posicao_indicador["21"]= "<b><i class='btn-sm btn' style='background-color:#57B6EC;color:white;;padding:2px !important;font-size:12px'>curso </i> ".$cDesc."</b><br>";
        $posicao_indicador["21"].= "<i class='btn-sm btn' style='background-color:#4D4D4D;color:white;padding:2px !important;font-size:12px'>LÚMINA </i> <i class='opacity-75 btn-sm btn btn-outline-secondary' style='padding:2px !important;font-size:12px;margin-left:3px !important;margin-bottom:5px'> data: $dtInicio a $dtFim x</i><br>";
        $posicao_indicador["11"]= "<a href='?r=lumilab/avancado&cid=$cId' class='btn btn-sm' style='background-color:#53BBE6 !important;color:white'>Avançado</a>";
        $posicao_indicador["12"]= "<a href='?r=lumilab/questionario&cid=$cId&dt=todo' class='btn btn-sm' style='background-color:#4682B4 !important;color:white'>Questionários</a>";
        $posicao_indicador["13"]= "<button class='btn btn-sm disabled' style='background-color:#53BBE6 !important;color:white'>Fóruns</button>";
        $posicao_indicador["14"]= "<button class='btn btn-sm disabled' style='background-color:#53BBE6 !important;color:white'>Estatística</button>";
        */
        
        //$posicao_indicador["32"]= $this->filtrar_indicador([true],[],"certificados",array($dtInicio,$dtFim),[],[],[],[],"",true,true);
        
        return $this->render('questionario', [
        'model'=>$model,
        'curso_descricao'=>$cDesc,
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        
    ]);   
    }



    public function actionForum()
    {
        $minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa","field_genero","field_racial","field_escolaridade","field_idade"));
        
        $datas = self::pegaDatas();
        $dtInicio=$datas[0];
        $dtFim=$datas[1];
        $cId=$datas[2];
        $cDesc = $datas[3];
        $debug=false;
        $model=new LoginForm();
        
        return $this->render('forum', [
        'model'=>$model,
        'curso_descricao'=>$cDesc,
        'dtInicio'=>$dtInicio,
        'dtFim'=>$dtFim,
        'debug'=>$debug,
        
    ]);   
    }









function filtrar_indicador($vettodos,$descricoes,$rotulo,$filtroData,$filtroCurso=[],$filtroPlataforma=[],$filtroField=[],$filtrosExcluidos=[],$descricao="",$ehData=false,$exibeRodape=false,$porcentagem=false,$vertical=false,$check=false)
    {
        
        $db = \Yii::$app->db;
        
        $todosRS = array();
        $novoCaso = array();
        $sql_indicador = "select  * from indicador where indicadorRotulo='$rotulo'";
        
        $command_indicador = $db->createCommand($sql_indicador);

        $rs_indicador=$command_indicador->queryAll();

        
        
        $tFiltroData = $rs_indicador[0]["filtroData"];
        foreach($filtroData as $idata =>$vdata)
        {
            $tFiltroData=str_replace("[d".($idata+1)."]",$vdata,$tFiltroData);
        }

        $tFiltroCurso = $rs_indicador[0]["filtroCurso"];
        foreach($filtroCurso as $idata =>$vdata)
        {
            $tFiltroCurso=str_replace("[c".($idata+1)."]",$vdata,$tFiltroCurso);
        }
        
        // customiza plataforma
        $tFiltroPlataforma = $rs_indicador[0]["filtroPlataforma"];
        foreach($filtroPlataforma as $idata =>$vdata)
        {
            $tFiltroPlataforma=str_replace("[p".($idata+1)."]",$vdata,$tFiltroPlataforma);
        }

           // customiza plataforma
           $tFiltroField = $rs_indicador[0]["filtroField"];
           foreach($filtroField as $idata =>$vdata)
           {
               $tFiltroField=str_replace("[f".($idata+1)."]",$vdata,$tFiltroField);
           }
        
            $tFiltroGroup = $rs_indicador[0]["filtroGroup"];
        
        
        if(!in_array("[filtroData]",$filtrosExcluidos))
            $sql_geral = str_replace("[filtroData]",$tFiltroData,$rs_indicador[0]["indicadorSQL"]);
        else
            $sql_geral = str_replace("[filtroData]","",$rs_indicador[0]["indicadorSQL"]);

        if(!in_array("[filtroCurso]",$filtrosExcluidos))
            $sql_geral = str_replace("[filtroCurso]",$tFiltroCurso,$sql_geral);
        else
            $sql_geral = str_replace("[filtroCurso]","",$sql_geral); 
        
            

        if(!in_array("[filtroPlataforma]",$filtrosExcluidos))
            $sql_geral = str_replace("[filtroPlataforma]",$tFiltroPlataforma,$sql_geral);
        else
            $sql_geral = str_replace("[filtroPlataforma]","",$sql_geral);

        if(!in_array("[filtroField]",$filtrosExcluidos))
            $sql_geral = str_replace("[filtroField]",$tFiltroField,$sql_geral);
        else
            $sql_geral = str_replace("[filtroField]","",$sql_geral);  
            
        if(!in_array("[filtroGroup]",$filtrosExcluidos))
            $sql_geral = str_replace("[filtroGroup]",$tFiltroGroup,$sql_geral);
        else
            $sql_geral = str_replace("[filtroGroup]","",$sql_geral);  

        

        
        
        
        $aconfig = explode("[config_",$sql_geral);
        
        //customiza config
        if(sizeof($aconfig)>1)
        {
            $vetconfig = [];
            foreach($aconfig as $ii => $vconfig)
            {
                
                
                $aEhconfig = explode("]",$vconfig);                
                if(sizeof($aEhconfig)>1){
                    $vetconfig[]=$aEhconfig[0];
                
                }
            }
            
            $configs= self::buscaConfig($vetconfig);
            foreach($vetconfig as $ii => $vconfig)
            {   
               
                $sql_geral = str_replace("]","",$sql_geral);
                $sql_geral = str_replace("[config_".$vconfig,$configs[$vconfig],$sql_geral);
                
            }
        }
        

        
        
        
        if($rotulo=="lista_quiz" or $rotulo=="inscricoes")
        {
            //print_r($vettodos);
          #print_r($filtroCurso);
          #echo $sql_geral;
          #print_r($filtroPlataforma);
         #exit();
          
          
        
        }
        
        
       
       $command = $db->createCommand($sql_geral);

       $rs_geral=$command->queryAll();
       

       
       if(@$vettodos[0]==false)
       {
        if(sizeof($rs_geral)==0)
            $retret[0][]=array(0=>array(true));
        else
        $retret[0][]=$rs_geral;
        $retret[1][]=$descricao!=""?$descricao:$rs_indicador[0]["indicadorDescricao"];
        
        
        return $retret;
       
       }
       else
        {     
                if($vettodos[0]===true)
                    $vettodos=[];
                
                    
               
            
        }

       
        
    
       
       
        $vettodos[] = $rs_geral;
       
        $descricoes[] = $descricao!=""?$descricao:$rs_indicador[0]["indicadorDescricao"];
       
       


       if($rs_indicador[0]["indicadorTipo"]=="texto")
       {
          
          return $this->indicador_texto($vettodos,$descricoes[0]!=""?$descricoes[0]:$rs_indicador[0]["indicadorDescricao"],$porcentagem,$check);
       }
       if($rs_indicador[0]["indicadorTipo"]=="questoes")
        {
            
            if(sizeof($vettodos[0])==0)
                return '<b>Sem dados</b>';
            
            return $this->indicador_questoes($vettodos,$descricoes[0]!=""?$descricoes[0]:$rs_indicador[0]["indicadorDescricao"],$filtroCurso[0],$filtroCurso[1],$filtroData[0],$filtroData[1]);
            
            
        }
       else  
       {
       return $this->indicador_grafico($vettodos,$descricoes,$ehData,$exibeRodape,$porcentagem,$vertical);
       }
       
     
    }

function indicador_questoes($rs_geral,$cabecalho,$dtInicio,$dtFim,$qid=0,$tent=1)
{
    
    $retorno='';
    $vetspan=array("um","dois","tres");    
   /* $rs_compara=array();
    foreach($rs_geral[0] as $ind =>$item)
        {
            foreach($item as $valor)
                $rs_compara[$ind]=$item;
          
        }
    $rs_geral=$rs_compara;
    */
    $rs_geral = $rs_geral[0];
    
    $temcompara = true;
    $totalgeral=array("dois"=>0,"tres"=>0);
    
    
    
        $retorno = "<div class='ul-listagem'>";
        $retorno.="<h5 class='li-cabecalho'>". $cabecalho . @$rs_geral[0]["quizDescricao"]."</h5>";

        
        $db = \Yii::$app->db;
        
        $sql_q = "SELECT r.questaoTentativa,count(distinct r.aluno_alunoId) as total FROM questaoresposta r 
        join questao q on (q.questaoId=r.questao_questaoId and r.questao_quiz_quizId=q.quiz_quizId)
        where r.questao_quiz_quizId=$qid and resultado in ('gradedright','gradedwrong')
        and r.questaoTentativa<4 and r.questaoRespostaData between '$dtInicio' and '$dtFim'
        group by r.questaoTentativa";
        
        
        $command = $db->createCommand($sql_q);

         $rs_geral2=$command->queryAll();
         $descricao=array();
         $retorno .="<table class='table' style='margin-left:0px;font-size:12px'><tr>";
            foreach($rs_geral2 as $ind =>$item)
            {  
                $cssativo='ativo_q';
                if($item["questaoTentativa"]==$tent)
                    $cssativo='ativo2';
                $retorno.="<td><a class='$cssativo' href='##'".' onclick="$carregaPosicao('."'21',"."'".@$qid."','".@$item["questaoTentativa"]."');".'"'."> Tentativa ".$item["questaoTentativa"]." (".$item["total"].")</a></td>";
            }
            $retorno.="</tr><table>";

            
        
        
        $retorno.="<table id='table-questoes' class='table table-dotted' style='margin-left:0px;font-size:14px'>";

        $retorno.="<tr>";
        $vanterior="";

        $retorno.="<td  style='font-weight:bold'>Questão</td>";
        $retorno.="<td  style='font-weight:bold'>Dificuldade/Discriminação</td>";
        $retorno.="<td  style='font-weight:bold'>Erros</td>";
        $retorno.="<td  style='font-weight:bold'>Acertos</td>";
        $retorno.="<td  style='font-weight:bold'>Média</td>";
        #$retorno.="<td  style='font-weight:bold'>Hab min</td>";
        
        
        $i=1;
        $a_itr=array();
        $a_hab = array();
        $block = array();

        
        foreach($rs_geral as $item)
        {
          
            $stemparcial = "select count(1) as todas,sum(case when r2.resultado='gradedright' then 1 else 0 end) as
            certas,sum(case when r2.resultado='gradedwrong' then 1 else 0 end) as
            erradas,sum(case when r2.resultado='gradedpartial' then 1 else 0 end) as
            parciais,((select count(1) from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId 
            and r2.aluno_alunoId=r.aluno_alunoId and r.resultado in ('gradedright')
            and r.questaoTentativa=r2.questaoTentativa and r2.questao_questaoId!=r.questao_questaoId)) as certas_dif 
            from questaoresposta r2 where questao_questaoId=".$item["questaoId"]." and questao_quiz_quizId=$qid and
            questaoTentativa=$tent and questaoRespostaData between '$dtInicio' and '$dtFim' limit 1";
            $commnad_parcial = $db->createCommand($stemparcial);
            $rs_parcial = $commnad_parcial->queryAll();
           
            //echo "<Br>" . $stemparcial;
            //print_r($rs_parcial);
            //exit();
            
            if($rs_parcial[0]["parciais"]>0)
                $block[]=$item["questaoId"];
            
            
            if(!in_array($item["questaoId"],$block))
            {
               

            $sql_prev = "select count(1) as tot,avg((r2.questaoRespostaNota)) as ponto
            ,avg((select count(1) from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId and r2.aluno_alunoId=r.aluno_alunoId and r.resultado in ('gradedright') and r.questaoTentativa=r2.questaoTentativa and r2.questao_questaoId!=r.questao_questaoId)) as total,
            (stddev_samp((r2.questaoRespostaNota)) * stddev_samp((select count(1) from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId and r2.aluno_alunoId=r.aluno_alunoId and r.resultado in ('gradedright') and r.questaoTentativa=r2.questaoTentativa and r2.questao_questaoId!=r.questao_questaoId))
            ) as divisor            
            from questaoresposta r2 join questao q on (q.questaoId=r2.questao_questaoId)
            where q.questaoOrdem=".$item["questaoOrdem"]." and r2.questao_quiz_quizId=$qid and r2.questaoTentativa=$tent
            and r2.questaoRespostaData between '$dtInicio' and '$dtFim'";
            
            
           
            $commnad_prev = $db->createCommand($sql_prev);
            $rs_prev = $commnad_prev->queryAll();
            


            $sql_ordem = "select sum(((r2.questaoRespostaNota) - ".$rs_prev[0]["ponto"]." ) * ((select count(1) from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId
            and r2.aluno_alunoId=r.aluno_alunoId and r.resultado in ('gradedright') 
            and r.questaoTentativa=r2.questaoTentativa and r2.questao_questaoId!=r.questao_questaoId) - ".$rs_prev[0]["total"].") ) / ((sum((r2.questaoRespostaNota)) -1) *
            ".$rs_prev[0]["divisor"].") as correlacao  from questaoresposta r2 join questao q on (q.questaoId=r2.questao_questaoId) 
            where q.questaoOrdem=".$item["questaoOrdem"]." and r2.questao_quiz_quizId=$qid and r2.questaoTentativa=$tent
            and r2.questaoRespostaData between '$dtInicio' and '$dtFim';";
            
            
            
            $command_ordem = $db->createCommand($sql_ordem);

             $rs_ordem=$command_ordem->queryAll();

           
            $s_guess = "select ((select count(1) from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId and r2.aluno_alunoId=r.aluno_alunoId and r.resultado 
            in ('gradedright') and r.questaoTentativa=r2.questaoTentativa and
             r2.questao_questaoId=r.questao_questaoId)/count(1)) as hab0,
              round(LN(((select count(1) from questaoresposta r WHERE 
              r2.questao_quiz_quizId=r.questao_quiz_quizId and r2.aluno_alunoId=r.aluno_alunoId
               and r.resultado in ('gradedright') and r.questaoTentativa=r2.questaoTentativa)/ 
               (select count(1) from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId 
               and r2.aluno_alunoId=r.aluno_alunoId and r.resultado in ('gradedright','gradedwrong') 
               and r.questaoTentativa=r2.questaoTentativa) ))/(1- ((select count(1) from questaoresposta 
               r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId and r2.aluno_alunoId=r.aluno_alunoId 
               and r.resultado in ('gradedright') and r.questaoTentativa=r2.questaoTentativa)/ (select count(1)
                from questaoresposta r WHERE r2.questao_quiz_quizId=r.questao_quiz_quizId and
                 r2.aluno_alunoId=r.aluno_alunoId and r.resultado in ('gradedright','gradedwrong') 
                 and r.questaoTentativa=r2.questaoTentativa))),1) as habilidade from questaoresposta r2
                  join questao q on (q.questaoId=r2.questao_questaoId) where q.questaoOrdem=".$item["questaoOrdem"]." 
                  and r2.questao_quiz_quizId=$qid and r2.questaoTentativa=$tent and r2.resultado = 'gradedright' 
                  and r2.questaoRespostaData between '$dtInicio' and '$dtFim'
             group by habilidade having habilidade is not null order by habilidade asc,hab0 asc limit 1;";

            $command_guess = $db->createCommand($s_guess);

            $rs_guess=$command_guess->queryAll();
            
             $a_itr["habilidade"][]=number_format(@$rs_guess[0]["habilidade"],2);
             $a_itr["guess"][]=0.25;  
             #$a_itr["guess"][]=$rs_guess[0]["hab0"]>=0.25?0.25:number_format($rs_guess[0]["hab0"],2);  
             //$a_itr["guess"][]=number_format($rs_guess[0]["hab0"],2);  
             $discrim = number_format(@$rs_ordem[0]["correlacao"],2);
             $discrim = $discrim<0?0:$discrim;
             $a_itr["correlacao"][]=$discrim;
             
             //echo @$item["media"]."-";
            #$item["media"]=$item["media"]==0?1:$item["media"];
            
            $a_itr["dificuldade"][]=number_format(log((1-@$item["media"])/@$item["media"]),2);

            
            }
            else
            {
             
                $a_itr["dificuldade"][]="-";
                $a_itr["habilidade"][]="-";
                $a_itr["correlacao"][]="-";
                $a_itr["guess"][]="-";
            }
            //$a_itr["dificuldade"][]=1.0;
            
            //return 'oi passou';
           
            $i++;
            
        }
        
        $i=1;
        $ajs="";
        $q1="";
        foreach($rs_geral as $item)
        {
            
          
            $vanterior="";
            if($i==1)
            {
            $q1=$item["questaoId"];
            }
            $dq1="Q".$item["questaoOrdem"].")";
            $q2=$item["questaoId"];
            $red="";
            if(in_array($item["questaoId"],$block))
            {
                $red='style="color:red"';
              
            }
            else
            {

                $ajs.=$dq1.";".$a_itr["dificuldade"][$i-1] .";".$a_itr["correlacao"][$i-1] .";".$a_itr["guess"][$i-1].";".$a_itr["habilidade"][$i-1].";#";
            
            }
            $retorno.="<tr $red>";
            $retorno.="<td>"."<a $red id='q$q2' class='link-questao' href='##' onclick=".'"$exibeAlt('."'$q2#".$item["questaoOrdem"]."')".'">'."Q".$item["questaoOrdem"].")".'</a></td>';
            $retorno.="<td >". $a_itr["dificuldade"][$i-1] ."/".$a_itr["correlacao"][$i-1]."</td>";
            $retorno.="<td >". $item["erros"] ."</td>";
            $retorno.="<td >". $item["acertos"] ."</td>";
            $retorno.="<td >". $item["media"] ."</td>";
            #$retorno.="<td >".$a_itr["habilidade"][$i-1]."</td>";
            
            
            $retorno.="</tr>";
            $retorno.="</tr>";
            
            $i++;
        }
    $retorno.="</table>";
    $retorno.="</div>";
    $retorno.="<script>"."$"."exibeAlt('$q1#1'); </script>";
    $retorno.="[final]".$ajs;
    
    return $retorno;
}
    
function indicador_texto($rs_geral,$cabecalho,$porcentagem=false,$check=false)
{
    $retorno='';
    $vetspan=array("um","dois","tres");    
    
    
    $rs_compara = [];
    $temcompara = true;
    $totalgeral=array("dois"=>0,"tres"=>0);
    if(sizeof($rs_geral)==1)
    $temcompara=false;
    $vmaior = 0;
    $vmenor = 1;
    if(isset($rs_geral[1]))
    if(sizeof($rs_geral[0])<sizeof($rs_geral[1]))
    {
        $vmaior=1;
        $vmenor=0;
    } 
    $rcab = $cabecalho[0]=="-"? '': $cabecalho;
    $ehCabecalho = sizeof(explode("Questionários",$rcab))>1?true:false;
    
    $ehCabecalho = sizeof(explode("Fóruns",$rcab))>1?true:$ehCabecalho;
    $ehCabecalho = sizeof(explode("Identifica",$rcab))>1?true:$ehCabecalho;
    $ehCabecalho = sizeof(explode("Escolaridade",$rcab))>1?true:$ehCabecalho;
    $ehCabecalho = sizeof(explode("Gênero",$rcab))>1?true:$ehCabecalho;
    $ehCabecalho = sizeof(explode("Faixa E",$rcab))>1?true:$ehCabecalho;
    $ehCabecalho = sizeof(explode("Questionários",$rcab))>1?true:$ehCabecalho;
    $ehCabecalho = sizeof(explode("Fóruns",$rcab))>1?true:$ehCabecalho;

    $ehListaNum=false;
    $ehListaNum = sizeof(explode("Top Insc",$cabecalho))>1?true:$ehListaNum;
    $ehListaNum = sizeof(explode("Cursos com mai",$cabecalho))>1?true:$ehListaNum;

    $ehQuest = sizeof(explode("Questionários",$cabecalho))>1?true:false;
    $ehForum = sizeof(explode("Fóruns",$cabecalho))>1?true:false;

    if(!$ehListaNum && !$ehQuest && !$ehForum)
    {
    foreach($rs_geral[$vmaior] as $ind =>$item)
        {
            
            $vdentro["data"] =@$item["data"];
            $vdentro["total"] =@$item["total"];
            $vdentro["filtro"]=0;
            $vtem = -1;
            if(isset($rs_geral[$vmenor]))
            foreach ($rs_geral[$vmenor] as $ind2 =>$item2)
                if($item["data"]==$item2["data"])
                    $vtem=$ind2;
            if($vtem>=0)
            {
                $vdentro["filtro"]=$rs_geral[$vmenor][$vtem]["total"];
                    if($vmaior==1)
                    {
                        $vdentro["total"] =@$vdentro["filtro"];
                        $vdentro["filtro"]=$rs_geral[$vmaior][$ind]["total"];;
                    
                    }
            }
               
            $rs_compara[$ind]=$vdentro;
                
            $totalgeral["dois"]+=@$vdentro["total"];
            $totalgeral["tres"]+=@$vdentro["filtro"];
            
            
        }
    #echo "<pre>";
    #print_r($rs_compara);
    $rs_geral=$rs_compara;
    }
    else
    $rs_geral=$rs_geral[0];
    
            
    
    
    
    
    
    
    
    
    
    
    
   
    
      
    $idul="";
    if($check){
        $desc="genero[]";
        $desc = sizeof(explode("Faixa E",$rcab))>1?"idade[]":$desc;
        $desc = sizeof(explode("Escolaridade",$rcab))>1?"escolaridade[]":$desc;
        $desc = sizeof(explode("Identifica",$rcab))>1?"racial[]":$desc;
        $idul='ul_form_perfil';
    }
    if($cabecalho[0]=="-")
    $cabecalho =substr($cabecalho,1);

    
   
  
    if($ehQuest || $ehForum)
    $idul="ul_quest";
    if(sizeof($rs_geral)>1 or $ehCabecalho)
    {
        #echo "-----";
        $retorno=$rcab;  
    }
    
    $retorno.="<ul class='ul-lista-perfil' id='$idul' style='padding-left:3px !important'>";
    
    $ehListaNum=false;
    $ehListaNum = sizeof(explode("Top Insc",$cabecalho))>1?true:$ehListaNum;
    $ehListaNum = sizeof(explode("Cursos com mai",$cabecalho))>1?true:$ehListaNum;
    $clinha = 1;
  
    
    foreach($rs_geral as $item)
    {
            $i=0;
            if($ehQuest || $ehForum)
                $retorno.="<li ".'onclick="$carregaPosicao('."'21','".@$item["idunico"]."','".(@$item["descdesc"]!="1"?"#".$item["descdesc"]:"1")."');".'"'."  id='quest".$item["idunico"]."' class='lista_q'>";
            else if(sizeof($rs_geral)>1 or $ehCabecalho)
                $retorno.="<li>";
            $vanterior="";
            foreach($item as $valor)
            {              
                if($i==2 && !$temcompara)
                    break;
                if($i>2)
                    break;
                    if(($ehQuest || $ehForum) && $i==0)
                    {
                        $retorno.='<p class="texto-com-icone"><i class="icone icone-olho"></i>';
                        $retorno.='<a href="##" class="link-questao cursor-pointer" href="##">';
                        //print_r($item);
                    }
                    if($check && $i==0)
                    {
                        $retorno.='<label class="toggle toggle-perfil">';
                        $retorno.='<input name="'.$desc.'" value="'.$valor.'" type="checkbox" checked="checked" name="" value="" class="toggle-checkbox" type="checkbox" onclick="$rodaPerfil()">';
                        $retorno.='<div class="toggle-switch"></div>';
                        
                        
                        #$retorno.='<p class="texto-com-icone"><i class="icone icone-olho"></i>';
                        #$retorno.='<a href="#" class="link-questao cursor-pointer" onclick="$carregaPosicao('."'21','".@$item["idunico"]."','".(@$item["descdesc"]!="1"?"#".$item["descdesc"]:"1")."');".'" href="#">';
                        //print_r($item);
                    }

                    
                    $cc1=$vetspan[$i];
                  
                    if(@$porcentagem && in_array($cc1,array('dois','tres')) && @$totalgeral[$cc1]!=0)
                    {
                        $vpor =  ($valor/$totalgeral[$cc1])*100;
                        #echo "tgeral". $totalgeral[$cc1] . " - $valor<br>";
                        $vpor = intval($vpor);
                        $vpor = $vpor>0?$vpor:"<1";
                        if($check && $i!=0)
                        $retorno.="<span title='~ $valor' class='span-i-$cc1 toggle-perfil'>". $vpor ."%</span>";
                        else
                        $retorno.="<span title='~ $valor' class='span-i-$cc1'>". $vpor ."%</span>";

                        
                    }
                    else
                    {
                           $toggle="";
                           if(@$totalgeral[$cc1]==0 && @$_GET["r"]=="lumilab/avancado")
                           $toggle=" toggle-perfil";
                     
                           
                        
                           $umalinha=true;
                           $umalinha = sizeof(explode("Certificados Em",$cabecalho))>1?true:false;
                           $umalinha = sizeof(explode("Novos Us",$cabecalho))>1?true:$umalinha;
                           if(sizeof($rs_geral)==1 && $cc1=='um' && !$ehCabecalho && $umalinha)
                                $retorno.="<h3><strong>". $cabecalho ."</strong>";
                            else
                                {
                                #if($valor=="Não respondido" && sizeof($rs_geral)==1)
                                #$retorno.="<span class='li-cabecalho'>". $cabecalho ."</span><br>";
                                if($umalinha)
                                    $valor = number_format($valor,"0",".",".");
                                if ($cc1=="dois" && !$umalinha)
                                    $valor = number_format($valor,"0",",",",");
                                
                                if($cc1=="um" && !$umalinha && strlen($valor)>45)
                                    $valor = substr($valor,0,38)." [..]";
                                
                                if($ehListaNum && $cc1=='um')
                                    $valor="<b>".$clinha.") </b>".$valor;
                                
                                if(($ehQuest || $ehForum) && $cc1=='dois')
                                    $valor="<b style='font-size: 12px !important;color:#000'>".$valor."</b>";
                                if($ehForum && $cc1=="um")
                                    $valor = "<i style='font-size:xx-small'>".$item["forumDescricao"]."<br></i>$valor";
                                
                                $retorno.="<span class='". (sizeof($rs_geral)==1  && !$ehCabecalho && $umalinha? 'numero-indicador':'span-i-'.$cc1) ."$toggle'>". $valor ."</span>";
                                if(sizeof($rs_geral)==1  && !$ehCabecalho && $umalinha)
                                    $retorno.="</h3>";
                                }
                            
                    }
                    
                    if(($ehQuest || $ehForum) && $i==0)
                        $retorno.="</a>";
                    
                    if($check && $i==0)
                        $retorno.='</label>';
                    $vanterior=$valor;
                    $i++;
            
            }
            if(sizeof($rs_geral)>1 or $ehCabecalho)
            $retorno.="</li>";
            $clinha++;
        
    }
    
    $retorno.="</ul>";

    if($check){
        $retorno.='</div>';
    }
    return $retorno;
}
function indicador_grafico($rs_geral,$caso,$ehData=false,$exibeRodape=false,$porcentagem=false,$vertical=false)
{
    
  
    $todasDatas=array();
    $rotData=array();
    $vartot=array();
    //echo '<pre>';
    //print_r($rs_geral);
    //exit();
    $descativ=[];
   
    foreach($rs_geral as $ind => $umGrafico)
    {
        
        
            
        $namefinal  = $caso[$ind];
        $Anamefinal = explode(" - ",$caso[$ind]);
        if(sizeof($Anamefinal)>1)
        $namefinal=$Anamefinal[1];
        
        #print_r($caso);
        
        $todosDados[$ind]['name']=$namefinal;
        
        // Determine chart type based on data
        $isQuestionChart = (substr($caso[0], 0, strlen("Questões x Tentativas")) == "Questões x Tentativas" || substr($caso[0], 0, 3) == "TRI");
        $chartType = $isQuestionChart ? 'line' : ($ehData ? 'line' : 'bar');
        $todosDados[$ind]['type'] = $chartType;
        
        foreach($umGrafico as $item)
        {
          
            @$vartot[$ind]=@$vartot[$ind]+$item["total"];
            if($ehData)
                 $rotData[strtotime(@$item["data"])][$ind]=@$item["total"];
            else
            {
                if ($vertical)
                {
                    $ord_ativ = str_replace(".","",explode("-",@$item["data"])[0]);
                    
                    $rotData[$ord_ativ][$ind]=@$item["total"];
                    $descativ[$ord_ativ]=@$item["data"];
                }
                else
                $rotData[@$item["data"]][$ind]=@$item["total"];
            }
                
           
        }

    }
    
    $contadata=0;
    //if(!in_array("dias_curso",$caso))
    if($ehData or $vertical)
    ksort($rotData);

#    echo "<pre>";
 #       print_r($todosDados);
    

   
        
    foreach($rotData as $datadata => $umGrafico)
    {
        if(!$ehData)
        {
            
            if($vertical)
            {
            $todasDatas[]=$descativ[$datadata];
            }
            else
            $todasDatas[]=$datadata;
        }
        else
            $todasDatas[]=date('Y-m-d', $datadata);

            
            for($iii=0;$iii<sizeof($caso);$iii++)
                {
                    //@$todosDados[$iii]['name'][$contadata]=$caso[$contadata];

                    if(!isset($umGrafico[$iii]))
                        $todosDados[$iii]['data'][$contadata]=0;
                    else{
                        if(!$porcentagem)  
                            $todosDados[$iii]['data'][$contadata]=$umGrafico[$iii];
                        else
                            $todosDados[$iii]['data'][$contadata]=number_format(($umGrafico[$iii]/$vartot[$iii])*100,2,".",".");
                    
                    }
                    
                        
                       

                }
       
         $contadata++;  

    }
    
    #echo "<pre>";
        
        #print_r($todosDados);
    
        
    
    $dimen = 200;
    if(substr($caso[0],0,strlen("Questões x Tentativas"))=="Questões x Tentativas")
        $dimen=400;
    else
        if($vertical)
            $dimen = 500;
        else
        if(sizeof($caso)>2)
            $dimen=400;
          
    if(sizeof($caso)>=5)
        $dimen=500;

    $retorno= $this->chartLine(
        $todasDatas,
        $todosDados,
        @explode(" - ",$caso[0])[0],
        $dimen,
        sizeof($caso)>1? false:true,
        $vertical
        
    );
    
    $desc="";
    if($exibeRodape)
    {
    foreach($caso as $ii =>$vv)
        {
                $desc.="<p style='font-size:14px;margin-left:20px'>".@explode(" - ",$caso[0])[0] ." ".  @$todosDados[$ii]['name']." : "."<i style='font-weight:bold'>".@$vartot[$ii]."</i></p>";
        }
    }

    $retorno= '<div class="login-aqui" style="margin-bottom:10px !important">'.$retorno . $desc."</div>";

    return $retorno;
    
}

function chartRegressaoLinear($seriesData, $m_estatistica,$m_estatistica2, $title = '',$alturaGrafico=200)
    {
        $chart = new \Hisune\EchartsPHP\ECharts();
        $xAxis = new \Hisune\EchartsPHP\Doc\IDE\XAxis();
        $yAxis = new \Hisune\EchartsPHP\Doc\IDE\YAxis();
        
        $color = ['#57B6EC','#57B6EC','#4D4D4D','#4D4D4D'];
        #$color = ['#57B6EC','#4D4D4D'];
        
        $title && $chart->title->text = $title;
        $chart->color = $color;
        $chart->tooltip->trigger = 'item';
        $chart->toolbox->show = true;
        $chart->option->notMerge=true;
        //$chart->toolbox->feature->dataZoom->yAxisIndex = 'none';
        $chart->toolbox->feature->dataView->readOnly = true;
        $chart->toolbox->feature->saveAsImage = [];
        $chart->grid->containLabel=true;
        $chart->grid->left='3%';
        $chart->grid->right='4%';
        
        
        $chart->height=intval($alturaGrafico)-70;
          
        
        $xAxis->boundaryGap = true;
        $yAxis->name=$m_estatistica2;
        $yAxis->nameLocation='center';
        $xAxis->name=$m_estatistica;
        $xAxis->nameLocation='center';
        
        
        foreach($seriesData as $ser){
            
            
            $chart->legend->data[] = @$ser['name'];
            $chart->legend->right='0';
            $chart->legend->top='35';
            
            
            //$chart->legend->bottom='auto';
            $series = new \Hisune\EchartsPHP\Doc\IDE\Series();
            $series->name = @$ser['name'];
            
            
            $series->type = @$ser['type'];
            

//            @$series->data = null;
            @$series->data = @$ser['data'];
            
            
            $chart->addSeries($series);



        }
    
        $chart->addXAxis($xAxis);
        $chart->addYAxis($yAxis);
        
        
        
        
        //$alturaGrafico=$alturaGrafico==120?'400':'600';
           
        return $chart->render(uniqid(),array('style'=>'height:'.$alturaGrafico.'px'));
    }


function chartBox($seriesData, $m_estatistica, $title = '',$alturaGrafico=200)
    {
        $chart = new \Hisune\EchartsPHP\ECharts();
        $xAxis = new \Hisune\EchartsPHP\Doc\IDE\XAxis();
        $yAxis = new \Hisune\EchartsPHP\Doc\IDE\YAxis();
        $color = ['#57B6EC','#4D4D4D'];
        if(count($seriesData)==4)
        $color = ['#d9534f','#f0ad4e','#5cb85c','#0275d8'];
        
        $title && $chart->title->text = $title;
        $chart->color = $color;
        $chart->tooltip->trigger = 'item';
        
        
        $chart->toolbox->show = true;
        $chart->option->notMerge=true;
        //$chart->toolbox->feature->dataZoom->yAxisIndex = 'none';
        $chart->toolbox->feature->dataView->readOnly = true;
        $chart->toolbox->feature->saveAsImage = [];
        $chart->grid->containLabel=true;
        $chart->grid->left='3%';
        $chart->grid->right='4%';
        
        $xAxis->data=null;
        
        $chart->height=intval($alturaGrafico)-70; 
        
          
        $xAxis->type = 'category';
        $xAxis->boundaryGap = true;
        $xAxis->data = array($m_estatistica);
        
        $soMostra = "";
        if(count($seriesData)==4)
        {
            $soMostra="Negativo";
            $maxmostra = @$seriesData[0]['data'][2];
            
            $rotus = array("Negativo","Neutro","Positivo");
            
            foreach($seriesData as $ind=> $ser){
                
                if(@$ser['data'][2]>$maxmostra)
                {
                    $maxmostra=@$seriesData;
                    $soMostra=$rotus[$ind];
                }
                
                if($ind==2)
                    break;

            }
        }
        
        foreach($seriesData as $ser){
            
            $chart->legend->data[] = @$ser['name'];
            $chart->legend->right='0';
            $chart->legend->top='35';
            #$chart->tooltip->trigger='axis';
            /*
            if(count($seriesData)==4 && $soMostra!="")
            {
                if(@$ser['name']!=$soMostra)
                $chart->legend->selected[@$ser["name"]] = false;     
            }
            */


           
            
            
            //$chart->legend->bottom='auto';
            $series = new \Hisune\EchartsPHP\Doc\IDE\Series();
            $series->name = @$ser['name'];
            
            
            $series->type = "boxplot";
//            @$series->data = null;
            $series->data = array(@$ser['data']);
            #$series->datasetIndex=0;
          
            
            
            $chart->addSeries($series);
        }
    
        $chart->addXAxis($xAxis);
        $chart->addYAxis($yAxis);
        //$alturaGrafico=$alturaGrafico==120?'400':'600';
           
        return $chart->render(uniqid(),array('style'=>'height:'.$alturaGrafico.'px'));
    }

function chartLine($xAxisData, $seriesData, $title = '',$alturaGrafico=200,$areaAbaixo=false,
    $vertical=false, $porcentagem=false)
    {
        $chart = new \Hisune\EchartsPHP\ECharts();
        $xAxis = new \Hisune\EchartsPHP\Doc\IDE\XAxis();
        $yAxis = new \Hisune\EchartsPHP\Doc\IDE\YAxis();
        $color = ['#57B6EC','#4D4D4D'];
        if($vertical or $title=="Questões x Tentativas" or $title=="TRI")
        $color = ['#9e9e9e','#4169e1','#6CC772','#ffc93b','#d67365','#483D8B','#20B2AA','#BC8F8F','#FF00FF',
    '#FF4500'];
        //shuffle($color);
        
        $title && $chart->title->text = $title=="TRI"? "TRI - Teoria de Resposta ao Item":$title;
        $chart->color = $color;

        $chart->tooltip->trigger = 'axis';
        if($vertical)
        $chart->tooltip->trigger = 'item';
        $chart->toolbox->show = true;
        $chart->option->notMerge=true;
        //$chart->toolbox->feature->dataZoom->yAxisIndex = 'none';
        #$chart->toolbox->feature->dataView->readOnly = true;
        #$chart->toolbox->feature->magicType->type = ['line', 'bar'];
        $chart->toolbox->feature->saveAsImage = [];
        $chart->grid->containLabel=true;
        $chart->grid->left='3%';
        $chart->grid->right='4%';
        
        $xAxis->data=null;
        
        $chart->height=intval($alturaGrafico)-70;
    
       
        
        if(!$vertical)
        {
        if(substr($title,0,4)=="Dias")
        {
            $yAxis->axisLabel->formatter="{value} %";
            $xAxis->name='dias';
            
            $chart->tooltip->formatter = 'Ativo {b} dia(s) no curso<br><b>{a0}: </b>{c0} %<br><b>{a1}:</b> {c1}%';
            if(sizeof(explode("para obter",$title))>1)
            $chart->tooltip->formatter = 'Certificado emitido em {b} dia(s)<br><b>{a0}: </b>{c0} %<br><b>{a1}:</b> {c1}%';
        }
            
          
        $xAxis->type = 'category';
        $xAxis->boundaryGap = true;
      
        
        
        
        if(@$_GET["opcao"]=="mês")
        {
            $meses = array("01"=>"Jan","02"=>"Fev","03"=>"Mar","04"=>"Abr","05"=>"Mai","06"=>"Jun","07"=>"Jul","08"=>"Ago","09"=>"Set","10"=>"Out","11"=>"Nov","12"=>"Dez");
            $xAxisDatanovo=[];
            foreach($xAxisData as $ind =>$mmes)
                $xAxisDatanovo[]=$meses[substr($mmes,4)]."-".substr($mmes,0,4); 
        
        $xAxis->data = $xAxisDatanovo;
        }
        else
        $xAxis->data = $xAxisData;
        if($title=="TRI")
        {
            $xAxis->name = "habilidade-estudante";
            $xAxis->nameLocation = "center";
            $xAxis->nameGap= 17;
            $yAxis->name = "probabilidade-acerto";
            $yAxis->nameLocation = "center";
            $yAxis->nameGap= 17;
        }
        if($title=="Questões x Tentativas")
        {
            $xAxis->name = "tentativa";
            $xAxis->nameLocation = "center";
            $xAxis->nameGap= 17;
        }

        
        
         if($title=="Questões x Tentativas")
         {
            $yAxis->max=1.2;
            
//            $chart->grid->top='20%';
         }
         if($title=="Regressão Logística")
         {
            $yAxis->max=1.0;
//            $chart->grid->top='20%';
         }
         
        
            }
        else
        {
            $yAxis->type = 'category';
            $yAxis->boundaryGap = true;
            $yAxis->data = array_reverse($xAxisData);
            
        }
        
        
        foreach($seriesData as $ser){
            
            $chart->legend->data[] = @$ser['name'] ;
            $chart->legend->right='0';
            $chart->legend->top='35';
            if(in_array($title,array("Certificados","Inscrições")))
                $chart->legend->selected['Lúmina'] = false;     
        
            
            //$chart->legend->bottom='auto';
            $series = new \Hisune\EchartsPHP\Doc\IDE\Series();
            $series->name = @$ser['name'];
            if($title=="TRI" or $title=="Regressão Logística")
            {
            $series->smooth=true;
            $series->showSymbol=false;
            
            }
            
            $series->type = @$ser['type'];
//            @$series->data = null;
            
            @$series->data = @$ser['data'];
            if($vertical)
            {
                @$series->data = array_reverse(@$ser['data']);
                $series->stack ='total';
            }
                
            
            if($areaAbaixo)
                $series->areaStyle='{}';
            
            if(@$ser['type']=="bar" && $vertical)
                $chart->tooltip->trigger = 'item';
            $chart->addSeries($series);
        }
    
        $chart->addXAxis($xAxis);
        $chart->addYAxis($yAxis);
        //$alturaGrafico=$alturaGrafico==120?'400':'600';
        if($vertical)
        {
        //echo $alturaGrafico;
            //exit();
        }
    
        return $chart->render(uniqid(),array('style'=>'height:'.$alturaGrafico.'px'));
    }

    public static function pegaBotoesIndicadores($action)
    {
        
        $opcoes["novo"]["51"]=array('inscricoes',array('mês','ano','dia'));
        $opcoes["novo"]["52"]=array('certificados',array('mês','ano','dia'));
        $opcoes["avancado"]["21"]=array('inscricoes',array('mês','ano','dia'));
        $opcoes["avancado"]["22"]=array('certificados',array('mês','ano','dia'));
        return @$opcoes[$action];
    }

    public static function pegaDatas()
    {
        #$minhasConfig=self::buscaConfig(array("data_inicial_fixa","data_final_fixa"));
        
        
        $dtInicio= @$_GET["dti"];
        #else
        #$dtInicio =$minhasConfig["data_inicial_fixa"]!="0"?$minhasConfig["data_inicial_fixa"]:date('Y-m-d',strtotime('-30 days'));

        
        $dtFim = @$_GET["dtf"];
        #else
        #    $dtFim = $minhasConfig["data_final_fixa"]!="0"?$minhasConfig["data_final_fixa"]:date('Y-m-d');

        #$qualAction = isset($_GET['page'])? $_GET['page']:"";
        $debug=false;   

        $dtPersonal="ultima_semana";

        if($dtFim!="" && $dtInicio!="")
            $dtPersonal=$dtInicio.",".$dtFim;
            
        $dataIntervalo  = isset($_GET['intervalo'])? $_GET['intervalo']:$dtPersonal;
        
        $filtrou=false;
        
       
        if($dataIntervalo!="ultima_semana")
        {
            if($dataIntervalo=="ultimo_mes")
            {
                $dtInicio=date('Y-m-d',strtotime('-30 days'));
                $dtFim = date('Y-m-d');

            }
            else if($dataIntervalo=="tudo")
            {
                #$dtInicio=$minhasConfig["data_inicial_fixa"]!="0"?$minhasConfig["data_inicial_fixa"]:'2010-01-01';
                $dtInicio = '2010-01-01';
                $dtFim = date('Y-m-d');
            }
                else if($dataIntervalo=="ultimo_ano")
                { 
                    $dtInicio=(date('Y')-1)."-01-01";
                    $dtFim=date('Y')."-01-01";
                   
                }
                else if($dataIntervalo=="ano_atual")
                {
                    $dtInicio=date('Y')."-01-01";
                    $dtFim=(date('Y')+1)."-01-01";

                }else if($dataIntervalo=="penultimo_ano")
                {
                    $dtInicio=(date('Y')-2)."-01-01";
                    $dtFim=(date('Y')-1)."-01-01";
                }else
                {
                    $dtInicio=explode(",",$dataIntervalo)[0];
                    $dtFim=explode(",",$dataIntervalo)[1];
                }

        }else 
        {
            $dtInicio=date('Y-m-d',strtotime('-7 days'));
            $dtFim=date('Y-m-d');
        }
        
        if (in_array(@$_GET["r"],array("lumilab/avancado","lab/avancado","lumilab/questionario","lumilab/estatistica","lumilab/forum")))
        {
           
            
            $l_cursos = self::buscaCursos(@$_GET["cid"]);
            
            if(@$_GET["cid"]!="")
            {
                #echo "inivindo".date($dtInicio);
                #print_r($l_cursos);
                #echo "inicurso".date(self::buscaPorCid($l_cursos,$_GET["cid"])["cursoData"]);
                #echo (date($dtInicio)) . " - " .(date(self::buscaPorCid($l_cursos,$_GET["cid"])["cursoData"]));                
                
                $dtInicio = ((date($dtInicio))>(date(self::buscaPorCid($l_cursos,$_GET["cid"])["cursoData"])) && $dataIntervalo!="tudo")?$dtInicio:self::buscaPorCid($l_cursos,$_GET["cid"])["cursoData"];
                return array($dtInicio,$dtFim,@$_GET["cid"],self::buscaPorCid($l_cursos,$_GET["cid"])["cursoDescricao"],$dataIntervalo);
            }
            else
            {
                
            return array(@$l_cursos[0]["cursoData"],$dtFim,@$l_cursos[0]["id"],@$l_cursos[0]["cursoDescricao"],$dataIntervalo);
            }
        }
        else
        {
            return  array($dtInicio,$dtFim,"","",$dataIntervalo);
        }
    }

    public static function buscaConfig($desc)
    {
        $db = \Yii::$app->db;
        $sql_geral = "select * from configuracao where ";
        foreach($desc as $ii =>$dd)
        $sql_geral.=" configuracaoDescricao='$dd' or ";

        
        $command = $db->createCommand(substr($sql_geral,0,-4));
               
        $rs_geral=$command->queryAll();
        $retorno=array();
        foreach($rs_geral as $iii =>$vvv)
            $retorno[$vvv["configuracaoDescricao"]]=$vvv["configuracaoValor"];


        return $retorno;
        
    }

    public static function buscaCursos($cursoid = "")
    {  
        $db = \Yii::$app->db;
        if($cursoid=="" && isset(\Yii::$app->user->identity->id))
            $sql_geral = "select c.cursoId as id,c.cursoDescricao,date(c.cursoDataCriacao) as cursoData from cursoresponsavel cr join 
        curso c on (c.cursoId=cr.curso_cursoId)         
        where cr.usuario_usuarioId=".\Yii::$app->user->identity->id . " group by id";
        else
        $sql_geral = "select c.cursoId as id,c.cursoDescricao,date(c.cursoDataCriacao) as cursoData from curso c where c.cursoId=$cursoid ";
        $command = $db->createCommand($sql_geral);
        $rs_geral=$command->queryAll();
        
        return $rs_geral;
        
    }

    public static function buscaAreas()
    {  
        $db = \Yii::$app->db;
        $sql_geral = "select *  from areacurso where areaVisivel=1";
        $command = $db->createCommand($sql_geral);
        $rs_geral=$command->queryAll();
        return $rs_geral;
        
    }


    public static function buscaPorCid($l_cursos,$cid)
    {  
        foreach($l_cursos as $i =>$v)
        {
            if($cid==$v["id"])
            return $v;
        }
        
    }
    
}

