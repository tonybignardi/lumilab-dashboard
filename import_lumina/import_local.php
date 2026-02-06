<?php

// Load environment variables
$envFile = dirname(__DIR__) . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos($line, '=') !== false && strpos($line, '#') !== 0) {
            list($key, $value) = explode('=', $line, 2);
            $_SERVER[trim($key)] = trim($value);
        }
    }
}

// Moodle Database Connection (PostgreSQL)
$moodle_host = getenv('MOODLE_DB_HOST') ?: 'localhost';
$moodle_port = getenv('MOODLE_DB_PORT') ?: '5432';
$moodle_database = getenv('MOODLE_DB_NAME') ?: 'm24';
$moodle_username = getenv('MOODLE_DB_USER') ?: 'postgres';
$moodle_password = getenv('MOODLE_DB_PASSWORD') ?: 'master';

try {
    $conn_remota = new PDO(
        'pgsql:host=' . $moodle_host . ';port=' . $moodle_port . ';dbname=' . $moodle_database,
        $moodle_username,
        $moodle_password
    );
    echo "Conectado ao Moodle (PostgreSQL)<br>";
} catch (PDOException $e) {
    echo "Erro conexão Moodle: " . $e->getMessage() . "<br/>";
}

// Local Database Connection (MySQL)
$local_host = getenv('DB_HOST') ?: 'localhost';
$local_port = getenv('DB_PORT') ?: '3306';
$local_database = getenv('DB_NAME') ?: 'lumix';
$local_username = getenv('DB_USER') ?: 'root';
$local_password = getenv('DB_PASSWORD') ?: '';

try {
    $conn_local = new PDO(
        'mysql:host=' . $local_host . ';port=' . $local_port . ';dbname=' . $local_database,
        $local_username,
        $local_password
    );
    echo "Conectado ao banco local (MySQL)<br>";
} catch (PDOException $e) {
    echo "Erro conexão banco local: " . $e->getMessage() . "<br/>";
}

function strData($tipoBD,$valor)
{
    if($tipoBD=="MY")
        return "FROM_UNIXTIME($valor,'%d-%m-%Y')";
    else
        return "to_timestamp($valor)";
}


#encontrando datas de encaixe
$sel_remoto = "select max(to_timestamp(timemodified)) as acessoremoto from mdl_course_modules_completion ";
$res1 = $conn_remota->query($sel_remoto, PDO::FETCH_ASSOC);
$rows = $res1->fetchAll();
echo "ultimadata moodle" . $rows[0]["acessoremoto"];
$dataremoto=substr($rows[0]["acessoremoto"],0,10);
#$dataremoto = "2023-01-06";

$sel_local = "select max(dataUltimaModificacao) as acessolocal from acessoconteudo";

$res2 = $conn_local->query($sel_local, PDO::FETCH_ASSOC);
$rows = $res2->fetchAll();
echo "ultimadata lumilab" . $rows[0]["acessolocal"];
$datalocal = !empty($rows[0]["acessolocal"]) ? substr($rows[0]["acessolocal"],0,10) : date('Y-m-d');
#$datalocal = "2023-01-06";


date_default_timezone_set('America/Sao_Paulo');


$strhoje = date('Y-m-d');
$strhoje = '2023-11-30';
#$dataremoto=$strhoje;
$hoje = date_create($dataremoto);
$dia_import= date_create($datalocal);

$iniapaga = date_create($datalocal);
$fimapaga = (date('Y')+1).'-01-01 00:00:00';



$hoje= date_format($hoje,'Y-m-d 23:59:59');
#$hoje = '2023-01-01 00:00:00';

$dia_import= date_format($dia_import,'Y-m-d 00:00:00');
$iniapaga= date_format($iniapaga,'Y-m-d 00:00:00');
$apaga=false;
$soReconstroi=true;
$apagaesai=false;
$datas_del=array($iniapaga,$fimapaga);

#print_r($datas_del);


$tcomecou = date('d-m-Y H:i:s');

#$datas[]=array($dia_import,$hoje);

//$datas_del=array('2023-11-22 00:00:00',$fimapaga);

$dtini=$datas_del[0];
$dtfim = $datas_del[1];

$reconstruido=false;
$soReconstroi=true;
$apaga=false;

if($apaga)
{
    if($soReconstroi)
        $dtfim = $datas_del[0];   
    
    echo "<br>inicio:".date('d-m-Y H:i:s')."<br>\n";
    
    // Executar DELETEs diretamente no banco
    try {
        $conn_local->query("DELETE FROM alunoinfo WHERE aluno_alunoId IN (SELECT alunoId FROM aluno WHERE alunoDataCriacao BETWEEN '$dtini' AND '$dtfim')");
        $conn_local->query("DELETE FROM alunoinscricao WHERE alunoInscricaoData BETWEEN '$dtini' AND '$dtfim'");
        $conn_local->query("DELETE FROM aluno WHERE alunoDataCriacao BETWEEN '$dtini' AND '$dtfim'");
        $conn_local->query("DELETE FROM acessoconteudo WHERE dataUltimaModificacao BETWEEN '$dtini' AND '$dtfim'");
        $conn_local->query("DELETE FROM conteudo");
        $conn_local->query("DELETE FROM cursoresponsavel WHERE interna='N'");
        $conn_local->query("DELETE FROM usuario");
        $conn_local->query("DELETE FROM questaoresposta WHERE questaoRespostaData BETWEEN '$dtini' AND '$dtfim'");
        $conn_local->query("DELETE FROM questao");
        $conn_local->query("DELETE FROM quiz");
        $conn_local->query("DELETE FROM forumpost WHERE forumPostData BETWEEN '$dtini' AND '$dtfim'");
        $conn_local->query("DELETE FROM forumtopico");
        $conn_local->query("DELETE FROM forum");
        $conn_local->query("DELETE FROM curso");
        $conn_local->query("DELETE FROM areacurso");
        
        echo "✅ Limpeza concluída: " . date('d-m-Y H:i:s') . "\n";
    } catch (PDOException $e) {
        echo "Erro ao limpar dados: " . $e->getMessage() . "\n";
    }
    
    if ($apagaesai)
        exit();
}

echo "<br>inicio copia".date('d-m-Y H:i:s')."<br>\n";
$datas = array();
#$datas[]=array($datas_del[0],'2023-11-25 00:00:00');
$datas[]=array('2023-11-25 00:00:00','2023-12-10 00:00:00');
#$datas[]=array('2023-12-01 00:00:00','2023-12-11 00:00:00');
#$datas[]=array('2023-12-11 00:00:00','2023-12-21 00:00:00');
#$datas[]=array('2023-12-21 00:00:00','2024-01-01 00:00:00');

#$datas[]=array($dia_import,$hoje);
foreach($datas as $ii =>$inifim)
{

    $datainicio = $inifim[0];
    $dataatual=$inifim[1];
    $narquivo = substr($inifim[1],0,10);



    $sqldateInicioMY="UNIX_TIMESTAMP('$datainicio')";
    $sqldateFinalMY="UNIX_TIMESTAMP('$dataatual')";

    $sqldateInicioPG="cast(extract(epoch from '$datainicio'::timestamp+'3 hours') as float)";
    $sqldateFinalPG="cast(extract(epoch from '$dataatual'::timestamp+'3 hours') as float)";

    if(!$soReconstroi)
    {
    echo "importando alunos<br>";
    $arq="";

    $sql = "SELECT id,firstname,lastname,email,".strData("PG","timecreated")." as datacriacao from mdl_user
    where timecreated between $sqldateInicioPG and $sqldateFinalPG"; 
    #echo $sql;
    $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
    #echo $sql;
    #exit();
    $rows = $res->fetchAll();
    $tudo = sizeof($rows);
    $itudo = 1;
    echo "importando INFO ALUNOS e INFOS <br>\n";
    foreach ($rows as $row) {
        try {
            $conn_local->query('insert into aluno values('.$row["id"].',"'.$row["firstname"]." ".$row["lastname"].'","'.$row["email"].'","'.$row["datacriacao"].'","","","","");');
            $arq.='insert into aluno values('.$row["id"].',"'.$row["firstname"]." ".$row["lastname"].'","'.$row["email"].'","'.$row["datacriacao"].'","","","","");'."\n";
            #echo " ALUNO (".$row["id"].") - importado <br>";
            $portudo = ($itudo/$tudo)*100;
            if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                echo "(11) alunos ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n"; 
            } catch (PDOException $e) {
            echo  "Error ao importar ALUNO (".$row["id"].")<br>\n";
        }

        $sql = "select d.userid,f.id,f.name,d.data from mdl_user_info_data d join mdl_user_info_field f
        on (f.id=d.fieldid) join mdl_user mm on (mm.id=d.userid) where fieldid in (34,36,37,38)
        and mm.id=".$row["id"]; 
        
        $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
        $rows = $res->fetchAll();
        
        foreach ($rows as $row) {
            try {
                $conn_local->query("insert into alunoinfo values(".$row["userid"].",'".$row["id"]."','".$row["name"]."','".$row["data"]."',0)");
                $arq.="insert into alunoinfo values(".$row["userid"].",'".$row["id"]."','".$row["name"]."','".$row["data"]."',0);\n";
                #echo " INFO ".$row["userid"] . "<br>";
            } catch (PDOException $e) {
                echo  "Error ao importar INFO (".$row["userid"].")<br>";
            }
        }
        $itudo++;
    }
    }

    if(!$reconstruido)
    {
        echo "importando AREA CURSOS <br>\n";
        $arq="";

        $sql = "select id,name,to_timestamp(timemodified) as datacriacao,visible from mdl_course_categories"; 

        $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
        $rows = $res->fetchAll();
        $tudo = sizeof($rows);
        $itudo = 1;
        foreach ($rows as $row) {
            try {
                $conn_local->query("insert into areacurso values(".$row["id"].",'".$row["name"]."','".$row["datacriacao"]."','".$row["visible"]."')");
                $arq.="insert into areacurso values(".$row["id"].",'".$row["name"]."','".$row["datacriacao"]."','".$row["visible"]."');\n";
                #echo " - AREA CURSO importada".$row["id"] . "<br>";
                $portudo = ($itudo/$tudo)*100;
                if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                echo "(10) areas curso ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
             } catch (PDOException $e) {
                echo  "Error ao importar AREA CURSO (".$row["id"].")<br>";
            }
            $itudo++;
        }
        
        echo "importando CURSOS <br>\n";
        
        $sql = "select id,shortname,".strData("PG","timecreated")." as datacriacao,category,visible from mdl_course"; 

        $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
        $rows = $res->fetchAll();
        echo "importando CONTEUDOS curso <br>\n";
        echo "importando CARGA   <br>\n";
        $tudo = sizeof($rows);
        $itudo = 1;
        $sqlinternos = "select * from cursocarga where interna='S'";
        #$qsinterno = $conn_local->query($sqlinternos,PDO::FETCH_ASSOC);
        #$rsinterno = $qsinterno->fetchAll();

        #$conn_local->query("truncate table cursocarga");
        foreach ($rows as $row) {
            try {
                $conn_local->query("insert into curso values(".$row["id"].",'".$row["shortname"]."','','".$row["datacriacao"]."','".$row["category"]."','".$row["visible"]."')");
                $arq.="insert into curso values(".$row["id"].",'".$row["shortname"]."','','".$row["datacriacao"]."','".$row["category"]."','".$row["visible"]."');\n";
                #echo " - CURSOS  importado".$row["id"] . "<br>";
                $portudo = ($itudo/$tudo)*100;
                if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                echo "(9) curso ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
            
                $sch = "select i.configdata,cc.id from mdl_block_instances i join 
        mdl_context c on (c.id=i.parentcontextid) join mdl_course cc on (c.instanceid=cc.id) where blockname='html'
        and i.configdata<>'' and cc.id=".$row["id"];

                $resch = $conn_remota->query($sch, PDO::FETCH_ASSOC);
                $rowch_todos = $resch->fetchAll();

             
                

                    
                    foreach ($rowch_todos as $rowch) {

                        $varhtml = base64_decode($rowch["configdata"]);
                        $tempocurso = explode("empo de estu",$varhtml);
                        if(sizeof($tempocurso)>1)
                        {
                            $tempocurso = explode(">",$tempocurso[1]);
                            $tempocurso = explode("h",$tempocurso[1]);
                            $vfinal = intval(strip_tags(str_replace("&nbsp;","",$tempocurso[0])));
                            echo "curso".$row["id"]. " ----- ". $vfinal. "\n";

                            #$conn_local->query("insert into cursocarga value ('NULL',".$row["id"].",'".$row["shortname"]."','".$vfinal."','N')");
                            $conn_local->query("insert into cursocarga value ('NULL',".$row["id"].",'".$row["shortname"]."','".$vfinal."','N')");
                            $arq.="insert into cursocarga value ('NULL',".$row["id"].",'".$row["shortname"]."','".$vfinal."','N');\n";
                            #echo " - CONTEUDO  importado".$row3["id"] . "<br>";
                        }
                    }
                    
                /*               
                    foreach($rsinterno as $rowch)
                    {
                        
                        if($row["id"]==$rowch["curso_cursoId"])
                        {
                            #$conn_local->query("insert into cursocarga value ('NULL','".$rowch["curso_cursoId"]."','".$row["shortname"]."','".$rowch["cargahoraria"]."','S')");
                            $arq.="insert into cursocarga value ('NULL','".$rowch["curso_cursoId"]."','".$row["shortname"]."','".$rowch["cargahoraria"]."','S');\n";
                            #$conn_local->query("delete from cursocarga where interna='N' and curso_cursoId=".$row["id"]);
                            $arq.="delete from cursocarga where interna='N' and curso_cursoId=".$row["id"].";\n";
                        }
                    }
                    */

                $sql2 = "select m.module,mm.name from mdl_course_modules m join mdl_modules mm
                on (mm.id=m.module) where m.course=".$row["id"] . " group by m.module,mm.name"; 

                //echo $sql;
                $res2 = $conn_remota->query($sql2, PDO::FETCH_ASSOC);
                $rows2 = $res2->fetchAll();

                foreach ($rows2 as $row2) {
                    try {
                        $sql3 = "select mm.id,m.name as conteudo,s.section,s.name as secao,s.sequence,m.course from mdl_".$row2["name"]." m join mdl_course_modules mm
                        on (mm.instance=m.id) join mdl_course_sections s on (s.id=mm.section) where mm.course=".$row["id"] . " 
                        and m.course=mm.course";
                        $res3 = $conn_remota->query($sql3, PDO::FETCH_ASSOC);
                        $rows3 = $res3->fetchAll();
                        foreach ($rows3 as $row3) {
                            $posicao = explode(",",$row3["sequence"]);
                            $chave = array_search($row3["id"],$posicao)+1;
                           
                            
                            #$conn_local->query("insert into conteudo values(".$row3["id"].",'".$row3["course"]."','".$row3["section"]."','".$chave."','".$row3["conteudo"]."','".$row2["name"]."','".$row3["secao"]."')");
                            #echo " - CONTEUDO  importado".$row3["id"] . "<br>";
                            $conn_local->query("insert into conteudo values(".$row3["id"].",'".$row3["course"]."','".$row3["section"]."','".$chave."','".$row3["conteudo"]."','".$row2["name"]."','".$row3["secao"]."')");
                            $arq.="insert into conteudo values(".$row3["id"].",'".$row3["course"]."','".$row3["section"]."','".$chave."','".$row3["conteudo"]."','".$row2["name"]."','".$row3["secao"]."');\n";


                            
                        }
                        
                    } catch (PDOException $e) {
                        echo  "Error ao importar CONTEUDOS  (".$row["id"].")<br>";
                    }
                }

            } catch (PDOException $e) {
                echo  "Error ao importar CURSOS  (".$row["id"].")<br>";
            }
            $itudo++;
        }
    }
    
    if(!$soReconstroi)
    {
    echo "importando INSCRICOES <br>\n";
    $arq="";
    $sql = "select f.userid,f.course,".strData("PG","f.timeenrolled")." as datainscricao,
    (case when f.timestarted=0 then null else ".strData("PG","f.timestarted")." end)
    as datainicio,
    ".strData("PG","f.timecompleted")." as dataconclusao 

    from mdl_course_completions f where f.timeenrolled 
    
    between $sqldateInicioPG and $sqldateFinalPG"; 


    //echo $sql;
    $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
    $rows = $res->fetchAll();
    $tudo = sizeof($rows);
    $itudo = 1;
    foreach ($rows as $row) {
        try {

            $s1tent = "SELECT ROUND(avg(a.sumgrades/q.grade),2) as nota1tent FROM 
                mdl_quiz_attempts a join mdl_quiz q on (a.quiz=q.id) where
            a.state='finished' and a.attempt=1 and q.grade>0 and a.userid=".$row["userid"]."
            and q.course=".$row["course"];
            $r1tent = $conn_remota->query($s1tent, PDO::FETCH_ASSOC);
            $row1tent = $r1tent->fetchAll();

            $snfim = "SELECT ROUND(avg(a.grade/q.grade),2) as notafim FROM 
                mdl_quiz_grades a join mdl_quiz q on (a.quiz=q.id) where
            q.grade>0 and a.userid=".$row["userid"]." and q.course=".$row["course"];
            $rnfim = $conn_remota->query($snfim,PDO::FETCH_ASSOC);
            $rownfim = $rnfim->fetchAll();

            #$conn_local->query("insert into alunoinscricao values(".$row["userid"].",'".$row["course"]."','".$row["datainscricao"]."','".$row["datainicio"]."','".$row["dataconclusao"]."','','','','".$rownfim[0]["notafim"]."','".$row1tent[0]["nota1tent"]."')");
            #echo " - INSCRICOES  importado".$row["userid"] . "<br>";
            $conn_local->query("insert  into alunoinscricao values(".$row["userid"].",
            '".$row["course"]."','".$row["datainscricao"]."','".$row["datainicio"]."',
            '".$row["dataconclusao"]."','','','','".$rownfim[0]["notafim"]."','".$row1tent[0]["nota1tent"]."')");
            $arq.="insert  into alunoinscricao values(".$row["userid"].",
            '".$row["course"]."','".$row["datainscricao"]."','".$row["datainicio"]."',
            '".$row["dataconclusao"]."','','','','".$rownfim[0]["notafim"]."','".$row1tent[0]["nota1tent"]."');\n";
            $portudo = ($itudo/$tudo)*100;
            if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
            echo "(8) alunoinscricao ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
        } catch (PDOException $e) {
            echo  "Error ao importar INSCRICOES  (".$row["userid"].")<br>";
        }
        $itudo++;
    }
    file_put_contents("4-inscricoes-$narquivo.sql",$arq);
    }
    if(!$reconstruido)
    {
        echo "importando PAPEIS <br>\n";
        $arq="";
        $sql = "select mc.id as course,ma.roleid,ma.userid,r.shortname,u.firstname,u.lastname,
        u.password,u.email,to_timestamp(u.timecreated) as criacao
        from mdl_role_assignments ma join mdl_context mx on (ma.contextid=mx.id) 
        join mdl_user u on (u.id=ma.userid)
        join mdl_role r on (r.id=ma.roleid) join mdl_course mc 
        on (mc.id=mx.instanceid) where ma.roleid in (1,3,4)"; 


        $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
        $rows = $res->fetchAll();
        $tudo = sizeof($rows);
        $itudo = 1;
        foreach ($rows as $row) {
            try {

                
                $conn_local->query("insert into usuario values(".$row["userid"].",'".$row["firstname"]." ".$row["lastname"]."','".$row["email"]."','".$row["password"]."','".$row["criacao"]."')");
                #echo " USUARIO (".$row["userid"].") - importado <br>";

                $arq.="insert into usuario values(".$row["userid"].",'".$row["firstname"]." ".$row["lastname"]."',
                '".$row["email"]."','".$row["password"]."','".$row["criacao"]."');\n";
                
                $portudo = ($itudo/$tudo)*100;
                if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                echo "(7) papeis ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";




                #$conn_local->query("insert into cursoresponsavel values(NULL,".$row["course"].",'".$row["userid"]."','".$row["roleid"]."','".$row["shortname"]."','N')");

                $conn_local->query("insert into cursoresponsavel values(NULL,".$row["course"].",'".$row["userid"]."',
                '".$row["roleid"]."','".$row["shortname"]."','N')");
                $arq.="insert into cursoresponsavel values(NULL,".$row["course"].",'".$row["userid"]."',
                '".$row["roleid"]."','".$row["shortname"]."','N');\n";
                #echo " PAPEIS (".$row["course"].") - importado <br>";
                

                
            } catch (PDOException $e) {
                echo  "Error ao importar PAPEIS (".$row["coursemcourseoduleid"].")<br>";
            }
            $itudo++;
        }

    }

    if(!$soReconstroi)
    {
    echo "importando ACESSOS <br>\n";
    $arq="";
    $sql = "select mc.coursemoduleid,mc.userid,m.course,
    

    to_timestamp(mc.timemodified) as datamodificacao,
    mc.completionstate

    from mdl_course_modules_completion mc join mdl_course_modules m
    on (m.id=mc.coursemoduleid)
    where mc.timemodified between $sqldateInicioPG and $sqldateFinalPG"; 


    $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
    $rows = $res->fetchAll();
    $tudo = sizeof($rows);
    $itudo = 1;
    foreach ($rows as $row) {
        try {
            

            $s1 = "select to_timestamp(v.timecreated) as viu
            from mdl_course_modules_viewed v
            where v.userid=".$row["userid"]." and v.coursemoduleid=".$row["coursemoduleid"];
            $r1 = $conn_remota->query($s1, PDO::FETCH_ASSOC);
            $row1 = $r1->fetchAll();
            $conn_local->query("insert into acessoconteudo values(".$row["coursemoduleid"].",'".$row["userid"]."','".$row["course"]."','".@$row1[0]["viu"]."','".$row["datamodificacao"]."','".$row["completionstate"]."')");
            $arq.="insert into acessoconteudo values(".$row["coursemoduleid"].",'".$row["userid"]."',
            '".$row["course"]."','".@$row1[0]["viu"]."','".$row["datamodificacao"]."','".$row["completionstate"]."');\n";
            #echo " ACESSOS (".$row["coursemoduleid"].") - importado <br>";
            $portudo = ($itudo/$tudo)*100;
            if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                echo "(6) acesso conteudo ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
        } catch (PDOException $e) {
            echo  "Error ao importar ACESSOS (".$row["coursemoduleid"].")<br>";
        }
        $itudo++;
    }
    
    }   

    if(!$reconstruido)
    {
        echo "importando QUIZ <br>\n";
        $arq="";
        $sql = "select q.id,q.name,to_timestamp(q.timemodified) as data,cm.course as curso,
        cm.id as cmodule,cs.sequence,cs.section
        from mdl_course_modules cm
        join mdl_quiz q on (q.id=cm.instance) 
        join mdl_course_sections cs on (
        cs.id=cm.section)
        where cm.module=16 and cm.visible=1"; 

        #echo $sql;
        $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
        $rows = $res->fetchAll();

        echo "importando QUESTOES INTERNO <br>\n";
        $tudo = sizeof($rows);
        $itudo = 1;
        foreach ($rows as $row) {
            try {

                $posicao = explode(",",$row["sequence"]);
                $chave = array_search($row["cmodule"],$posicao)+1;

                #$conn_local->query("insert into quiz values(".$row["id"].",'".$row["name"]."','".$row["data"]."','".$row["curso"]."','".$row["section"]."','".$chave."')");

                $conn_local->query("insert into quiz values(".$row["id"].",'".$row["name"]."',
                '".$row["data"]."','".$row["curso"]."','".$row["section"]."','".$chave."')");
                $arq.="insert into quiz values(".$row["id"].",'".$row["name"]."',
                '".$row["data"]."','".$row["curso"]."','".$row["section"]."','".$chave."');\n";
            
                #echo " QUIZ (".$row["id"].") - importado <br>";
                $portudo = ($itudo/$tudo)*100;
                if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                    echo "(5) questoes quizz ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
                

                $sql2 = "select v.questionid as qid,s.quizid as quizid,q.name as nquestao,q.questiontext as questao,
                s.slot,to_timestamp(q.timemodified) as data,v.version, v.questionbankentryid,
                '---' from mdl_question_references r
                join mdl_quiz_slots s on (s.id=r.itemid) join mdl_question_versions v
                on (v.questionbankentryid=r.questionbankentryid ) join mdl_question q on (q.id=v.questionid)
                where 
                quizid=".$row["id"]."
                order by quizid,slot asc,version desc"; 
                #echo $sql2;
                #exit();
                
                
                #echo $sql2;
                $res2 = $conn_remota->query($sql2, PDO::FETCH_ASSOC);
                $rows2 = $res2->fetchAll();
                
                foreach ($rows2 as $row2) {
                    try {
                
                        $conn_local->query("insert into questao values(".$row2["qid"].",'".$row2["quizid"]."',
                        '".$row2["nquestao"]."','".$row2["questao"]."','".$row2["slot"]."','".$row2["data"]."')");
                        $arq.="insert into questao values(".$row2["qid"].",'".$row2["quizid"]."',
                        '".$row2["nquestao"]."','".$row2["questao"]."','".$row2["slot"]."','".$row2["data"]."');\n";
                        #echo " QUESTOES (".$row2["qid"].") - importado <br>";
                    
                    } catch (PDOException $e) {
                        echo  "Error ao importar questoes (questoes)<br>";
                    }
                }
                


            
            } catch (PDOException $e) {
                echo  "Error ao importar QUIZ (QUIZ)<br>";
            }
            $itudo++;
        }
    }

    if(!$soReconstroi)
    {
    echo "importando respostas QUIZ <br>\n";
    $arq="";
    $sql = "select s.state as resultado,s.userid as alunoid,qa.quiz,
    s.fraction as nota,to_timestamp(qa.timemodified) as data
    ,a.questionid as questaoid,qa.attempt as tentativa
    from mdl_question_attempt_steps s
    join mdl_question_attempts a on (a.id=s.questionattemptid) 
    join mdl_question_usages u on (u.id=a.questionusageid) 
    join mdl_quiz_attempts qa on (qa.uniqueid=u.id)
    where s.state not in('todo','complete','finished','gaveup','invalid') and 
    qa.timemodified between $sqldateInicioPG and $sqldateFinalPG
    "; 


    $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
    $rows = $res->fetchAll();
    $tudo = sizeof($rows);
    $itudo = 1;
    foreach ($rows as $row) {
        try {

            $conn_local->query("insert into questaoresposta values('','".$row["resultado"]."','".$row["questaoid"]."','".$row["quiz"]."','".$row["alunoid"]."','".$row["tentativa"]."','".$row["nota"]."','".$row["data"]."')");

            $arq.="insert into questaoresposta values('','".$row["resultado"]."','".$row["questaoid"]."',
            '".$row["quiz"]."','".$row["alunoid"]."','".$row["tentativa"]."'
            ,'".$row["nota"]."','".$row["data"]."');\n";
            #echo " respostas (".$row["quiz"].") - importado <br>";

            $portudo = ($itudo/$tudo)*100;
            if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
            echo "(4) respostas quizz ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
        } catch (PDOException $e) {
            echo  "Error ao importar questoes (questoes)<br>";
        }
        $itudo++;
    }
    }

    if(!$reconstruido)
    {
        echo "importando FORUNS  <br>\n";
        $arq="";
        $sql = "select f.id,f.type,f.name,f.intro,f.course,to_timestamp(f.timemodified) as data from mdl_forum f"; 


        $res = $conn_remota->query($sql, PDO::FETCH_ASSOC);
        $rows = $res->fetchAll();


        $tudo = sizeof($rows);
        $itudo = 1;
        foreach ($rows as $row) {
            try {

                $conn_local->query("insert into forum values('".$row["id"]."','".$row["type"]."','".str_replace("'",'"',strip_tags($row["name"]))."','','".$row["data"]."','".$row["course"]."')");
                $arq.="insert into forum values('".$row["id"]."','".$row["type"]."',
                '".str_replace("'",'"',strip_tags($row["name"]))."','','".$row["data"]."'
                ,'".$row["course"]."');\n";
                
                $portudo = ($itudo/$tudo)*100;
                if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                    echo "(3) forum /topico ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";


    ;
                
                #echo " forum (".$row["id"].") - importado <br>";

                #echo "importando topicos foruns  <br>";

                $sql2 = "select d.id,d.name,d.forum,d.course from mdl_forum_discussions d where
                d.course='".$row["course"]."' "; 
                #echo $sql2;
                
                
                $res2 = $conn_remota->query($sql2, PDO::FETCH_ASSOC);
                $rows2 = $res2->fetchAll();
                
                
                
                foreach ($rows2 as $row2) {
                    try {
                
                        $conn_local->query("insert into forumtopico values('".$row2["id"]."','".str_replace("'",'"',strip_tags($row2["name"]))."','".$row["id"]."','".$row["course"]."')");
                    
                        $arq.="insert into forumtopico values('".$row2["id"]."',
                        '".str_replace("'",'"',strip_tags($row2["name"]))."','".$row["id"]."','".$row["course"]."');\n";
                        #echo " topico (".$row2["id"].") - importado <br>";
                            
                    } catch (PDOException $e) {
                        echo  "Error ao importar topico (foruns)<br>";
                    }

                }


            

            
            } catch (PDOException $e) {
                echo  "Error ao importar forum (foruns)<br>";
            }
            $itudo++;
        }
    }

    if(!$soReconstroi)
    {

    echo "importando posts foruns  <br>\n";
    $arq="";
    $sql2 = "select p.id,p.discussion,p.userid,to_timestamp(p.modified) as data,
    p.message,f.course as curso from mdl_forum_posts p join mdl_forum_discussions f on (f.id=p.discussion) where p.modified between $sqldateInicioPG and $sqldateFinalPG"; 


    $res2 = $conn_remota->query($sql2, PDO::FETCH_ASSOC);
    $rows2 = $res2->fetchAll();
    $tudo = sizeof($rows2);
    $itudo = 1;
    #$url = '../assets/vadersentiment.php';
    #include $url;
    #$sentimenter = new SentimentIntensityAnalyzer();
    foreach ($rows2 as $row2) {
        try {

            $conn_local->query("insert into forumpost values('".$row2["id"]."','".$row2["data"]."','".str_replace("'",'"',strip_tags($row2["message"]))."','".$row2["discussion"]."','".$row2["userid"]."','".$row2["curso"]."','','','','')");
        
            $arq.="insert into forumpost values('".$row2["id"]."','".$row2["data"]."',
            '".str_replace("'",'"',strip_tags($row2["message"]))."','".$row2["discussion"]."','".$row2["userid"]."','".$row2["curso"]."','','','','');\n";
            $portudo = ($itudo/$tudo)*100;
            if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
                echo "(2) post forum ". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";
            #echo " post (".$row2["id"].") - importado <br>";
            
            #$result = $sentimenter->getSentiment(substr(strip_tags($row2["message"]),0,300));
            #$in = "update  forumpost set sent_total='".$result["compound"]."',sent_pos='".$result["pos"]."',sent_neu='".$result["neu"]."',sent_neg='". $result["neg"]."' where forumPostId=".$row2["id"];
            #$arq.=$in.";\n";
            ##$conn_local->query($in);
            
                
        } catch (PDOException $e) {
            echo  "Error ao importar post (foruns)<br>";
        }
        $itudo++;
    }



    echo "atualizando dias/modulos\n";
    $arq="";
    $sql2 = "select distinct ac.aluno_alunoId,ac.curso_cursoId,(
        select count(1) from acessoconteudo a2 where a2.aluno_alunoId=ac.aluno_alunoId AND
        ac.curso_cursoId=a2.curso_cursoId and a2.acessoConteudoStatus in ('1','2','3')) as modulos,
        (
        select count(distinct date(a2.dataUltimaModificacao)) from acessoconteudo a2 where a2.aluno_alunoId=ac.aluno_alunoId AND
        ac.curso_cursoId=a2.curso_cursoId and a2.acessoConteudoStatus in ('1','2','3')) as dias from acessoconteudo ac 
        where ac.dataUltimaModificacao between '$datainicio' and '$dataatual'
        and ac.acessoConteudoStatus in ('1','2','3');"; 
    $res2 = #$conn_local->query($sql2, PDO::FETCH_ASSOC);
    $rows2 = $res2->fetchAll();
    $tudo = sizeof($rows2);
    $itudo = 1;
    foreach ($rows2 as $row2) {
            
            $sqlpg="select to_timestamp(i.timecreated) as datacert from mdl_simplecertificate_issues
        i join mdl_simplecertificate s on (s.id=i.certificateid) where s.course=".$row2["curso_cursoId"]."
        and i.userid=".$row2["aluno_alunoId"]." order by datacert desc limit 1";
        $respg = $conn_remota->query($sqlpg, PDO::FETCH_ASSOC);
        $rowspg = $respg->fetchAll();
        $portudo = ($itudo/$tudo)*100;
        if(($portudo - intval($portudo)> 0.98) && intval($portudo)%2==0)
            echo " (1) atualizando diasunicos/modulos". number_format($portudo,2,".","") . "% - [[$dataatual]] -\n";

            $sql3 = "update alunoinscricao set alunoInscricaoDataCertificado='".@$rowspg[0]["datacert"].
            "',diasUnicosNoCurso=".$row2["dias"].",modulosCompletos=".$row2["modulos"]." where
                        aluno_alunoId=".$row2["aluno_alunoId"]." and curso_cursoId=".$row2["curso_cursoId"];

            $arq.=$sql3.";\n";            

            $res3 = #$conn_local->query($sql3);
            $itudo++;
        }

    }
    $reconstruido=true;
    
    #break;

    echo "<<<<<<<<<<<<<<<<<<<\n";

    echo $tcomecou."<br>";
    echo "fim".date('d-m-Y H:i:s')."\n";
    $tcomecou = date('d-m-Y H:i:s')."\n";

    if($soReconstroi)
        break;

}

echo "<br>(0)executando procedimentos<br>\n";
$arq="";
$sql3 = "CALL atualiza_alunoinfo()";
$conn_local->query($sql3);
$arq.=$sql3.";\n";
$sql3 = "CALL atualiza_perfil()";
$conn_local->query($sql3);
$arq.=$sql3.";\n";

$sql3 = "CALL gera_info()";
$conn_local->query($sql3);
$arq.=$sql3.";\n";



?>