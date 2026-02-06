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

$l_areas = LumilabController::buscaAreas();
$area_sel = [];
if(isset($_GET["area"]))
{
    $area_sel=$_GET["area"];
}
$areaval="";
foreach($area_sel as $iarea =>$varea)
    $areaval.="$varea,";

if(sizeof($area_sel)==sizeof($l_areas))
$areaval="";

$areaval="'".substr($areaval,0,-1)."'";


$area_cargas = [];
if(isset($_GET["carga"]))
{
    $area_cargas=$_GET["carga"];
}
$cargas="";
foreach($area_cargas as $icarga =>$vcarga)
    $cargas.="$vcarga,";

if(sizeof($area_cargas)==3)
    $cargas="";


$cargas="'".substr($cargas,0,-1)."'";









    $layouts = [
        //'row r1 login-aqui'=>['row r1'=>['col-md-5','col-md-7'],
        'row r1 com-borda nao-fecha'=>['col-md-12'],
        'row r2 fecha-dois'=>['col-md-3',' col-md-3','col-md-3','col-md-3'],
        'row r3 com-borda mt-3 mb-3 nao-fecha'=>['col-md-10'],
        'row r4 '=>['col-md-3','col-md-3','col-md-3','col-md-3'],
        'row r5 mb-3 contem-duas-colunas'=>['col-6','col-6'],
        'row r6 mb-3 contem-duas-colunas fecha-dois'=>['col-6','col-6'],
        
    ];
    $opcoes=LumilabController::pegaBotoesIndicadores('novo');
    $datas=LumilabController::pegaDatas();
    $xx=1;
    $yy=1;
    foreach($layouts as $ll =>$cc)
    {
   
        ?>
        <div class="<?php echo $ll;?>">
            <?php
                 if(($yy==3 or $yy==1) && $xx==1){?>

    
          <div class="row">

            <div class="col-12">
              <?php 
              $minhasConfig=LumiLabController::buscaConfig(array("gab_areas"));
              $marea = explode(",",$minhasConfig["gab_areas"]);
              $areadesc="|";
              foreach($marea as $iarea =>$varea )
              {
                $vvarea = explode("=",$varea);
                if (in_array($vvarea[0],$area_sel))
                    $areadesc.=$vvarea[1]."|";

              }
              if($areaval=="''")
                $areadesc="Todas";

            if($cargas=="''")
                $cargadesc="Todas";
            else{
              $cargadesc = str_replace("0-20","Até 20h",substr($cargas,1,-1));
              $cargadesc = str_replace("40-1000","+ 40h",$cargadesc);
              $cargadesc = str_replace("20-40","De 20h a 40h",$cargadesc);
              $cargadesc = "|".str_replace(",","|",$cargadesc);
              
            }
              ?>
              <p class="texto-com-icone mb-0"><i class="icone icone-redondo-azul"></i> Dados Lúmina: <br> 
              <span class="btn tag-filtro btn-sm ">De <?php echo $dtInicio?> a <?php echo $dtFim?></span>
              <?php if($yy==3){?>
              <span class=" btn tag-filtro btn-sm ">Carga Horária: <?php echo $cargadesc;?> </span>
              <span class=" btn tag-filtro btn-sm ">Área: <?php echo $areadesc;?> </span>
              <?php }?>

            </p>
              
              
            </div>
            

          </div>
        

                  
                 <?php }
                foreach($cc as $c1 =>$v1)
                { 
                    if(!is_array($v1))
                    {
                    ?>
                <div class="<?php echo $v1;?>">
                <div id="lumilab-pre-<?php echo $yy.$xx?>" style="width:auto;" align="center">
                <?php echo Html::img('@web/ponto-load.gif',array('width'=>40,'id'=>'img-load-'. $yy.$xx,'style'=>'margin:auto;text-align:center;display:none;vertical-align:middle;opacity:0.5'));?>
            </div>
                <div  id="lumilab-indicador-<?php echo $yy.$xx?>">
                
                <?php echo $debug?$yy.$xx:'';?>
                    
                    </div>
                    <div style="float:right;text-align:end;margin-top:-40px">
                    <?php 
                        if(@$opcoes[$yy.$xx][0])
                        {?>
                        <div id="botoes-<?php echo $opcoes[$yy.$xx][0]?>" style="display:none">
                            <?php foreach(@$opcoes[$yy.$xx][1] as $iii =>$vvv) { ?>
                            <button class="btn btn-sm btn<?php echo $iii==0?'':'-outline'?>-primary" id="bti-<?php echo $opcoes[$yy.$xx][0]."-".$vvv?>"><?php echo $vvv?></button>
                        <?php }?>
                            </div>
                        <?php 
                        }
                        $xx++;
                        ?>
                        </div>
                </div>
                <?php
                    }   
                }
                $xx=1;
             
        if (substr($ll,strlen("nao-fecha")*-1)!="nao-fecha")
            echo "</div>"; 
        if (substr($ll,strlen("fecha-dois")*-1)=="fecha-dois")  
            echo  "</div>";
            ?>
        <?php  
        $yy++;
     
    }
    

?>

<?php 



$this->registerJs("function carregaPosicao(p,desc=''){
    
    $('#img-load-'+p).show();
    $('#lumilab-indicador-'+ p ).html('');
    $('#lumilab-pre-'+ p ).show();
    if(p=='51')
    $('#botoes-inscricoes').hide();
    if(p=='52')
    $('#botoes-certificados').hide();
    
  
       $.ajax({
          url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
          type: 'get',
          data: {
                    ajax:'S',
                    intervalo:'".@$_GET["intervalo"]."',
                    posicao:p,
                    dti:'".$datas[0]."',
                    dtf:'".$datas[1]."',
                    acao:'novo',    
                    areas:".$areaval.",
                    cargas:".$cargas.",
                    _csrf : '". Yii::$app->request->getCsrfToken() ."'
                },
          success: function (data) {
            
            
            $('#img-load-'+p).hide();
            $('#lumilab-indicador-'+ p ).html(data);
            $('#lumilab-pre-'+ p ).hide();
            if(p=='51')
            $('#botoes-inscricoes').show();
            if(p=='52')
            $('#botoes-certificados').show();
                
            
           
            
            

          }
     });}",
    View::POS_READY,
    'lumi-lab-cp'.rand(1,9999)
);
?>


<?php 
foreach($opcoes as $posicao => $dopcoes)
{
    $string="";
    foreach($dopcoes[1] as $vvv){
        $string.="$('#bti-".$dopcoes[0]."-".$vvv."').removeClass('btn-primary');";
        $string.="$('#bti-".$dopcoes[0]."-".$vvv."').addClass('btn-outline-primary');";
    }
    foreach($dopcoes[1] as $vvv){
$this->registerJs("$('#bti-".$dopcoes[0]."-".$vvv."').click(function(){
    $string       
    $(this).removeClass('btn-outline-primary');
    $(this).addClass('btn-primary');

    $('#lumilab-pre-".$posicao."').show();
    $('#lumilab-indicador-".$posicao."').hide();
    $('#img-load-".$posicao."').show();
    $('#botoes-".($posicao=="51"?'inscricoes':'certificados')."').hide();
       
       $.ajax({
          url: '". Yii::$app->request->baseUrl. '?r=lumilab/sample' ."',
          type: 'get',
          data: {
                    ajax:'N',
                    indicador: '".$dopcoes[0]."' , 
                    opcao:'".$vvv."', 
                    posicao:'".$posicao."',
                    dti:'".$datas[0]."',
                    dtf:'".$datas[1]."',
                    acao:'novo',
                    areas:".$areaval.",
                    cargas:".$cargas.",                    
                    _csrf : '". Yii::$app->request->getCsrfToken() ."'
                },
          success: function (data) {
            $('#lumilab-indicador-".$posicao."').show();
            $('#lumilab-indicador-".$posicao."').html(data);
           
            $('#img-load-".$posicao."').hide();
            $('#botoes-".($posicao=="51"?'inscricoes':'certificados')."').show();

          }
     });});",
    View::POS_READY,
    'lumi-lab-cc'.$dopcoes[0].'-'.$vvv
);
    }
}


//$this->registerJs("carregaPosicao('61');carregaPosicao('62')", View::POS_READY,'lumi-lab-'.rand(1,9999));

$xx=1;
$yy=1;
foreach($layouts as $ll =>$cc)
{

            foreach($cc as $c1 =>$v1)
            { 
                $dd='';
                $this->registerJs("carregaPosicao('".$yy.$xx."');", View::POS_READY,'lumi-lab-cp-'.rand(1,9999));
                $xx++;
            }
            $xx=1;
            $yy++;
}
?>