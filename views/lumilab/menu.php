<?php
use app\controllers\LumilabController;
if(isset(Yii::$app->user->identity->id))
{
    $l_cursos = LumilabController::buscaCursos();
    $cId= @$l_cursos[0]["id"];
}

  ?>
<div class=" mb-3">
          <div class="row" style="padding: 0;margin: 0 -23px;">
            <div class="col-3">
              <a href="?r=lab/avancado&cid=<?php echo !isset($_GET["cid"])?$cId:@$_GET["cid"];?><?php echo isset($_GET["intervalo"])?"&intervalo=".$_GET["intervalo"]:"&intervalo=ultimo_ano";?>" class="btn <?php echo in_array(@$_GET['r'],array("lumilab/avancado","lab/avancado"))?'btn-primary':'btn-outline-primary'?> btn-sm w-100">Visão Geral</a>
            </div>
            <div class="col-3">
              <a href="?r=lumilab/questionario&cid=<?php echo !isset($_GET["cid"])?@$cId:$_GET["cid"]?><?php echo isset($_GET["intervalo"])?"&intervalo=".$_GET["intervalo"]:"&intervalo=ultimo_ano";?>" class="btn <?php echo @$_GET['r']=="lumilab/questionario"?'btn-primary':'btn-outline-primary'?> btn-sm w-100">Questionários</a>
            </div>
            <div class="col-3">
              <a href="?r=lumilab/forum&cid=<?php echo !isset($_GET["cid"])?$cId:@$_GET["cid"]?><?php echo isset($_GET["intervalo"])?"&intervalo=".$_GET["intervalo"]:"&intervalo=ultimo_ano";?>" class="btn <?php echo @$_GET['r']=="lumilab/forum"?'btn-primary':'btn-outline-primary'?>  btn-sm w-100">Interação nos Fóruns</a>
            </div>
            <div class="col-3">
              <a href="?r=lumilab/estatistica&cid=<?php echo !isset($_GET["cid"])?$cId:@$_GET["cid"]?><?php echo isset($_GET["intervalo"])?"&intervalo=".$_GET["intervalo"]:"&intervalo=ultimo_ano";?>" class="btn <?php echo @$_GET['r']=="lumilab/estatistica"?'btn-primary':'btn-outline-primary'?> btn-sm w-100">Estatísticas</a>
            </div>
          </div>
</div>

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

<div class="row com-borda mb-3">
          <!-- PRIMEIRA LINHA - SUMÁRIO -->
          <?php $temFiltroLumina = in_array(@$_GET["r"],array("lumilab/questionario"))?true:false;
          ?>
          <div class="row">
            <?php
            $nrow= 12;
            #if(in_array(@$_GET["r"],array("lumilab/estatistica")))
            #$nrow=8;
            ?>
            <div class="col-<?php echo $nrow;?>">
              <h5><strong><?php echo @$curso_descricao;?></strong></h5>
              <p class="texto-com-icone"><i class="icone icone-redondo-preto"></i> Dados curso:     
              <?php 
               if(!in_array(@$_GET["r"],array("lumilab/estatistica")))
               {?>
              <span class=" btn tag-filtro btn-sm">De <?php echo $dtInicio?> a <?php echo $dtFim?></span>
              <?php }else {?>
                <?php 
                  
                  echo '  <span style="text-decoration:underline;color:'. ($totcurso==0?"red":""). '">'.$totcurso ." Concluintes</span>";
                }?>
            </p>
              <?php if(!$temFiltroLumina) {?>
              <p class="texto-com-icone mb-0"><i class="icone icone-redondo-azul"></i> Dados Lúmina:  
              <?php 
               if(in_array(@$_GET["r"],array("lumilab/estatistica")))
               {?>
                <?php 
                  echo '  <span style="text-decoration:underline;color:'. ($totlumina==0?"red":""). '">'.$totlumina ." Concluintes</span></p><br><p style='padding-bottom:-10px !important'>";
                }?>
              <span class=" btn tag-filtro btn-sm ">De <?php echo $dtInicio?> a <?php echo $dtFim?></span>
              <span class=" btn tag-filtro btn-sm ">Carga Horária: <?php echo $cargadesc;?> </span>
              <span class=" btn tag-filtro btn-sm ">Área: <?php echo $areadesc;?> </span>
              </p>
              <?php }?>
            </div>
            
          </div>
</div>