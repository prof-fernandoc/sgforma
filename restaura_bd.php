<?php 
require_once("classes.php");
$restauracaoOk = UtilBD::restauraBD();
echo $restauracaoOk ? 'Bd Restaurado com sucesso!<br>' : 'Erro.<br>';
echo '<a href="index.php">Voltar para Página Inicial</a>';
?>