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

// Local Database Connection (MySQL)
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_port = getenv('DB_PORT') ?: '3306';
$db_name = getenv('DB_NAME') ?: 'lumix';
$db_user = getenv('DB_USER') ?: 'root';
$db_password = getenv('DB_PASSWORD') ?: '';

try {
    $conn_local = new PDO(
        'mysql:host=' . $db_host . ';port=' . $db_port . ';dbname=' . $db_name,
        $db_user,
        $db_password
    );
    echo "Conectado ao banco local (MySQL)<br>";
} catch (PDOException $e) {
    die("Erro ao conectar ao banco local: " . $e->getMessage() . "<br/>");
}

$datas_del=array('2023-01-02 00:00:00','2023-11-21 00:00:00');
$dtini=$datas_del[0];
$dtfim = $datas_del[1];

echo "<br>inicio:".date('d-m-Y H:i:s')."<br>";
$conn_local->query("delete from aluno where alunoDataCriacao between '$dtini' and '$dtfim'");
$conn_local->query("delete from alunoinfo where aluno_alunoId in (select alunoId from aluno where alunoDataCriacao between '$dtini' and '$dtfim')");
$conn_local->query("delete from alunoinscricao where alunoInscricaoData  between '$dtini' and '$dtfim' ");


$conn_local->query("delete from acessoconteudo where dataUltimaModificacao between '$dtini' and '$dtfim'");
$conn_local->query("delete from conteudo");
$conn_local->query("delete from cursoresponsavel where interna='N'");
$conn_local->query("delete from usuario");



$conn_local->query("delete from questaoresposta where questaoRespostaData between '$dtini' and '$dtfim'");
$conn_local->query("delete from questao");
$conn_local->query("delete from quiz");

$conn_local->query("delete from forumpost where forumPostData between '$dtini' and '$dtfim'");
$conn_local->query("delete from forumtopico");
$conn_local->query("delete from forum");

$conn_local->query("delete from curso");
$conn_local->query("delete from areacurso");


echo "fim:".date('d-m-Y H:i:s');

















