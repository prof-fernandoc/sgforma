<?php
require_once("classes.php");

$dados = Relatorio::buscaTodasFormas();


$cabecalho = '<table border="1" width="100%">'
               .'<tr><td width="1%"><b>Id</b></td>'
               . '<td><b>Dados</b></td><td><b>Desenho</b></td>'
               . '<td width="1%"><b>Editar</b></td>'
               . '<td width="1%"><b>Excluir</b></td></tr>';
$tr = '';

if (count($dados)>0){
  foreach ($dados as $linha ){
	if ($linha instanceof Circulo) {
	  $botaoEditar = '<form method="POST" action="form_circulo.php">'
		. '<input id="id" name="id" type="hidden" value="' . $linha->getId() .'">'
		. '<input id="cor_borda" name="cor_borda" type="hidden" value="' . $linha->getCor() .'">'
		. '<input id="raio" name="raio" type="hidden" value="' . $linha->getRaio() .'">'
	    . '<input type="submit" value="Editar" ></form>';
	
	  $botaoExcluir = '<form method="POST" action="processa.php">'
		. '<input id="subclasse" name="subclasse" type="hidden" value="circulo">'
		. '<input id="id" name="id" type="hidden" value="' . $linha->getId() .'">'
		. '<input id="acao" name="acao" type="hidden" value="EXCLUIR">'
	    . '<input type="submit" value="Excluir" ></form>';	
    }
    else{
	  $botaoEditar = '<form method="POST" action="form_retangulo.php">'
		. '<input id="id" name="id" type="hidden" value="' . $linha->getId() .'">'
		. '<input id="cor_borda" name="cor_borda" type="hidden" value="' . $linha->getCor() .'">'
		. '<input id="base" name="base" type="hidden" value="' . $linha->getBase() .'">'
		. '<input id="altura" name="altura" type="hidden" value="' . $linha->getAltura() .'">'
	    . '<input type="submit" value="Editar" ></form>';
	
	  $botaoExcluir = '<form method="POST" action="processa.php">'
		. '<input id="subclasse" name="subclasse" type="hidden" value="retangulo">'
		. '<input id="id" name="id" type="hidden" value="' . $linha->getId() .'">'
		. '<input id="acao" name="acao" type="hidden" value="EXCLUIR">'
	    . '<input type="submit" value="Excluir" ></form>';		
    }
	
    $tr .= '<tr>';
	$tr .= '<td>' . $linha->getId() . '</td>';
	$tr .= '<td>' . $linha . '</td>';
	$tr .= '<td>' . $linha->desenhar() . '</td>';	
	$tr .= '<td>' . $botaoEditar . '</td>';
	$tr .= '<td>' . $botaoExcluir . '</td>';
	$tr .='</tr>';
	//die ('<pre>' . print_r((array) $linha,1) . '</pre>');
  }
}
$tr .= '</table>';
?>

<html>
<head><title>Sistema Gerenciador de Formas</title></head>
<style> td {text-align: center}</style>
<body>
<table width="100%"> <tr><td align="center">
<h1>Sistema Gerenciador de Formas</h1>

<table width="100%" border="1">
  <tr>
    <td width="33%"><a href="form_circulo.php"><b>Cadastrar Novo Círculo</b></a></td>
    <td width="34%"><a href="form_retangulo.php"><b>Cadastrar Novo Retângulo</b></a></td>
    <td width="33%"><a href="restaura_bd.php"><b>Restaura BD</b></a></td>
  </tr>
</table>
<br/><br/>

<?php echo $cabecalho . $tr; ?>

</td></tr>
</table>
</body>
</html>