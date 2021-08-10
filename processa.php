<?php 
//echo ( '<pre>' . print_r($_POST, 1) . '</pre>' ); //verificar valores recebidos

//trata parâmetros
$acao     = $_POST['acao']      ?? false;
$subclasse= $_POST['subclasse'] ?? false;
$id       = $_POST['id']        ?? null;
$raio     = $_POST['raio']      ?? null;
$base     = $_POST['base']      ?? null;
$altura   = $_POST['altura']    ?? null;
$corBorda = $_POST['cor_borda'] ?? null;

require_once("classes.php");

switch($acao) {
case 'ADICIONAR':
  if ($subclasse == 'circulo') {
    $obj = new CirculoDAO();
    $obj->setCor($corBorda);
    $obj->setRaio($raio);
	$ok = $obj->insere();
    echo $ok ? 'Inserido<br>' : 'erro<br>';
  }
  else if ($subclasse == 'retangulo') {
    $obj = new RetanguloDAO();
    $obj->setCor($corBorda);
    $obj->setBase($base);
    $obj->setAltura($altura);
    $ok = $obj->insere();
    echo $ok ? 'Inserido<br>' : 'erro<br>';
  }
  break;  
  
case 'ATUALIZAR':
  if ($subclasse == 'circulo') {
    $obj = new CirculoDAO();
	$obj->setId($id);
    $obj->setCor($corBorda);
    $obj->setRaio($raio);
    $ok = $obj->atualiza();
	echo $ok ? 'Atualizado<br>' : 'erro<br>';
  }
  else if ($subclasse == 'retangulo') {
    $obj = new RetanguloDAO();
	$obj->setId($id);
    $obj->setCor($corBorda);
    $obj->setBase($base);
    $obj->setAltura($altura);
    $ok = $obj->atualiza();
    echo $ok ? 'Atualizado<br>' : 'erro<br>';
  }
  break;

case 'EXCLUIR':
  if ($subclasse == 'circulo') {
    $obj = new CirculoDAO();
	$obj->setId($id);
	$ok = $obj->exclui();
    echo $ok ? 'Excluído<br>' : 'erro<br>';
  }
  else if ($subclasse == 'retangulo') {
    $obj = new RetanguloDAO();
	$obj->setId($id);
	$ok = $obj->exclui();
    echo $ok ? 'Excluído<br>' : 'erro<br>';
  }
  break;
}

echo '<a href="index.php">Voltar para Página Inicial</a>';
?>