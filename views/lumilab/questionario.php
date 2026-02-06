<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */



use yii\bootstrap5\ActiveForm;


/** @var yii\web\View $this */
use yii\web\View;
use yii\bootstrap5\Html;
use app\controllers\LumilabController;


?>

<?php 
//$chart = new \Hisune\EchartsPHP\ECharts();
//$chart->render(uniqid());
require("menu.php");?>

    <?php
    $layouts = [
        //'row r1 login-aqui'=>['row r1'=>['col-md-5','col-md-7'],
        //'row r1 login-aqui '=>['col-md-3','col-md-3','col-md-3','col-md-3'],
        //'row r2 login-aqui fecga'=>['col-md-12'],
        'row r3 com-borda mb-3'=>['col-md-6','col-md-6'],
        'row r4 mb-3 com-borda seg-linha'=>['col-md-7','col-md-5'],
        'row r5 mb-3 com-borda seg-linha'=>['col-md-7','col-md-5'],
        
        
       
    ];
    $opcoes=LumilabController::pegaBotoesIndicadores('questionario');
    $datas=LumilabController::pegaDatas();
    $xx=1;
    $yy=1;
  
    foreach($layouts as $ll =>$cc)
    {
        ?>
        <div class="<?php echo $ll;?>">
            <?php
                foreach($cc as $c1 =>$v1)
                { 
                    if(!is_array($v1) )
                    {
                    
                    ?>
                <div class="<?php echo $v1;?>">
                <?php if($yy>=2){?>
                 <div id="lumilab-pre-<?php echo $yy.$xx?>" style="width:auto;height:150px  " align="center">
                <br><br>
                <?php echo Html::img('@web/ponto-load.gif',array('width'=>40,'id'=>'img-load-'. $yy.$xx,'style'=>'margin:auto;text-align:center;display:none;vertical-align:middle;opacity:0.5'));?>
                </div>
                <?php } ?>
                <div  id="lumilab-indicador-<?php echo $yy.$xx?>">
                <?php echo $debug?$yy.$xx:'';?>
                    <?php echo @$posicao_indicador[$yy.$xx];
                    ?>
                    </div>
                    
                        <?php 
                        $xx++;
                        ?>
                </div>
                <?php
                    }
                }
                $xx=1;
                
                
            ?>
        </div>
        <?php  
        $yy++;
    }

$this->registerJs('$'."carregaPosicao = function(p,quiz,tent='1'){
     
    if(p=='21')
    {
    $('#ul_quest li').each(function (){
        
        if($(this).attr('id')=='quest'+quiz)
        {
        $(this).addClass('lista_qsel');
        }
        else
        {
        $(this).removeClass('lista_qsel').addClass('lista_q');
        }
      });   
    }
    
    
    $('#lumilab-indicador-'+ p ).hide(); 
    $('#lumilab-pre-'+ p ).show();
    
    $('#img-load-'+p).show();
    if(p=='11' || p=='12')
        $('.seg-linha').hide();
    if(p=='21')
    {
        $('.seg-linha').show();
        $('#lumilab-pre-'+ p ).addClass('ul-listagem');
        $('#lumilab-indicador-22').hide(); 
        $('#lumilab-indicador-31').hide(); 
        $('#lumilab-indicador-32').hide(); 
        $('#lumilab-pre-22').show();
        $('#lumilab-pre-31').show();
        $('#lumilab-pre-32').show();
       

        $('#lumilab-pre-22').addClass('ul-listagem');
        $('#lumilab-pre-31').addClass('ul-listagem');
        $('#lumilab-pre-32').addClass('ul-listagem');

        $('#lumilab-indicador-22').html(''); 
        $('#img-load-22').show()
        $('#img-load-31').show()
        $('#img-load-32').show()
    
    }
        
     
        $.ajax({
           url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
           type: 'get',
           data: {
                    ajax:'S', 
                    posicao:p,
                    intervalo:'".@$_GET["intervalo"]."',
                     dti:'".$datas[0]."',
                     dtf:'".$datas[1]."',
                     cid:'".$datas[2]."',
                     acao:'questionario',                    
                     quiz:quiz, 
                     tentativa:tent,
                     _csrf : '". Yii::$app->request->getCsrfToken() ."'
                 },
           success: function (data) {

                
             datanova = data.split('[final]');
             $('#img-load-'+p).hide();
             $('#lumilab-indicador-'+ p ).show();
             $('#lumilab-indicador-'+ p ).html(datanova[0]);
             $('#lumilab-pre-'+ p ).hide();
             
                if(p=='21')
                {
                
                    \$carregaPosicao('32',quiz,'1'); 
                    
                    
                    \$carregaITR('31',datanova[1]);
                }
          
            
            
            
 
           }
      });}",
     View::POS_READY,
     'lumi-lab-cp'.rand(1,9999)
 );

 $this->registerJs('$'."carregaITR = function(p,dados){
     
    $('#lumilab-indicador-'+ p ).hide(); 
    $('#lumilab-pre-'+ p ).show();
    $('#img-load-'+p).show();
    

     
        $.ajax({
           url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
           type: 'get',
           data: {
                    ajax:'S', 
                    posicao:p,
                    intervalo:'".@$_GET["intervalo"]."',
                     dti:'".$datas[0]."',
                     dtf:'".$datas[1]."',
                     cid:'".$datas[2]."',
                     acao:'itr',                    
                     dados:dados, 
                     _csrf : '". Yii::$app->request->getCsrfToken() ."'
                 },
           success: function (data) {
            
             
         
             $('#img-load-'+p).hide();
             $('#lumilab-indicador-'+ p ).show();
             $('#lumilab-indicador-'+ p ).html(data);
             $('#lumilab-pre-'+ p ).hide();
           


           },
           error: function(result) {
            $('#lumilab-indicador-'+ p ).html('Erro ao carregar. Tente novamente');
        }
      });}",
     View::POS_READY,
     'lumi-lab-citr'.rand(1,9999)
 );

 $this->registerJs('$'."exibeAlt = function(data){
     
    if(data!='#1')
 {
    $('#table-questoes tr td a').each(function (){
        
        if($(this).attr('id')=='q'+data.split('#')[0])
        $(this).addClass('ativo2');
        else
        {
        $(this).removeClass('ativo2').addClass('link-questao ativo_q');
        }
        
      });   
    }
    
        $('#lumilab-indicador-22').hide();       
        $('#lumilab-pre-22').show();
        
        
        $('#img-load-22').show()

        $.ajax({
            url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-questao' ."',
            type: 'get',
            data: {
                      qid:data, 
                      _csrf : '". Yii::$app->request->getCsrfToken() ."'
                  },
            success: function (data) {
                $('#lumilab-indicador-22').show();
                $('#lumilab-indicador-22').addClass('ul-listagem-questao');
                $('#lumilab-indicador-22').html(data);
                $('#img-load-22').hide()
                $('#lumilab-pre-22').hide();
             
            },
            error: function(result) {
             $('#lumilab-indicador-22').html('Erro ao carregar. Tente novamente');
         }
       });








      
        
     }",
    View::POS_READY,
    'lumi-lab-ea'.rand(1,9999)
);

 ?>
 
<?php

$this->registerJs('$'."carregaPosicao('11','0');", View::POS_READY,'lumi-lab-'.rand(1,9999));
$this->registerJs('$'."carregaPosicao('12','0');", View::POS_READY,'lumi-lab-'.rand(1,9999));


?>




