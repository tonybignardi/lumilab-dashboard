<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */



use yii\bootstrap5\ActiveForm;


/** @var yii\web\View $this */
use yii\web\View;
use yii\bootstrap5\Html;
use app\controllers\LumilabController;

?>
<?php require("menu.php");


?>

<div class="row com-borda mb-3">
<?php 
    $layouts = [
        'row r2-check fecha-dois'=>['col-md-3 col-grande',' col-md-3 col-grande','col-md-3 col-grande','col-md-3 col-grande'],
        'row r4 contem-duas-colunas mb-3'=>['col-6 com-borda','col-6 com-borda'],
        'row r5 contem-duas-colunas mb-3'=>['col-6 com-borda','col-6 com-borda'],
        'row r6 com-borda mb-3'=>['col-12'],
    ];
    $opcoes=LumilabController::pegaBotoesIndicadores('avancado');
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
                <?php if($yy>=1){?>
                 <div id="lumilab-pre-<?php echo $yy.$xx?>" style="width:auto;" align="center">
                <?php echo Html::img('@web/ponto-load.gif',array('width'=>40,'id'=>'img-load-'. $yy.$xx,'style'=>'margin:auto;text-align:center;display:none;vertical-align:middle;opacity:0.5'));?>
                </div>
                <?php } ?>
                <div  id="lumilab-indicador-<?php echo $yy.$xx?>">
                <?php echo $debug?$yy.$xx:'';?>
                    <?php echo @$posicao_indicador[$yy.$xx];
                    ?>
                    </div>
                    <div style="float:right;text-align:end;margin-top:-60px">
                    <?php 
                        if(@$opcoes[$yy.$xx][0])
                        {?>
                        <div id="botoes-<?php echo $opcoes[$yy.$xx][0]?>" style="display:none">
                            <?php foreach(@$opcoes[$yy.$xx][1] as $iii =>$vvv) { ?>
                            <button class="btn btn-sm btn<?php echo $iii==0?'':'-outline'?>-primary" id="bti-<?php echo $opcoes[$yy.$xx][0]."-".$vvv?>"><?php echo $vvv?></button>
                        <?php }?>
                            </div>
                        <?php }
                        $xx++;
                        ?>
                    </div>
                </div>
                <?php
                    }
                }
                if($xx==5)
                {
                $block = @$_GET["intervalo"]=="tudo"?true:false;
                    ?>
                  <div class="col-md-12" align="center" id="form_calcular">

<input type="button" <?php echo $block?'disabled="disabled"':"";?> id="btn_calcgeral" value="<?php echo $block?'Indisponível para #TodoPeríodo':"Obter Resultados";?>" class="btn btn-primary btn-sm w-30" onclick="$rodaPerfilBotao()">

</div>  
                <?php }
                
                $xx=1;
                if (substr($ll,strlen("nao-fecha")*-1)!="nao-fecha")
                echo "</div>"; 
            if (substr($ll,strlen("fecha-dois")*-1)=="fecha-dois")  
                echo  "</div>";
            ?>
        
        <?php  
        $yy++;
    }


$this->registerJs("function carregaPosicao(p,desc=''){
     
    $('#img-load-'+p).show();
    $('#lumilab-indicador-'+ p ).html('');
    $('#lumilab-pre-'+ p ).show();
    if(p=='21')
    $('#botoes-inscricoes').hide();
    if(p=='22')
    $('#botoes-certificados').hide();
   

        $.ajax({
           url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
           type: 'get',
           data: {
                     posicao:p,
                     dti:'".$datas[0]."',
                     dtf:'".$datas[1]."',
                     cid:'".$datas[2]."',
                     acao:'avancado',     
                     areas:".$areaval.",
                     cargas:".$cargas.",
                     _csrf : '". Yii::$app->request->getCsrfToken() ."'
                 },
           success: function (data) {

            
             
            $('#img-load-'+p).hide();
            $('#lumilab-indicador-'+ p ).html(data);
            $('#lumilab-pre-'+ p ).hide();
            if(p=='21')
            $('#botoes-inscricoes').show();
            if(p=='22')
            $('#botoes-certificados').show();
             
            
 
           }
      });}",
     View::POS_READY,
     'lumi-lab-cpiu'.rand(1,9999)
 );
 ?>

<?php 


$this->registerJs('$'."rodaPerfilBotao = function(){

    

    $('#bti-inscricoes-ano').removeClass('btn-primary').addClass('btn-outline-primary');
    $('#bti-inscricoes-dia').removeClass('btn-primary').addClass('btn-outline-primary');
    $('#bti-inscricoes-mês').removeClass('btn-outline-primary').addClass('btn-primary');
    
    $('#bti-certificados-ano').removeClass('btn-primary').addClass('btn-outline-primary');
    $('#bti-certificados-dia').removeClass('btn-primary').addClass('btn-outline-primary');
    $('#bti-certificados-mês').removeClass('btn-outline-primary').addClass('btn-primary');

    

    carregaPosicaoPerfil('21','#')
    carregaPosicaoPerfil('22','#')

    

    carregaPosicaoPerfil('31','#')
    carregaPosicaoPerfil('32','#')
    carregaPosicaoPerfil('41','#')


};",
View::POS_READY,
'lumi-lab-cpiu'.rand(1,9999)
);


$this->registerJs('$'."rodaPerfil = function(){

 console.log('apertou')

};",
View::POS_READY,
'lumi-lab-cpiu'.rand(1,9999)
);

$this->registerJs("function carregaPosicaoPerfil(p='',desc=''){
     
     
     sel_perfil=''
     desmarcado = false
     scontadores={'genero[]':0,'racial[]':0,'escolaridade[]':0,'idade[]':0}
     $('#ul_form_perfil li label input').each(function (){
       if ($(this).is(':checked'))
       {
         sel_perfil+=$(this).attr('name')+':'+$(this).val()+';'
       }
       else
       desmarcado=true
       scontadores[$(this).attr('name')]++
               
     });
     
     if(!desmarcado)
       sel_perfil=''
       //alert(sel_perfil)
        
       $('#img-load-'+p).show();
       $('#lumilab-indicador-'+ p ).html('');
       $('#lumilab-pre-'+ p ).show();
       if(p=='21')
       $('#botoes-inscricoes').hide();
       if(p=='22')
       $('#botoes-certificados').hide();
    

        $.ajax({
           url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
           type: 'get',
           data: {
                     posicao:p,
                     dti:'".$datas[0]."',
                     dtf:'".$datas[1]."',
                     cid:'".$datas[2]."',
                     acao:'avancado',    
                     sel_perfil:sel_perfil,     
                     areas:".$areaval.",
                     cargas:".$cargas.",
                     v_contadores:scontadores['genero[]']+','+scontadores['racial[]']+','+scontadores['escolaridade[]']+','+scontadores['idade[]'],
                     _csrf : '". Yii::$app->request->getCsrfToken() ."'
                 },
           success: function (data) {

            
             
            $('#img-load-'+p).hide();
            $('#lumilab-indicador-'+ p ).html(data);
            $('#lumilab-pre-'+ p ).hide();
            if(p=='21')
            $('#botoes-inscricoes').show();
            if(p=='22')
            $('#botoes-certificados').show();
            
 
           }
      });}",
     View::POS_READY,
     'lumi-lab-cpiu'.rand(1,9999)
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
    $('#botoes-".($posicao=="21"?'inscricoes':'certificados')."').hide();

    sel_perfil=''
    desmarcado = false
    scontadores={'genero[]':0,'racial[]':0,'escolaridade[]':0,'idade[]':0}
    $('#ul_form_perfil li label input').each(function (){
      if ($(this).is(':checked'))
      {
        sel_perfil+=$(this).attr('name')+':'+$(this).val()+';'
      }
      else
      desmarcado=true
      scontadores[$(this).attr('name')]++
              
    });
    if(!desmarcado)
      sel_perfil=''
      //alert(sel_perfil)
    vajax = 'S'
    if(sel_perfil!='')
    vajax='N'    
       
       $.ajax({
          url: '". Yii::$app->request->baseUrl. '?r=lumilab/sample' ."',
          type: 'get',
          data: {
                    ajax:vajax,
                    intervalo:'".@$_GET["intervalo"]."',
                    indicador: '".$dopcoes[0]."' , 
                    opcao:'".$vvv."', 
                    posicao:'".$posicao."',
                    dti:'".$datas[0]."',
                    dtf:'".$datas[1]."',
                    acao:'avancado',
                    cid:'".$datas[2]."',
                    areas:".$areaval.",   
                    sel_perfil:sel_perfil,                
                    v_contadores:scontadores['genero[]']+','+scontadores['racial[]']+','+scontadores['escolaridade[]']+','+scontadores['idade[]'],
                    _csrf : '". Yii::$app->request->getCsrfToken() ."'
                },
          success: function (data) {
            $('#lumilab-indicador-".$posicao."').show();
            $('#lumilab-indicador-".$posicao."').html(data);
           
            $('#img-load-".$posicao."').hide();
            $('#botoes-".($posicao=="21"?'inscricoes':'certificados')."').show();

          }
     });});",
    View::POS_READY,
    'lumi-lab-cpp-'.$dopcoes[0].'-'.$vvv
);
    }
}
$xx=1;
$yy=1;
foreach($layouts as $ll =>$cc)
{

            foreach($cc as $c1 =>$v1)
            { 
                $dd='';
                if(in_array($yy.$xx,array("21","22","31","32","41")))
                {
                    $dd='#';
                }
                $this->registerJs("carregaPosicao('".$yy.$xx."','$dd');", View::POS_READY,'lumi-lab-cpp'.rand(1,9999));
                
                $xx++;
            }
            $xx=1;
            $yy++;
}
?>
