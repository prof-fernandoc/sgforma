<?php 
  //echo '<pre>' . print_r($_POST, 1) . '</pre>';
?>
<?php

//tratamento de parâmetros enviados por POST
$acao = isset($_POST['id']) ? 'ATUALIZAR' : 'ADICIONAR';
$id       = $_POST['id'] ?? null;
$raio     = $_POST['raio'] ?? 50;
$corBorda = $_POST['cor_borda'] ?? 'black';


//gerando uma array de cores.
$cores = array( 'Branco' => 'white', 'Cinza 1' => '#EEEEEE', 'Cinza 2' => '#CCCCCC', 
              'Cinza 3' => '#AAAAAA', 'Cinza 4' => '#999999', 'Cinza 5' => '#777777', 
              'Cinza 6' => '#555555', 'Cinza 7' => '#333333', 'Preto' => 'black',
              'Marinho' => 'navy', 'Azul' => 'blue', 'Azul Real' => 'royalblue', 
              'Teal' => '#006666', 'Verde' => 'green', 'Verde Claro' => 'yellowgreen', 
              'Verde Limão' => 'lime', 'Amarelo' => 'yellow', 'Dourado' => 'gold', 
              'Laranja' => 'orange', 'Vermelho' => 'red', 'Vermelho Escuro' => 'darkred', 
              'Roxo' => 'indigo', 'Púrpura' => 'purple', 'Magenta' => 'magenta', 
              'Violeta' => 'violet', 'Pink' => 'pink'); 
$selected = '';
?>

<html>
<head><title>Cadastro de Círculo</title></head>
<body>
<table width="100%" height="100%"> <tr><td align="center">
<h1>Cadastro de Círculo</h1>

<table border="1"><tr><td align="center">
<form method="post" action="processa.php">
<input type="hidden" id="acao" name="acao" value="<?php echo $acao; ?>">
<input type="hidden" id="subclasse" name="subclasse" value="circulo">
<?php if ($acao=='ATUALIZAR'): ?>
<input type="hidden" id="id" name="id" value="<?php echo $id; ?>">
<?php endif; ?>

Escolha a cor da Forma: <br/>
<!-- <input type="color" id="cor_borda" name="cor_borda" value="#FF0000" /> -->
<select id="cor_borda" name="cor_borda" 
  onchange="this.style.backgroundColor = this.value;"
  style="background-color: <?php echo $corBorda; ?>">
  <?php foreach ( array_keys($cores) as $chave) : ?>
  <option style="background-color: <?php echo $cores[$chave]; ?>" 
          value="<?php echo $cores[$chave]; ?>"
		  <?php if ($corBorda == $cores[$chave]) { $selected = 'selected'; echo $selected; } ?>>
    <?php echo $chave; ?>
  </option>
  <?php endforeach ?>
<?php if ( $selected == '' ) : ?>
  <option style="background-color: <?php echo $corBorda; ?>" value="<?php echo $corBorda; ?>" selected>
     <?php echo $corBorda; ?>
  </option>
<?php endif ?>
</select>
<br/>
<br/>

Escolha o raio do círculo: <br/>
<input type="range" name="raio" id="raio" min="10" max="300" step="10" value="<?php echo $raio; ?>"
   onchange="document.getElementById('raio-output').value = this.value;" />
<output id="raio-output" class="raio-output" for="raio"><?php echo $raio; ?></output>
<br/>
<br/>


<input type="submit" value="Gravar">
<br/><br/>
</form>
</td></tr></table>
<br/>
<a href="index.php">Voltar para Início.</a>

</td></tr>
</table> 
</body>
</html>