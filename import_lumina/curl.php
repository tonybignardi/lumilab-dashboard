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

// Conectar ao banco de dados
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_NAME') ?: 'lumix';
$db_user = getenv('DB_USER') ?: 'root';
$db_password = getenv('DB_PASSWORD') ?: '';

try {
    $conn = new PDO(
        'mysql:host=' . $db_host . ';port=' . $db_port . ';dbname=' . $db_name,
        $db_user,
        $db_password
    );
} catch (PDOException $e) {
    die("Erro ao conectar ao banco: " . $e->getMessage());
}

// Buscar todos os IDs dos cursos do banco de dados
$sql = "SELECT GROUP_CONCAT(cursoId SEPARATOR ',') as ids FROM curso";
$stmt = $conn->query($sql);
$result = $stmt->fetch(PDO::FETCH_ASSOC);
$ids = "&ids=" . ($result['ids'] ?? '');

if (empty($result['ids'])) {
    die("Nenhum curso encontrado no banco de dados!");
}

$ch = curl_init();

// Get application path from .env (default: /lumilab)
$app_path = getenv('APP_PATH') ?: '/lumilab';
$url = "http://localhost:8080" . $app_path . "/?r=lumilab/salva-pagina";

$geral = false;
$questionario = true;
$outros = true;
$sentimento = true;

$vetperiodos = array("ultimo_ano","tudo","penultimo_ano");


curl_setopt($ch, CURLOPT_URL, $url.'&acao=pegaCurso'.$ids);
echo $url.'&acao=PegaCurso'.$ids;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

$head = curl_exec($ch);


$rows = array();
$linhas = explode("\n",$head);

//print_r($linhas);
array_pop($linhas);
$ind=0;

foreach($linhas as $id =>$ll){
    $data = explode("->",$ll);
    $rows[@$ind]["cursoId"]=$data[0];
    $rows[@$ind]["foruns"]=explode(",",$data[1]);
    $ind++;
}

### construindo pagina novo - geral
if($geral)
foreach($vetperiodos as $ind =>$inter)
{
    foreach(array("11","21","22","23","24","31","41","42","43","44","51","52","61","62") as $ind =>$ii)
    {
    curl_setopt($ch, CURLOPT_URL, $url.'&intervalo='.$inter.'&acao=novo&p='.$ii);
    echo  $url.'&intervalo='.$inter.'&acao=novo&p='.$ii . "<br>";

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

    $head = curl_exec($ch);
    echo $head;
    }
}

if($geral)
exit();
# cursos avancado

if($questionario)
foreach($rows as $rid => $rv)
{
    foreach($vetperiodos as $ind =>$inter)
    {
        foreach(array("21","32") as $ind =>$ii)
        {
            curl_setopt($ch, CURLOPT_URL, $url.'&intervalo='.$inter.'&cid='.$rv["cursoId"]."&acao=questionario&p=$ii");
            echo $url.'&intervalo='.$inter.'&cid='.$rv["cursoId"]."&acao=questionario&p=$ii"."<br>";

            #curl_setopt($ch, CURLOPT_HEADER, TRUE);

            #curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $head = curl_exec($ch);
            echo $head;
            echo "$ii " .$rv["cursoId"];

        }
    }
 
 #$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}
if($outros)
foreach($rows as $rid => $rv)
{
    foreach($vetperiodos as $ind =>$inter)
    {
        foreach(array("31","32") as $ind =>$ii)
        {
            curl_setopt($ch, CURLOPT_URL, $url.'&intervalo='.$inter.'&cid='.$rv["cursoId"]."&acao=avancado&p=$ii");
            echo $url.'&intervalo='.$inter.'&cid='.$rv["cursoId"]."&acao=avancado&p=$ii"."<br>";

            #curl_setopt($ch, CURLOPT_HEADER, TRUE);

            #curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $head = curl_exec($ch);
            echo $head;
            echo "$ii " .$rv["cursoId"];

        }
    }
 
 #$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}
if($questionario)
foreach($rows as $rid => $rv)
{
    foreach($vetperiodos as $ind =>$inter)
    {
        foreach(array("21","32") as $ind =>$ii)
        {
            curl_setopt($ch, CURLOPT_URL, $url.'&intervalo='.$inter.'&cid='.$rv["cursoId"]."&acao=questionario&p=$ii");
            echo $url.'&intervalo='.$inter.'&cid='.$rv["cursoId"]."&acao=questionario&p=$ii"."<br>";

            #curl_setopt($ch, CURLOPT_HEADER, TRUE);

            #curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $head = curl_exec($ch);
            echo $head;
            echo "$ii " .$rv["cursoId"];

        }
    }
 
 #$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
}


if($sentimento)
{
    foreach($rows as $rid => $rv)
    {
        
       
        foreach($rv["foruns"] as $idfo =>$forum){
            if($forum!="")
            {
            
                
            curl_setopt($ch, CURLOPT_URL, $url.'&acao=sentimento&fid='.$forum);
            echo $url.'&acao=sentimento&fid='.$forum;
            
            

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

            $head = curl_exec($ch);
            
            //echo $forum;
            }
         }
       #print_r($result);

    
    }       
       
    curl_close($ch);
   
}
?>