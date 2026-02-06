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
require("menu.php");


?>


    <?php
    $layouts = [
        //'row r1 login-aqui'=>['row r1'=>['col-md-5','col-md-7'],
        //'row r1 login-aqui '=>['col-md-3','col-md-3','col-md-3','col-md-3'],
        //'row r2 login-aqui fecga'=>['col-md-12'],
        'row r3 com-borda mb-3'=>['col-md-6','col-md-6'],
        'row r4 mb-3 com-borda seg-linha'=>['col-md-7','col-md-5'],
        'row r5 mb-3 com-borda seg-linha'=>['col-md-7','col-md-5'],
    
        
        //'row r5 mb-3 com-borda seg-linha'=>['col-md-7','col-md-5'],
        
       
    ];
    $opcoes=LumilabController::pegaBotoesIndicadores('forum ');
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

    $this->registerJs("function checaRetorno(){
    
        if($('#img-load-21').is(':visible'))
        {
          $('#img-load-21').hide();
          $('#lumilab-pre-22').hide();
          $('#lumilab-pre-21').hide();
          $('#lumilab-indicador-21').show();
          $('#lumilab-indicador-21').html(\"<div align='center'><b class='".'ativo_q'."'>Erro inesperado ao Carregar - Sentimentos nos Posts</b></div>\");
          $('#lumilab-indicador-22').hide();
        }

       
    
        if($('#img-load-31').is(':visible'))
        {
          $('#img-load-31').hide();
          $('#lumilab-pre-32').hide();
          $('#lumilab-pre-31').hide();
          $('#lumilab-indicador-31').show();
          $('#lumilab-indicador-31').html(\"<div align='center'><b class='".'ativo_q'."'>Erro inesperado ao Carregar Palavras em Posts</b></div>\");
          $('#lumilab-indicador-32').hide();
          
        }
         
         
      }",
         View::POS_READY,
         'lumi-lab-forum'.rand(1,9999)
      );  

$this->registerJs('$'."carregaPosicao = function(p,forum,desc=''){
     
    setTimeout(checaRetorno,10000)
    if(p=='21')
    {
    $('#ul_quest li').each(function (){
        
        if($(this).attr('id')=='quest'+forum)
        {
        $(this).addClass('lista_qsel');
        }
        else
        {
        $(this).removeClass('lista_qsel').addClass('lista_q');
        }
      });   
    

     $('#lumilab-indicador-22').hide();
     $('#lumilab-pre-22').show();
     $('#img-load-22').hide();
    
     //$('#lumilab-indicador-31').hide();
     //$('#lumilab-pre-31').show();
     //$('#img-load-31').hide();

     //$('#lumilab-indicador-32').hide();
     //$('#lumilab-pre-32').show();
     //$('#img-load-32').hide();

     $('#lumilab-indicador-31').hide();
     $('#lumilab-pre-31').show();
     $('#img-load-31').hide();

     $('#lumilab-indicador-32').hide();
     $('#lumilab-pre-32').show();
     $('#img-load-32').hide();

    }

    $('#lumilab-indicador-'+ p ).hide(); 
    $('#lumilab-pre-'+ p ).show();
    $('#img-load-'+p).show();
    
    if(p=='21'  || p=='31')
    {
    $('#lumilab-indicador-'+p.substring(0,1)+'2').hide();
    $('#lumilab-pre-'+p.substring(0,1)+'2').show();
    $('#img-load-'+p.substring(0,1)+'2').hide();
    }
    
    if(p=='11' || p=='12')
    $('.seg-linha').hide();
    else
        $('.seg-linha').show();

    v_teste='N'
    if(desc.substring(0,1)=='#')
    {
        v_teste='S'
        //alert('vai')
    }
    
    if(p=='21')
    {
        
        \$carregaPosicao('31',forum,desc)
        \$carregaPosicao('31',forum,desc)
        
    }
    
        $.ajax({
           url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
           type: 'get',
           data: {
                     posicao:p,
                     dti:'".$datas[0]."',
                     dtf:'".$datas[1]."',
                     cid:'".$datas[2]."',
                     acao:'forum',                    
                     forum:forum, 
                     forum_desc:desc,
                     v_teste:v_teste,
                     areas:".$areaval.",
                     cargas:".$cargas.",
                     _csrf : '". Yii::$app->request->getCsrfToken() ."'
                 },
           success: function (data) {

            datanova = data.split('#DIVISAO#');

            
            $('#lumilab-indicador-'+ p ).show();      
            
            $('#lumilab-indicador-'+ p ).html(datanova[0]);
                        
            $('#img-load-'+p).hide();
            
            $('#lumilab-pre-'+ p ).hide();
            

            if(p=='21' || p=='31')
            {
            $('#img-load-'+p.substring(0,1)+'2').hide();
            $('#lumilab-indicador-'+p.substring(0,1)+'2').show();
            $('#lumilab-indicador-'+p.substring(0,1)+'2').html(datanova[1]);
            $('#lumilab-pre-'+p.substring(0,1)+'2').hide();
            }

           

           
            
 
           }
      });}",
     View::POS_READY,
     'lumi-lab-cp-for'.rand(1,9999)
 );


$this->registerJs('$'."carregaPosicao('11','0');", View::POS_READY,'lumi-lab-'.rand(1,9999));
$this->registerJs('$'."carregaPosicao('12','0');", View::POS_READY,'lumi-lab-'.rand(1,9999));



?>




