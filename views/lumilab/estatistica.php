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
  $opcoes=LumilabController::pegaBotoesIndicadores('avancado');
  $datas=LumilabController::pegaDatas();
  $xx=1;
  $yy=1;
?>

<form action="" method="post">
<div class="modal fade" id="modal_relo" tabindex="-1" role="dialog" aria-labelledby="modal_relo" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modal_relo">Atenção</h5>
        <button type="button" onclick="$('#modal_relo').modal('toggle')" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Para executar a regressão logística para analíse da probabilidade de <b>obtenção de </b>,
        você deve escolher a variável a ser correlacionada.
      </div>
      <div class="modal-footer">
        <button type="button" onclick="$('#modal_relo').modal('toggle')" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

<div class="row com-borda">
<div class="col-md-2">
  <strong>1. Objetivo </strong>
</div>
<div class="col-md-10" id="form_objetivo">

  <input type="button" id="btn_comparar" value="Comparar - Willcoxon-Mann-Whitney"
  class="btn btn-primary btn-sm w-30">
  
  <input type="button" id="btn_correlacionar" value="Correlacionar - Regressão Linear"
  class="btn btn-outline-primary btn-sm w-30">

</div>
</div>
<div class="row com-borda mt-3">
<div class="col-md-12" id="form_variaveis">
  <strong id="msg_vd">2. Escolha a variável</strong>
<br>
<input type="button" class="btn btn-primary btn-sm w-30" id="nf_concluintes" name="op_variavel"
value="Nota final, concluintes">  

<input type="button" class="btn btn-outline-primary btn-sm w-30" value="Notas 1 tentativa, concluintes"  id="n1_concluintes" name="op_variavel">
  
  
  <input type="button" class="btn btn-outline-primary btn-sm w-30" id="dias_ativos"  value="Qtd dias ativos no curso" name="op_variavel">
  
  <input type="button" class="btn btn-outline-primary btn-sm w-30" value="Qtd dias para Certificado" id="dias_certificado" name="op_variavel">

  <input type="button" class="btn btn-outline-primary btn-sm w-30" value="Módulos completos" id="mod_completos" name="op_variavel">
</div>
<div class="col-md-12" id="form_variaveis2" style="display:none">
<br>
  <strong>2.1. Escolha uma variável independente (X)</strong>
<br>
<input type="button" class="btn btn-primary btn-sm w-30" id="nf_concluintes2" name="op_variavel"
value="Nota final, concluintes">  

<input type="button" class="btn btn-outline-primary btn-sm w-30" value="Notas 1 tentativa, concluintes"  id="n1_concluintes2" name="op_variavel">
  
  
  <input type="button" class="btn btn-outline-primary btn-sm w-30" id="dias_ativos2"  value="Qtd dias ativos no curso" name="op_variavel">
  
  <input type="button" class="btn btn-outline-primary btn-sm w-30" value="Qtd dias para Certificado" id="dias_certificado2" name="op_variavel">

  <input type="button" class="btn btn-outline-primary btn-sm w-30" value="Módulos completos" id="mod_completos2" name="op_variavel">
  <div id="msg_varx" style="display:none" class="text-warning">Escolha a variável para X</div>
</div>
</div>
<div class="row com-borda mt-3">
  <div class="row">
    <strong>3. Defina os Grupos</strong>
</div>
<div class="row"  id="form_perfil">
<div class="col-md-3 genero" id="col-genero">
<p class="texto-com-icone"><i class="icone icone-genero"></i> <strong>Gênero</strong></p>
<?php 
        foreach ($ret_infos[0] as $ii =>$vv) { ?>
        <label class="toggle">
          <input type="checkbox" checked="checked" <?php echo $vv["infoValor"]=="Nao Respondidox"?"disabled='disabled'":''; ?> name="genero[]" value="<?php echo $vv["infoValor"]?>" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
            <?php echo $vv["infoValor"]; ?>
          </label>
          <br>
       <?php } ?>
</div>
<div class="col-md-3 racial" id="col-racial">
<p class="texto-com-icone"><i class="icone icone-racial"></i> <strong>Identificação Racial</strong></p>
    <?php 
        foreach ($ret_infos[1] as $ii =>$vv) { ?>
        <label class="toggle">
          <input type="checkbox" checked="checked" <?php echo $vv["infoValor"]=="Nao Respondidox"?"disabled='disabled'":''; ?> name="racial[]" value="<?php echo $vv["infoValor"]?>" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
            <?php echo $vv["infoValor"]; ?>
          </label>
          <br>
       <?php } ?>
</div>
<div class="col-md-3 escolaridade" id="col-escolaridade">
<p class="texto-com-icone"><i class="icone icone-escolaridade"></i> <strong>Escolaridade</strong></p>
<?php 
        foreach ($ret_infos[2] as $ii =>$vv) { ?>
        <label class="toggle">
          <input type="checkbox" checked="checked" <?php echo $vv["infoValor"]=="Nao Respondidox"?"disabled='disabled'":''; ?> name="escolaridade[]" value="<?php echo $vv["infoValor"]?>" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
            <?php echo $vv["infoValor"]; ?>
          </label>
          <br>
       <?php } ?>
</div>
<div class="col-md-3 idade" id="col-idade">
<p class="texto-com-icone"><i class="icone icone-faixa-etaria"></i>  <strong>Faixa Etária</strong></p>
<?php 
        foreach ($ret_infos[3] as $ii =>$vv) { ?>
        <label class="toggle">
          <input type="checkbox" checked="checked" <?php echo $vv["infoValor"]=="Nao Respondidox"?"disabled='disabled'":''; ?> name="idade[]" value="<?php echo $vv["infoValor"]?>" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
            <?php echo $vv["infoValor"]; ?>
          </label>
          <br>
       <?php } ?>
</div>
</div>
        </div>
<div class="row com-borda mt-3">
<div class="col-md-4">
  <strong>4. Calcular  </strong>
  <br>
  <p style="display:none">Tamanho Amostra Filtro: <input type="text" value="250" placeholder="" id="tamostrafiltro" size="4"><br></p>
  <p style="display:none">Tamanho Amostra Curso: <input type="text" value="250" placeholder="" id="tamostracurso" size="4"></p>
    <div id="escolhe_cg" class="com-borda" style="display:none;padding:3px !important;margin:0px !important;">
  <label class="toggle" id="label_curso_filtro">
          <input id="curso_filtro"  checked="checked" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
          1 Reta -> Grupos no Curso X Grupos no Lúmina (c/ Filtro)
  </label>
  <label class="toggle" id="label_grupo_filtro">
          <input id="grupo_filtro" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
            2 Retas -> a) Grupos no Curso x Curso fora do grupo <br>
                      b) Grupos no Lúmina x Lúmina fora do Grupo
          
  </label>
  </div>
   
  <label class="toggle" id="label_manter" style="display:none">
          <input id="manter_grupo_filtro" checked="checked" class="toggle-checkbox" type="checkbox">
            <div class="toggle-switch"></div>
          Filtrar Grupos também para Dados LÚMINA
  </label>
</div>
<div class="col-md-8" id="form_calcular">

  <input type="button" id="btn_calcgeral" value="Obter Resultados"
  class="btn btn-primary btn-sm w-30">

  
  
</div>
</div>
<div class="row mt-3 com-borda" style="display:none" id="div_analisar">
<div class="row">
    <strong>5. Analisar</strong>
</div>
<div class="row mt-0">
  <div class="col-md-6">
   <p class="texto-com-icone"><i class="icone icone-redondo-azul"></i>Dados Lúmina: De <?php echo $datas[0]?> a <?php echo $datas[1]?><br>[Grupo controle]</p>
  </div>
  <div class="col-md-6 mr-0">
  <p class="texto-com-icone"><i class="icone icone-redondo-preto"></i><?php echo $curso_descricao;?><br> [Grupo Experimental]</p>
  </div>
</div>
<div class="row mt-3" align="center" id="img-load-cinco" style="display:none">
<?php echo Html::img('@web/ponto-load.gif',array('style'=>'margin:auto;width:80px;text-align:center;vertical-align:middle;opacity:0.5'));?>
</div>
<div class="row">
<div id="lumilab-indicador-cinco" class="col-md-8">
</div>
<div id="lumilab-estatistica-mensagem" class="col-md-4">

</div>
</div>
  </div>

  <div class="row com-borda mt-3" style="display:none" id="dados_txt">
  <strong>Baixar Dados</strong>
    <div align="center">  
        <button type="button" id="btn_txt" class="btn btn-success btn-sm w-30">Baixar dados Controle+Experimental</button>
    </div>
</div>

<div class="row com-borda mt-3" style="display:none" id="obs_will">
<strong>Observações</strong>
<p>O  teste de Mann-Whitney (ou teste de Wilcoxon para soma de ranques) testa a hipótese da igualdade das medianas de duas amostras, sendo uma  alternativa não paramétrica ao teste t de Student.  Os valores de U calculados pelo teste avaliam o grau de entrelaçamento dos dados dos dois grupos após a ordenação. A maior separação dos dados em conjunto indica que as amostras são distintas, rejeitando-se a hipótese de igualdade das medianas. </p>
<p>O Lumi.Lab não faz testes de normalidade, e por isso apenas o resultado do teste de Mann-Whitney é reportado.</p>
<p>O Lumi.Lab parte do princípio que as amostras são independentes e aleatórias</p>
<p>Recomenda-se que, caso você não tenha treinamento em Estatística, consulte um profissional da área para interpretar estes dados.</p>
</div>

</form>

<?php 

$this->registerJs("function checaRetorno(){
    
  if($('#img-load-cinco').is(':visible'))
  {
    $('#img-load-cinco').hide();
    $('#lumilab-indicador-cinco').html(\"<div align='center'><b class='".'ativo_q'."'>Erro inesperado</b></div>\");
    $('#lumilab-estatistica-mensagem').html('');
  }
   
            
   
   
}",
   View::POS_READY,
   'lumi-lab-estatistica'.rand(1,9999)
);    

$this->registerJs("function carregaPosicao(v_teste){
    
    setTimeout(checaRetorno,10000)
    $('#div_analisar').show();
     $('#img-load-cinco').show();
     $('#lumilab-indicador-cinco').html('');
     v_objetivo = 'Comparar'
     if($('#btn_correlacionar').hasClass('btn-primary'))
     v_objetivo = 'Correlacionar'
     
     v_vars =''
     $('#form_variaveis input').each(function (){
      if ($(this).hasClass('btn-primary'))
      {
          v_vars+=$(this).attr('id')+','
      }
    });
    varx=''
    $('#form_variaveis2 input').each(function (){
      if ($(this).hasClass('btn-primary'))
      {
        varx=$(this).attr('id')
      }
    });
      if($('#btn_comparar').hasClass('btn-primary'))
      varx=''

    
      $('#dados_txt').hide();
      $('#obs_will').hide();

      sel_perfil=''
      desmarcado = false
      
      scontadores={'genero[]':0,'racial[]':0,'escolaridade[]':0,'idade[]':0}
      $('#form_perfil div label input').each(function (){
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

       
       manter_grupo_filtro = 'S'
       
       curso_filtro='N'
       
       grupo_filtro='N'

      
      

        $.ajax({
           url: '". Yii::$app->request->baseUrl. '?r=lumilab/carrega-posicao' ."',
           type: 'get',
           data: {
                    p_amostra_filtro: $('#tamostrafiltro').val(),
                    p_amostra_curso: $('#tamostracurso').val(),
                    v_objetivo:v_objetivo,
                    v_vars:v_vars,
                    v_teste:v_teste,
                    varx:varx,
                    areas:".$areaval.",
                    cargas:".$cargas.",
                    manter_grupo_filtro:manter_grupo_filtro,
                    curso_filtro:curso_filtro,
                    grupo_filtro:grupo_filtro,
                    
                    v_contadores:scontadores['genero[]']+','+scontadores['racial[]']+','+scontadores['escolaridade[]']+','+scontadores['idade[]'],
                    sel_perfil:sel_perfil,
                     dti:'".$datas[0]."',
                     dtf:'".$datas[1]."',
                     cid:'".$datas[2]."',
                     acao:'estatistica',                    
                     _csrf : '". Yii::$app->request->getCsrfToken() ."'
                 },
           success: function (data) {
             data2 =data.split('#DIVISAO#')

              $('#img-load-cinco').hide();
              
                $('#lumilab-estatistica-mensagem').html(data2[1]);
                $('#lumilab-indicador-cinco').html(data2[0]);

              
                
                if(data2[1]=='..Processando')
                {
                //alert(v_teste.substring(0,-1))
                $('#lumilab-estatistica-mensagem').html('');
                
                carregaPosicao(v_teste.substring(0,v_teste.length-1)+'2')
                }else
                $('#dados_txt').show();
               
                
              
             
             if(v_teste=='will2')
             $('#obs_will').show();
             
             
           
           
            
 
           }
      });}",
     View::POS_READY,
     'lumi-lab-estatistica'.rand(1,9999)
 );

 
 $this->registerJs("$('#form_variaveis input').on('click',function (){
    
  //$('#ob_certificado').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#dias_certificado').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#mod_completos').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#dias_ativos').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#nf_concluintes').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#n1_concluintes').removeClass('btn-primary').addClass('btn-outline-primary');
  $(this).removeClass('btn-outline-primary').addClass('btn-primary');
  
  
  
    

    $('#form_perfil div label input').each(function (){
      $(this).prop('disabled',false)        
      $(this).prop('checked',true) 
    });
    
   
  
}
 )");




 $this->registerJs("$('#form_variaveis2 input').on('click',function (){
    
  //$('#ob_certificado2').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#dias_certificado2').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#mod_completos2').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#dias_ativos2').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#nf_concluintes2').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#n1_concluintes2').removeClass('btn-primary').addClass('btn-outline-primary');
  $(this).removeClass('btn-outline-primary').addClass('btn-primary');
  
  

    $('#form_perfil div label input').each(function (){
      $(this).prop('disabled',false)        
      $(this).prop('checked',true) 
    });
    
   
}
 )");


 $this->registerJs("$('#btn_calcgeral').on('click',function (){
  
  if($('#btn_comparar').hasClass('btn-primary'))
  {
    carregaPosicao('will1')
    return 0;
  }
  
  abre=false

  $('#form_variaveis2 input').each(function (){
    if ($(this).hasClass('btn-primary'))
    {
      abre=true
    }
  });

  if(!abre)
    $('#modal_relo').modal('show')
  else
   carregaPosicao('reli1')

}
)");

 $this->registerJs("$('#btn_calc1').on('click',function (){
    
    carregaPosicao('will1')
}
 )");

 $this->registerJs("$('#btn_calc3').on('click',function (){
  
  
  carregaPosicao('reli1')
  
}
 )");


 $this->registerJs("$('#btn_txt').on('click',function (){
  
  
  window.open('/lumilab/?r=txt&chave=".sha1('abc'.\Yii::$app->user->identity->id)."','_blank');

  
}
 )");

 $this->registerJs("$('#btn_correlacionar').on('click',function (){
  
  $('#form_variaveis2').show()
  
  $('#msg_vd').html('2. Escolha a variável dependente (Y)')
  $('#msg_varx').html('Escolha a variável para X')
  $('#msg_varx').hide()
  
  $('#form_variaveis input').each(function (){
    $(this).removeClass('btn-primary').addClass('btn-outline-primary');
    $(this).removeClass('btn-warning').addClass('btn-outline-primary');
  
  });
  
  
  $('#nf_concluintes').trigger( 'click')

  $('#btn_correlacionar').removeClass('btn-outline-primary').addClass('btn-primary');
  $('#btn_comparar').removeClass('btn-primary').addClass('btn-outline-primary');

  $('#nf_concluintes').trigger('click')

  $('#btn_calc1').removeClass('btn-primary').addClass('btn-outline-primary');
  
  $('#btn_calc1').prop('disabled',true);

  

  $('#form_perfil div label input').each(function (){
    
      $(this).prop('checked',true)
            
  });

  
  $('#manter_grupo_filtro').prop('checked',true)

  $('#form_perfil .col-md-3').each(function (){
    
    $(this).removeClass('com-borda')
          
  });

  
  

}
 )");

 $this->registerJs("$('#btn_comparar').on('click',function (){
  
  
  $('#form_variaveis2').hide() 
  $('#msg_vd').html('2. Escolha a variável')
  
  
  $('#btn_correlacionar').removeClass('btn-primary').addClass('btn-outline-primary');
  $('#btn_comparar').removeClass('btn-outline-primary').addClass('btn-primary');
  //$('#ob_certificado').removeClass('btn-primary').addClass('btn-outline-primary');
  
  $('#nf_concluintes').trigger('click')

  
  $('#form_perfil div label input').each(function (){
    
    $(this).prop('checked',true)
  
          
});

  

}
 )");



 
 ?>