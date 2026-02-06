
<?php
use yii\bootstrap5\Html;
use yii\bootstrap5\Nav;
use yii\bootstrap5\Modal;
#Yii::$app->registerPlugin('modal');
?>

<div class="container">
  <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
        <a href="index.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-dark text-decoration-none">
         <?php echo Html::img('@web/assets/img/logos/logo.svg')?>
        </a>
        <ul class="nav">
        <?php  
        
        if(isset(Yii::$app->user->identity->id) && @Yii::$app->user->identity->username==@$minhasConfig["email_admin"])
    {
        
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav mt-3','style'=>'flex-grow:0'] ,
        'items' => [
            
            ['label' => 'Administrar','url' => ['/site/contact'],'items'=>[
                ['label'=>'Carga Horária','url'=>['/curso-carga']],
                ['label'=>'Autorização de Usuários','url'=>['/curso-responsavel']],
                ['label'=>'Configurações','url'=>['/configuracao']],
            ],'options'=>['class' => 'btn btn-sm btn-secondary','style'=>'border-radius:10px']]
                    
        ]
    ]);
}

$exibe = "";
$urlexibe = array("novo"=>"kX13JPmxILo","avancado"=>"D6rDM42OVSM","questionario"=>"eMuHlwN262A","forum"=>"-LfVfHKAq5w","estatistica"=>"MbbutH9Wq3o");
if(isset($_GET['r']))
{
    
    $exibe = @explode("/",@$_GET["r"])[1];
    
    
 
}
else
$exibe = "novo";
if(in_array($exibe,array("novo","avancado","questionario","forum","estatistica")))
{
?>
<li class="nav-item">
<!-- Botão para acionar modal -->
<?php Modal::begin([
    'title' => 'Ajuda em Vídeo',
    'toggleButton' => ['style'=>'margin-right:0px;margin-top:10px;border:none;background-color:#FFF','label'=>Html::img('@web/assets/img/ajuda.png',['height'=>50])],
    'id'=>'modalExemplo',
    'size'=>'modal-lg',
    
    
]);

echo '<div align="center"><iframe width="560" height="315" src="https://www.youtube.com/embed/'.@$urlexibe[$exibe].'" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe></div>';
#echo $exibe;

Modal::end();
}
?>


</li>
      
<li class="nav-item"><a href="#" class="nav-link">
          <?php echo Html::img('@web/assets/img/logos/logo_lumina.svg')?>
        </a></li>

</ul>
</header>


</div>      
  