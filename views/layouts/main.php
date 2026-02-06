<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;
use yii\bootstrap5\ActiveForm;
use app\models\LoginForm;
use app\controllers\LumilabController;





AppAsset::register($this);
\yii\bootstrap5\BootstrapPluginAsset::register($this);


$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
//$this->registerCssFile('@web/css/site.css');



if(isset(Yii::$app->user->identity->id))
{
    $l_cursos = LumilabController::buscaCursos();
    
}
$l_areas = LumilabController::buscaAreas();

$minhasConfig=LumilabController::buscaConfig(array("data_inicial_fixa","data_final_fixa","email_admin"));

$datas =LumilabController::pegaDatas();
$dtInicio = $datas[0];
$dtFim = $datas[1];
$dataIntervalo = isset($_GET["intervalo"])?$_GET["intervalo"]:"ultimo_ano";

$debug=false;   

$block = @$_GET["intervalo"]=="tudo"?true:false;

$this->title = 'Lumilab ';
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">

<?php $this->beginBody();
 require("header.php");
 
?>
<main class="flex-shrink-0">
  <div class="container">
      <div class="row">
      <!-- MENU ESQUERDO -->
      <div class="col-3">
        <div id="menu-esquerdo" class="d-flex flex-column flex-shrink-0 p-3">
          <!-- LOGIN -->
          <?php 
         
            if(!isset(Yii::$app->user->identity->id) && @$_GET["r"]!="site/login"){ ?>

          <?= Alert::widget() ?>
          <p class="text-center">É professor do Lúmina?</p>
          <form id="form-login-esquerdo" method="post" action="web/?r=site/login">
            <input type="email" placeholder="E-mail" name="LoginForm[username]" class="">
            <input type="hidden" name="_csrf" value="<?php echo Yii::$app->request->getCsrfToken()?>">
            <input type="password" placeholder="Senha" name="LoginForm[password]" class="">
            <div class="mb-2 mt-2 form-check">
              <input type="checkbox" name="LoginForm[rememberMe]" class="form-check-input" id="exampleCheck1">
              <label class="form-check-label" for="exampleCheck1">Lembrar meus dados</label>
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-1">Entrar</button>
          </form>
          <p id="esqueci-senha" class="w-100 text-center"><a href="#">Utilize sua senha do Lúmina</a></p>
          <!-- LOGIN -->
          
          <?php  } ?>
          <!-- VAI NO LUGAR DO FORM QUANDO LOGADO -->
           <?php if (isset(Yii::$app->user->identity->id)){ ?>

            <div class="dropdown mb-3">
            <a href="#" class="d-flex align-items-center link-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
              <img src="https://sbcf.fr/wp-content/uploads/2018/03/sbcf-default-avatar.png" alt="" width="32" height="32" class="rounded-circle me-2">
              <strong><?php echo @Yii::$app->user->identity->name?></strong>
            </a>
            <ul class="dropdown-menu text-small shadow" style="">
              <li><a class="dropdown-item" href="#">Acessar o Lúmina</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                  <form action="web/?r=site/logout" method="post">
                  <input type="hidden" name="_csrf" value="<?php echo @Yii::$app->request->getCsrfToken()?>">
                    <button type="submit" class="dropdown-item" style="width:100%">Sair</button>
               </form>
             </li>
            </ul>
          </div>
                 
                <?php }?>
          
          <!-- MENU LINKS PADRAO -->
          <ul class="nav nav-pills flex-column mb-auto">
          <li class="nav-item"> <a href="?r=lab/novo" class="nav-link <?php echo (in_array(@$_GET["r"],array("lab/novo","lumilab/novo")) or !isset($_GET["r"]) or @$_GET['r']=="")?'active2':''?>"><i class="icone icone-dados-plataforma"></i> Dados da Plataforma </a> </li>
            <li> <a href="?r=site/ajuda" class="nav-link <?php echo @$_GET["r"]=="site/ajuda"?'active2':''?>"><i class="icone icone-ajuda"></i> Ajuda</a> </li>
            <li> <a href="?r=site/equipe" class="nav-link <?php echo @$_GET["r"]=="site/equipe"?'active2':''?>"><i class="icone icone-equipe"></i> Equipe</a> </li>
            <li> <a href="?r=site/termos" class="nav-link <?php echo @$_GET["r"]=="site/termos"?'active2':''?>"><i class="icone icone-termos"></i> Termos de Uso</a> </li>
            <li> <a href="?r=site/politica" class="nav-link <?php echo @$_GET["r"]=="site/politica"?'active2':''?>"><i class="icone icone-politica"></i> Política de Privacidade</a> </li>
          </ul>
          <!-- FIM MENU LINKS PADRAO -->
          <?php if (isset(Yii::$app->user->identity->id)) { ?>
            <br>
          <p class="titulo texto-com-icone mb-0"><i class="icone icone-escolaridade"></i> <strong>Meus Cursos</strong></p>
          <ul class="nav nav-pills flex-column mb-3">
          <?php 
              $cId= @$l_cursos[0]["id"];
              $i=1;
              
                foreach($l_cursos as $i_curso => $v_curso){
                  // Determine nav link class
                  $isFirstAndActive = (!isset($_GET["cid"]) && $i_curso==0 && in_array(@$_GET["r"],array("lab/avancado","lumilab/questionario","lumilab/estatistica","lumilab/forum")));
                  $isSelectedCourse = (@$_GET["cid"]==@$v_curso["id"] && !in_array(@$_GET['r'],array("","lab/novo")));
                  $navClass = ($isFirstAndActive || $isSelectedCourse) ? 'nav-link active' : 'nav-link';
                  
                  // Determine route for href
                  $currentRoute = @$_GET['r'];
                  $isLumilabRoute = (substr($currentRoute, 0, 7) == "lumilab");
                  $isInvalidRoute = ($currentRoute == "lumilab/avancado" || $currentRoute == "lumilab/novo");
                  $route = $isLumilabRoute && !$isInvalidRoute ? $currentRoute : 'lab/avancado';
                  
                  // Build interval parameter
                  $interval = isset($_GET["intervalo"]) ? "&intervalo=".@$_GET["intervalo"] : "&intervalo=ultimo_ano";
                  ?>
                  <li class="<?php echo $navClass; ?>"><a href="?r=<?php echo $route; ?>&cid=<?php echo $v_curso["id"]?><?php echo $interval; ?>">
                  <?php echo $v_curso["cursoDescricao"];?></a></li>
                <?php 
                  $cId=@$_GET["cid"]==@$v_curso["id"]?$v_curso["id"]:$cId;
                  $i++;
              }?>
          </ul>
          <?php }?>
          <!-- FIM MEUS CURSOS -->
          <?php if(in_array(@$_GET['r'],array("lab/avancado","lab/novo","lumilab/avancado","lumilab/novo","lumilab/questionario","lumilab/estatistica","lumilab/forum")) or !isset($_GET['r']) or @$_GET["r"]==""){
?>        <br>
          <!-- MENU INTERVALO -->
          <p class="titulo texto-com-icone"><i class="icone icone-intervalo"></i> <strong>Intervalo</strong></p>
          <ul class="nav nav-pills flex-column mb-auto">
            <?php 
              $acao = "lab/novo";
              if(isset($_GET["r"]) && @$_GET["r"]!="")
              $acao = in_array(explode("/",@$_GET["r"])[1],array("novo","avancado"))?"lab/".explode("/",@$_GET["r"])[1]:@$_GET["r"];
            ?>
                <!--li class=" nav-link <?php echo $dataIntervalo=="ano_atual"?'active':''?>"><a href="?r=<?php echo @$acao;?>&intervalo=ano_atual<?php echo '&cid='.@$cId?>"> Ano Atual</a></li>
                <li class=" nav-link <?php echo $dataIntervalo=="ultimo_mes"?'active':''?>"><a href="?r=<?php echo @$acao;?>&intervalo=ultimo_mes<?php echo '&cid='.@$cId?>"> Último Mês</a></li>
                !-->
                <li class="nav-link <?php echo $dataIntervalo=="ultimo_ano"?'active':''?>"><a href="?r=<?php echo @$acao;?>&intervalo=ultimo_ano<?php echo '&cid='.@$cId?>">Último ano (<?php echo (date('Y')-1);?>)</a></li>
                <li class="nav-link <?php echo $dataIntervalo=="penultimo_ano"?'active':''?>"><a href="?r=<?php echo @$acao;?>&intervalo=penultimo_ano<?php echo '&cid='.@$cId?>"><?php echo (date('Y')-2);?></a></li>
                <li class="nav-link <?php echo  $dataIntervalo=="tudo"?'active':''?>"><a href="?r=<?php echo @$acao;?>&intervalo=tudo<?php echo '&cid='.@$cId?>">Todo o Período</a></li>
                

          </ul>
          
          <!-- FIM MENU INTERVALO -->
          <!-- INICIO PERSONLAIZADO -->
          <?php if($debug){?>
          <br>
          <p class="titulo texto-com-icone"><i class="icone icone-intervalo"></i><strong>Personalizado</strong></p>
          <form id="form-personalizado-esquerdo" class="form-busca" action="">
            
                    <input type="hidden" name="r" value="<?php echo @$_GET["r"]?>">
                    <input type="hidden" name="cid" value="<?php echo @$cId?>">
                    <div class="form-group">
                    <label>
                        Início:</label>
                        <br>
                        <input type="date"  value="<?php echo $dtInicio ?>" name="dt-inicio" />
                    </div>
                    <div class="form-group">
                    <label>
                        Fim:</label>
                        <br>
                        <input type="date"  value="<?php echo $dtFim ?>" name="dt-fim" />
                    </div>
                    <br>
                    <div class="form-group">
                            <button class="btn btn-primary w-100 mb-1" type="submit">Filtrar</button>
                    </div>
                </form>  
                      <?php } } ?>
                    
                      <?php if (in_array(@$_GET["r"],array("lab/avancado","lab/novo","lumilab/avancado","lumilab/novo","lumilab/estatistica","lumilab/forum")) or !isset($_GET['r']) or @$_GET["r"]=="") { ?>
            <br>
            <p class="titulo texto-com-icone mb-0"><i class="icone icone icone-filtro-plataforma"></i> <strong>Filtros da Plataforma</strong></p> 
            <hr>
            <form action="" style="display:block !important">
            <input type="hidden" name="r" value="<?php echo "lumilab/". @explode("/",@$_GET["r"])[1]?>">
            <?php 
            if(!in_array(@$_GET["r"],array("lab/novo","lumilab/novo"))){?>
            <input type="hidden" name="cid" value="<?php echo @$cId?>">
            <?php }?>
              <input type="hidden" name="intervalo" value="<?php echo @$_GET["intervalo"]?>">
              
            <p class="titulo texto-com-icone mb-0"><i class="icone icone-cargah"></i> <strong>Carga horária</strong></p>
            
            <?php
            // Check if carga values are selected
            $carga = isset($_GET["carga"]) ? (array)$_GET["carga"] : [];
            $isChecked_0_20 = empty($carga) || in_array("0-20", $carga) ? 'checked="checked"' : '';
            $isChecked_20_40 = empty($carga) || in_array("20-40", $carga) ? 'checked="checked"' : '';
            $isChecked_40_1000 = empty($carga) || in_array("40-1000", $carga) ? 'checked="checked"' : '';
            ?>
            
            <label class="toggle form-group" style="display:block !important">
          <input type="checkbox" <?php echo $isChecked_0_20; ?> name="carga[]" value="0-20" class="toggle-checkbox" type="checkbox">
            
            Até 20h
            <div class="toggle-switch" style="flex-grow: 1 !important;text-align: end !important;float: right !important;"></div>
          </label>
          <br>
          <label class="toggle form-group" style="display:block !important">
          <input type="checkbox" <?php echo $isChecked_20_40; ?> name="carga[]" value="20-40" class="toggle-checkbox" type="checkbox">
            De 20h a 40h
            <div class="toggle-switch" style="flex-grow: 1 !important;text-align: end !important;float: right !important;"></div>
          </label>
          <br>
          <label class="toggle form-group" style="display:block !important">
          <input type="checkbox" <?php echo $isChecked_40_1000; ?> name="carga[]" value="40-1000" class="toggle-checkbox" type="checkbox">
            
            Mais de 40h
            <div class="toggle-switch" style="flex-grow: 1 !important;text-align: end !important;float: right !important;"></div>
          </label>
          <br>
                    <div class="form-group">
                            <?php
                            $btnDisabled = $block ? 'disabled="disabled"' : '';
                            $btnText = $block ? 'Indisponível para #TodoPeríodo' : 'Filtrar';
                            ?>
                            <button <?php echo $btnDisabled; ?> class="btn btn-primary w-100 mb-1" type="submit"><?php echo $btnText; ?></button>
                    </div>
                      
          <hr>
          
                      
              <p class="titulo texto-com-icone mb-0"><i class="icone icone-area"></i> <strong>Área</strong></p>
              
              
           
              <?php 
                if(isset($_GET["dt"])){?>
              <input type="hidden" name="dt" value="<?php echo @$_GET["dt"]?>">
              <?php }?>
              <input type="hidden" name="dt-inicio" value="<?php echo @$dtInicio?>">
              <input type="hidden" name="dt-fim" value="<?php echo @$dtFim?>">
              <input type="hidden" name="intervalo" value="<?php echo @$_GET["intervalo"]?>">
                
          <?php 
                $areas_sel = [];
                if(isset($_GET["area"]))
                  $areas_sel = @$_GET["area"];
                foreach($l_areas as $i_area => $v_area){
                  // Check if area should be selected
                  $isAreaSelected = (in_array($v_area["areaCursoId"],$areas_sel) || sizeof($areas_sel)==0) ? "checked='checked'" : '';
                  ?>
                  <label class="toggle form-group" style="display:block !important">
                    <input type="checkbox" <?php echo $isAreaSelected; ?> name="area[]" value="<?php echo $v_area["areaCursoId"];?>" class="toggle-checkbox">
                  <?php echo $v_area["areaCursoDescricao"];?>
                  <div class="toggle-switch" style="flex-grow: 1 !important;text-align: end !important;float: right !important;"></div>
                  </label>
                <br>
              <?php } ?>
                
          <br>
                    <div class="form-group">
                            <?php
                            $btnDisabled = $block ? 'disabled="disabled"' : '';
                            $btnText = $block ? 'Indisponível para #TodoPeríodo' : 'Filtrar';
                            ?>
                            <button <?php echo $btnDisabled; ?> class="btn btn-primary w-100 mb-1" type="submit"><?php echo $btnText; ?></button>
                    </div>
                </form>
                
          <?php }  ?>    
                </div>

                
          </div>
          
          <div id="conteudo-direito" class="col-9">

              <?= $content ?>
             
            </div>
            
    </div>
  </div>    
</main>
<?php require("footer.php");?>
<?php $this->endBody() ?>
<script type="text/javascript" src="//fastly.jsdelivr.net/npm/echarts@5/dist/echarts.min.js"></script>
</body>
</html>
<?php $this->endPage() ?>
