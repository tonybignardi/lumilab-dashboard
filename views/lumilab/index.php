<?php
/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var app\models\LoginForm $model */

use yii\bootstrap5\ActiveForm;


/** @var yii\web\View $this */
use yii\web\View;
use yii\bootstrap5\Html;


?>
    <div id="principal">
        <div class="row">
            <div class="col-md-2">
            <div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    

    <div class="row">
        

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'fieldConfig' => [
                    'template' => "{label}\n{input}\n{error}",
                  
                ],
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password')->passwordInput() ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                'template' => "<div class=\"custom-control custom-checkbox\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
            ]) ?>
            <div class="form-group">
                <div>
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>


        </div>
    
</div>
                <div class="row login-aqui">
                   
                    <div class="form-group login">
                        <ul>
                            <li>
                        <?php echo Html::img('@web/assets/img/ico-ajuda.svg');?> Ajuda
                            </li><li>
                        <?php echo Html::img('@web/assets/img/ico-termos.svg');?> Termos
                            </li><li>
                        <?php echo Html::img('@web/assets/img/ico-politica.svg');?> Política
                        </li>
                    </ul>
                    <?php
                            //echo "Bem-vindo(a)<br>" .$USER->firstname ." ". $USER->lastname ."($USER->id)";
                            
                        ?>
                    <a class="btn btn-primary btn-sm" href="?r=lumilab&page=avancado<?php echo $dataIntervalo!=""?'&dt='.$dataIntervalo:"" ?>">Avançado</a>
                    <br><br>
                    <a class="btn btn-success btn-sm" href="?r=lumilab&page=<?php echo $dataIntervalo!=""?'&dt='.$dataIntervalo:"" ?>">Visão Geral</a>
                    </div>
                </div>
                <?php 
                    if($qualPage=="avancado")
                    {
                ?>
                 <div class="row login-aqui">
                    <label><?php echo Html::img('@web/assets/img/ico-meuscursos.svg');?> Meus Cursos </label>
                 <?php 
                        echo $meus_cursos;
				       //  // local_lumilab_dashboard_get_list('meuscursos',$dataIntervalo);
				?>
				</div>
                <?php } ?>
                <div class="row login-aqui">
                <span><?php echo Html::img('@web/assets/img/ico-intervalo.svg');?> Intervalo </span>    
                <ul class="ul-filtro-aqui">
                <li class="<?php echo  $dataIntervalo=="todo"?'ativo2':''?>"><a href="?r=lumilab&dt=todo<?php echo  $qualPage=="avancado"?'&page=avancado':'' ?>">Todo o Periodo</a></li>
                <li class="<?php echo $dataIntervalo=="um"?'ativo2':''?>"><a href="?r=lumilab&dt=um<?php echo $qualPage=="avancado"?'&page=avancado':'' ?>">Último mês</a></li>
                <li class="<?php echo $dataIntervalo=="us"?'ativo2':''?>"><a href="?r=lumilab&dt=us<?php echo $qualPage=="avancado"?'&page=avancado':'' ?>"> Última semana</a></li>
                    </ul>
                    <form class="form-busca" action="">
                    
                            <input type="hidden" name="page" value="<?php echo $qualPage; ?>">
                            <input type="hidden" name="r" value="lumilab">
                            <div class="form-group">
                            <label>
                                Início:</label>
                                <input type="date" value="<?php echo $dtInicio ?>" name="dt-inicio" min="2010-01-01" max="<?php echo date('Y-m-d')?>" />
                            
                            </div>
                            <div class="form-group">
                            <label>
                                Fim:</label>
                                <input type="date" value="<?php echo $dtFim ?>" name="dt-fim" min="2010-01-01" max="<?php echo date('Y-m-d')?>" />
                            </div>
                            <br>
                            <div class="form-group">
                                    <button class="btn btn-primary btn-sm ativo2" type="submit">Filtrar</button>
                            </div>
                        </form>  
                    </div>
                    <?php 
                    if($qualPage=="avancado")
                    {
                ?>
                 <div class="row login-aqui login">
                
                <h5>&nbsp;<?php echo Html::img('web/assets/img/icons/users.svg');?>&nbsp;Inscritos</h5>
                <ul class="lista-com-toggle">
                    <li>
                        <p>Até 500 Alunos</p>
                        <label class="toggle">
                            <input class="toggle-checkbox" type="checkbox">
                            <div class="toggle-switch"></div>
                        </label>
                    </li>
                    <li>
                        <p>De 500 e 2,5 mil</p>
                        <label class="toggle">
                            <input class="toggle-checkbox" type="checkbox">
                            <div class="toggle-switch"></div>
                        </label>
                    </li>
                    <li>
                        <p>De 2,5 mil e 5 mil</p>
                        <label class="toggle">
                            <input class="toggle-checkbox" type="checkbox" checked="">
                            <div class="toggle-switch"></div>
                        </label>
                    </li>
                    <li>
                        <p>Mais de 5 mil</p>
                        <label class="toggle">
                            <input class="toggle-checkbox" type="checkbox">
                            <div class="toggle-switch"></div>
                        </label>
                    </li>
                </ul>
                  
                <h5>&nbsp;<?php echo Html::img('@web/assets/img/ico-area.svg');?>&nbsp;Áreas</h5>
                 <?php 
                    echo $areas;
				       //  // local_lumilab_dashboard_get_list('areas',$dataIntervalo);
				?>
				</div>
                               
                <?php } ?>
                
                    
            </div>
            <div class="col-md-10">
                    <div class="row">
                        <?php 
                        if($qualPage!="avancado")
                        { ?>

                        <div class="col-md-12 grafico">
                            <?php
                            $tempos["certificados-incricoes-usuarios"]=microtime(true);
                            echo @$g_todos[0];
                            // local_lumilab_dashboard_get_graph_line(array('certificados','inscricoes','usuarios'),$dataIntervalo);
                            
                            $tempos["certificados-incricoes-usuarios"]-=microtime(true);
                            ?>

                            
                        </div>
                        <?php }
                        else { ?>
                            <h2 class="curso-selecionado">Curso selecionado</h2>

                        <?php }
                        ?>
                    
                    </div>
                    <div class="row">
                    <div class="col-md-2">
                                <div class="row painel-usuario">
								<?php 
                                    echo $cursos;
									//// local_lumilab_dashboard_get_list('cursos',$dataIntervalo);
									?>
								</div>
								<div class="row painel-usuario">
								<?php 
                                    echo $usuarios;
									//// local_lumilab_dashboard_get_list('usuarios',$dataIntervalo);
									?>
								</div>
                                <div class="row painel-usuario">
								<?php 
                                    echo $certificados;
									// local_lumilab_dashboard_get_list('certificados',$dataIntervalo);
									?>
								</div>
                                
                        </div>  
                        
                       
                        
								
                    
                    <div class="col-md-5">
                             <div class="row por-curso">
								<?php 
                                    echo $genero;
									
									?>
								</div>
                                <div class="row por-curso">
							<?php 
                                    echo $racial;
									// local_lumilab_dashboard_get_list('racial',$dataIntervalo,true);
							?>
                            </div>
								
			
                                

                        </div>
                            
                <div class="col-md-5"> 
                        <div class="row por-curso">
								<?php 
                                    echo $idades;
									// local_lumilab_dashboard_get_list('idades',$dataIntervalo,true);
									?>
								</div>
                         <div class="row por-curso">
							<?php 
                                    echo $escolaridade;
									// local_lumilab_dashboard_get_list('escolaridade',$dataIntervalo,true);
							?>
                            </div>
                      
                        
                    

             </div>
                        </div>
                    
                <?php 
                if($qualPage!="avancado")
                { ?>
                <div class="row titulo-graficos">
                <div class="col-md-12">
                    <div class="row">
                    <div class="col-md-6 por-curso">
                
                <?php 
                  $tempos["inscritos_no_curso"]=microtime(true);
                    echo $inscritos_no_curso;
                  $tempos["inscritos_no_curso"]-=microtime(true);
                ?>
                  
                </div>
                <div class="col-md-6 por-curso">
                <?php 
                  $tempos["certificados_no_curso"]=microtime(true);
                    echo $certificados_no_curso;
                    $tempos["certificados_no_curso"]-=microtime(true);
                ?>
                  
                </div>
                </div>
                </div>

                </div>
                <?php 
                }else {?>
                <div class="col-md-12">
                <div class="row titulo-graficos">
                    <h3> Datas </h3>
                </div>

                    <div class="row">
                 <div class="col-md-6 grafico-pequeno">
                 <?php
                           $tempos["inscricoes-graf"]=microtime(true);
                       echo $g_inscricoes[0];
                       $tempos["inscricoes-graf"]-=microtime(true);
                       
                   ?>
                     <b style="font-weight:bold">Inscrições: <?php echo @$g_inscricoes[1][0] ?></b>
                     <br>
                     <br>
                </div>
                <div class="col-md-6 grafico-pequeno">
                <?php
                     $tempos["certificados-graf"]=microtime(true);
                    echo $g_certificados[0];
                    $tempos["certificados-graf"]-=microtime(true);
                            
                    ?>
                    <b style="font-weight:bold">Certificados: <?php echo @$g_certificados[1][0] ?></b>
                    <br>
                    <br>
                </div>
                </div>
                </div>
                <div class="col-md-12">
                <div class="row titulo-graficos">
                    <h3> Tempo no Curso </h3>    
                </div>
                    <div class="row">
                 <div class="col-md-6 grafico-pequeno">
                 <?php
                    $tempos["dias_curso-graf"]=microtime(true);
                        echo $g_dias_curso[0];
                        
                    $tempos["dias_curso-graf"]-=microtime(true);
                   ?>
                <p style="color:#aaa;">Quantidade de alunos x dias de acesso ao curso</p> 
                </div>
                <div class="col-md-6 grafico-pequeno">
                <?php
                    $tempos["dias_certificado-graf"]=microtime(true);
                        echo $g_dias_certificado[0];
                    $tempos["dias_certificado-graf"]-=microtime(true);
                   ?>
                <b>Certificados: <?php echo @$g_dias_certificado[1][0] ?></b>
                <p style="color:#aaa;">Quantidade de alunos e dias navegados para emissão de certificado</p>      
                </div>
                </div>
                </div>

                <div class="col-md-12">
                <div class="row titulo-graficos">
                    <h3> Notas </h3>    
                </div>
                    <div class="row">
                 <div class="col-md-6 grafico-pequeno">
                 <?php
                        $tempos["notas-1tent-notafinal-graf"]=microtime(true);
                        echo $g_1tent[0];
                        // local_lumilab_dashboard_get_graph_line(array('1tent','notafinal'),$dataIntervalo);
                        $tempos["notas-1tent-notafinal-graf"]-=microtime(true);
                   ?>
                </div>
                <div class="col-md-6 grafico-pequeno">
                <?php
                        $tempos["notas-1tent_certificado-notafinal_certificado-graf"]=microtime(true);
                            echo $g_1tent_certificado[0];   
                        // local_lumilab_dashboard_get_graph_line(array('1tent_certificado','notafinal_certificado'),$dataIntervalo);
                           $tempos["notas-1tent_certificado-notafinal_certificado-graf"]-=microtime(true);
                   ?>  
  
                </div>
                </div>
                </div>

                
                <?php } ?>

            
            </div>
    </div>

      <?php
        if($debug==true)
        {
            foreach($tempos as $rotulo =>$temp)
            {
                echo $rotulo . "  (". number_format($temp,2,".",",") ."s)<br>";
            }
        }
    //echo $OUTPUT->footer();

    //$pagehtml .= $OUTPUT->header();
    //$pagehtml .= $OUTPUT->heading("$titulo heading", 2);
 
?>


