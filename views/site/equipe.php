<?php

/** @var yii\web\View $this */

use yii\helpers\Html;

#$this->title = 'Equipe do lumi.lab';
#$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="row com-borda">
            <div class="row">
                <div class="col-12">
                    <h5 class="text-center mb-0"><strong>Equipe</strong></h5>
                </div>
          </div>
        </div>
        <!-- FIM QUADRO TITULO -->

        <!-- QUADRO EQUIPE -->
        <div align="center" class="row com-borda mt-3 mb-4">
            
        <div class="col-4">
                    <div class="card border-none card-equipe">
                    <p class="text-center mb-0"> 
                    <?php echo Html::img('https://ui-avatars.com/api/?name=Tony+Bignardi&background=random&size=300',array('class'=>'card-img-top'))?></p>
                        <div class="card-body">
                            <h5 class="card-title text-center">Tony Bignardi</h5>
                            <p class="card-text">Doutourando no PGIE-UFRGS. Desenvolvedor da ferramenta.</p>

                            <!--a  href="#" class="card-link">Link para um lugar</a>
                            <br>
                            <a href="#" class="card-link">Link para outro lugar</a>!-->
                        </div>
                    </div>
                </div>    
                <div class="col-4">
                    <div class="card border-none card-equipe">
                      <p class="text-center mb-0"> 
                      <?php echo Html::img('https://ui-avatars.com/api/?name=Gabriela+Trindade&background=random&size=300',array('class'=>'card-img-top'))?>
                        </p>
                        <div class="card-body">
                            <h5 class="card-title text-center">Gabriela Trindade Perry</h5>
                            <p class="card-text">Coordenadora e Orientadora do projeto (Doutorado)</p>

                            <!--a  href="#" class="card-link">Link para um lugar</a>
                            <br>
                            <a href="#" class="card-link">Link para outro lugar</a>!-->
                        </div>
                    </div>
                </div>            
                <div class="col-4">
                    <div class="card border-none card-equipe">
                    <p class="text-center mb-0"> 
                    <?php echo Html::img('https://ui-avatars.com/api/?name=Leonardo+Chaves&background=random&size=300',array('class'=>'card-img-top'))?></p>
                        <div class="card-body">
                            <h5 class="card-title text-center">Leonardo Chaves</h5>
                            <p class="card-text">Web desiner e Apoio no desenvolvimento da plataforma</p>

                           <!--a  href="#" class="card-link">Link para um lugar</a>
                            <br>
                            <a href="#" class="card-link">Link para outro lugar</a>!-->
                        </div>
                    </div>
                </div>            
                        
               
                </div>