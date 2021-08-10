<?php

Interface Desenhavel {
  public function desenhar();
}



Abstract Class Forma implements Desenhavel{
  private $id;
  private $cor;

  public function getId(){
    return $this->id;
  }

  public function getCor(){
    return $this->cor;
  }

  public function setId($id){
    $this->id = $id;	  
  }

  public function setCor($cor){
    $this->cor = $cor;	  
  }
  
  abstract public function getArea ();
  
  abstract public function getPerimetro ();
  
  abstract public function __toString ();
}



Class Circulo extends Forma {
  private $raio;

  public function setRaio($raio){
    $this->raio = $raio;	  
  }

  public function getRaio(){
    return $this->raio;	  
  }
    
  public function getArea () {
    return round( pi() * ($this->raio ^ 2), 2);
  }

  public function getPerimetro () {
    return round( 2 * pi() * $this->raio, 2);
  }
  
  public function __toString (){
    return  '<div><b>Id:</b> ' . $this->getId() . '<br/>'
	      . '<b>Área:</b> ' . $this->getArea() . '<br/>'
	      . '<b>Perímetro:</b> ' . $this->getPerimetro() . '<br/>'
	      . '<b>Raio:</b> ' . $this->raio . '</div>';
  }
  
  public function desenhar() {
    return "<canvas id=\"" . $this->getId() .
			 "\" width=\"" . 2*$this->raio+10 .
			 "\" height=\"" . 2*$this->raio+10 .
			 "\"></canvas>
	  <script type=\"text/javascript\">
		var canvas = document.getElementById('" . $this->getId() . "');
		if (canvas.getContext)
		{
		  var ctx = canvas.getContext('2d'); 
		  var X = canvas.width / 2;
		  var Y = canvas.height / 2;
		  var R = ". $this->raio .";
		  ctx.beginPath();
		  ctx.arc(X, Y, R, 0, 2 * Math.PI, false);
		  ctx.lineWidth = 3;
		  ctx.strokeStyle = '". $this->getCor() ."';
		  ctx.stroke();
		}
	  </script>";
  }
}



Class Retangulo extends Forma {
  private $base;
  private $altura;
  
  public function setBase($base){
    $this->base = $base;	  
  }
  
  public function setAltura($altura){
    $this->altura = $altura;
  }
  
  public function getBase(){
    return $this->base;	  
  }
  
  public function getAltura(){
    return $this->altura;
  }
  
  public function getArea () {
    return round( $this->base * $this->altura, 2);
  }

  public function getPerimetro () {
    return round( 2 * $this->base + 2 * $this->altura, 2);
  }

  public function __toString (){
    return  '<div><b>Id:</b> ' . $this->getId() . '<br/>'
	      . '<b>Área:</b> ' . $this->getArea() . '<br/>'
	      . '<b>Perímetro:</b> ' . $this->getPerimetro() . '<br/>'
	      . '<b>Base:</b> ' . $this->base . '<br/>'
	      . '<b>Altura:</b> ' . $this->altura . '</div>';
  }
  
  public function desenhar() {
    return " <span id=\"" . $this->getId() . "\" style=\"
              width:" . $this->base . "px;
              height:" . $this->altura . "px;
              border: solid 4px ". $this->getCor() .			  
              "; display:inline-block\"></span> ";
  }
}



Class UtilBD {
  private static $servidor = 'localhost';
  private static $usuario  = '';
  private static $senha    = '';
  private static $database = 'bd2';
  private static $porta    = 3306;
  private static $link     = null;
  
  public static function getLink (){
	if ( self::$link==null )
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	try {
	  $varlink = new mysqli(self::$servidor,
                        self::$usuario,
                        self::$senha,
                        self::$database,
                        self::$porta);

	  if ( $varlink->connect_errno ) die ('Verifique os dados de conexão na Classe UtilBD.');
	  $varlink->autocommit(false);
	  self::$link = $varlink;
    }
	catch (Exception $e) {
      die ('Verifique os dados de conexão na Classe UtilBD. ' . $e->getMessage());
    }
	return self::$link;
  }
  
  public static function restauraBD(){
	mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
	$cmdDatabase = 'CREATE DATABASE IF NOT EXISTS ' . self::$database . ' ;';
	$arquivo = 'bd.sql';
	$sql = file_get_contents ( $arquivo );

    $ok = true;
    try {
      $linkSemDB = new mysqli(self::$servidor,
                      self::$usuario,
				      self::$senha,
					  null,
					  self::$porta);
      if ( $linkSemDB->connect_errno ) die ('Verifique os dados de conexão na Classe UtilBD.');
      if ($ok) {
        $ok = $linkSemDB->query( $cmdDatabase );
        if ($ok) {
          $ok = $linkSemDB->select_db( self::$database );
          if ($ok) {
            $ok = $linkSemDB->multi_query( $sql );
          }
        }
      } else die ('Verifique os dados de conexão na Classe UtilBD.');
    }
    catch (Exception $e) {
      $ok = false;
      echo $e->getMessage();
    }
    finally {
      return $ok;
    }
  }

}



Interface DAO {
	
  public static function buscaTodos();
  
  public static function buscaPorId($id);

  public function insere();
  
  public function atualiza();

  public function exclui();

}



Class CirculoDAO extends Circulo implements DAO {
	
  public static function buscaTodos() {
    $array = array();
    $link = UtilBD::getLink();
    $query = "SELECT id, cor_borda, raio FROM relatorio WHERE subclasse='circulo'";	
    if ($result = $link->query($query)) {
      while($linha = $result->fetch_object()){
        $obj = new Circulo();
        $obj->setId($linha->id);
        $obj->setCor($linha->cor_borda);
        $obj->setRaio($linha->raio);
        $array[] = $obj;
      }
    }
    $result->free();
    return $array;
  }
  
  public static function buscaPorId($id) {
    $array = array();
    $link = UtilBD::getLink();
    $query = "SELECT id, cor_borda, raio FROM relatorio WHERE id = ? AND subclasse='circulo'";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($stmt->data_seek(0)) {
	  $stmt->bind_result( $id, $corBorda, $raio );
      while($result->fetch_object()){
        $obj = new Circulo();
        $obj->setId($id);
        $obj->setCor($corBorda);
        $obj->setRaio($raio);
        $array[] = $obj;
      }
    }
    $stmt->close();
    return $array;
  }

  public function insere() {
    $link = UtilBD::getLink();
    
	$id = null;
    $corBorda = $this->getCor() ?? false;
    $raio = $this->getRaio() ?? false;
	
	if ( !($corBorda && $raio) ) return false;
	
    $query1 = "INSERT INTO forma (id, cor_borda) VALUES (default, ?)";
	$query2 = "INSERT INTO circulo (id, raio) VALUES (?, ?)";
	
    try {
       $link->begin_transaction();
       $stmt1 = $link->prepare($query1);
       $stmt1->bind_param("s", $corBorda);
       $stmt1->execute();
       $id = $link->insert_id;
       $stmt2 = $link->prepare($query2);
       $stmt2->bind_param("id", $id, $raio);
       $stmt2->execute();
       $link->commit();
    }
    catch (Exception $e) {
       $link->rollback();
       die( $e->getMessage() );
    }
    finally {
       $result = ( ($stmt1->affected_rows==$stmt2->affected_rows) && ($stmt2->affected_rows>0) );
       $stmt1->close();
       $stmt2->close();
       return $result;
    }
  }

  public function atualiza() {
    $link = UtilBD::getLink();
    
	$id = $this->getId() ?? false;
    $corBorda = $this->getCor() ?? false;
    $raio = $this->getRaio() ?? false;
	if ( !($id && $corBorda && $raio)) return false;

    $query1 = "UPDATE forma SET cor_borda = ? WHERE id = ?";
	$query2 = "UPDATE circulo SET raio = ? WHERE id = ?";
	
    try {
       $link->begin_transaction();
       $stmt1 = $link->prepare($query1);
       $stmt1->bind_param("si", $corBorda, $id);
       $stmt1->execute();
       $stmt2 = $link->prepare($query2);
       $stmt2->bind_param("di", $raio, $id);
       $stmt2->execute();
       $link->commit();
    }
    catch (Exception $e) {
       $link->rollback();
       die( $e->getMessage() );
    }
    finally {
       $result = ( ($stmt1->affected_rows>0) || ($stmt2->affected_rows>0) );
       $stmt1->close();
       $stmt2->close();
       return $result;
    }
  }

  public function exclui() {
    $link = UtilBD::getLink();
    
    $id = $this->getId() ?? false;
    if ( !($id) ) return false;

    $query1 = "DELETE FROM circulo WHERE id = ?";
    $query2 = "DELETE FROM forma WHERE id = ?";

    try {
       $link->begin_transaction();
       $stmt1 = $link->prepare($query1);
       $stmt1->bind_param("i", $id);
       $stmt1->execute();
       $stmt2 = $link->prepare($query2);
       $stmt2->bind_param("i", $id);
       $stmt2->execute();
       $link->commit();
    }
    catch (Exception $e) {
       $link->rollback();
       die( $e->getMessage() );
    }
    finally {
       $result = ( ($stmt1->affected_rows==$stmt2->affected_rows) && ($stmt2->affected_rows>0) );
       $stmt1->close();
       $stmt2->close();
       return $result;
    }
  }

}



Class RetanguloDAO extends Retangulo implements DAO {
	
  public static function buscaTodos() {
    $array = array();
    $link = UtilBD::getLink();
    $query = "SELECT id, cor_borda, raio FROM relatorio WHERE subclasse='retangulo'";	
    if ($result = $link->query($query)) {
      while($linha = $result->fetch_object()){
        $obj = new Circulo();
        $obj->setId($linha->id);
        $obj->setCor($linha->cor_borda);
        $obj->setBase($linha->base);
        $obj->setAltura($linha->altura);
        $array[] = $obj;
      }
    }
    $result->free();
    return $array;
  }
  
  public static function buscaPorId($id) {
    $array = array();
    $link = UtilBD::getLink();
    $query = "SELECT id, cor_borda, base, altura FROM relatorio WHERE id = ? AND subclasse='retangulo'";
    $stmt = $link->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    if ($stmt->data_seek(0)) {
	  $stmt->bind_result( $id, $corBorda, $base, $altura );
      while($result->fetch_object()){
        $obj = new Circulo();
        $obj->setId($id);
        $obj->setCor($corBorda);
        $obj->setBase($base);
        $obj->setAltura($altura);
        $array[] = $obj;
      }
    }
    $stmt->close();
    return $array;
  }

  public function insere() {
    $link = UtilBD::getLink();
    
	$id = null;
    $corBorda = $this->getCor() ?? false;
    $base = $this->getBase() ?? false;
    $altura = $this->getAltura() ?? false;
	if ( !($corBorda && $base && $altura)) return false;

    $query1 = "INSERT INTO forma (id, cor_borda) VALUES (default, ?)";
	$query2 = "INSERT INTO retangulo (id, base, altura) VALUES (?, ?, ?)";

    try {
       $link->begin_transaction();
       $stmt1 = $link->prepare($query1);
       $stmt1->bind_param("s", $corBorda);
       $stmt1->execute();
       $id = $link->insert_id;
       $stmt2 = $link->prepare($query2);
       $stmt2->bind_param("idd", $id, $base, $altura);
       $stmt2->execute();
       $link->commit();
    }
    catch (Exception $e) {
       $link->rollback();
       die( $e->getMessage() );
    }
    finally {
       $result = ( ($stmt1->affected_rows==$stmt2->affected_rows) && ($stmt2->affected_rows>0) );
       $stmt1->close();
       $stmt2->close();
       return $result;
    }
  }

  public function atualiza() {
    $link = UtilBD::getLink();
    
	$id = $this->getId() ?? false;
    $corBorda = $this->getCor() ?? false;
    $base = $this->getBase() ?? false;
    $altura = $this->getAltura() ?? false;
	if ( !($id && $corBorda && $base && $altura)) return false;

    $query1 = "UPDATE forma SET cor_borda = ? WHERE id = ?";
	$query2 = "UPDATE retangulo SET base = ?, altura = ? WHERE id = ?";
	
    try {
       $link->begin_transaction();
       $stmt1 = $link->prepare($query1);
       $stmt1->bind_param("si", $corBorda, $id);
       $stmt1->execute();
       $stmt2 = $link->prepare($query2);
       $stmt2->bind_param("ddi", $base, $altura, $id);
       $stmt2->execute();
       $link->commit();
    }
    catch (Exception $e) {
       $link->rollback();
       die( $e->getMessage() );
    }
    finally {
       $result = ( ($stmt1->affected_rows>0) || ($stmt2->affected_rows>0) );
       $stmt1->close();
       $stmt2->close();
       return $result;
    }
  }

  public function exclui() {
    $link = UtilBD::getLink();
    
    $id = $this->getId() ?? false;
    if ( !($id) ) return false;

    $query1 = "DELETE FROM retangulo WHERE id = ?";
    $query2 = "DELETE FROM forma WHERE id = ?";
	
    try {
       $link->begin_transaction();
       $stmt1 = $link->prepare($query1);
       $stmt1->bind_param("i", $id);
       $stmt1->execute();
       $stmt2 = $link->prepare($query2);
       $stmt2->bind_param("i", $id);
       $stmt2->execute();
       $link->commit();
    }
    catch (Exception $e) {
       $link->rollback();
       die( $e->getMessage() );
    }
    finally {
       $result = ( ($stmt1->affected_rows==$stmt2->affected_rows) && ($stmt2->affected_rows>0) );
       $stmt1->close();
       $stmt2->close();
       return $result;
    }
  }

}



Class Relatorio {
	
  public static function buscaTodasFormas(){
    $array = array();
    $link = UtilBD::getLink();
	$query = "SELECT * FROM relatorio ORDER BY id, raio, (base * altura)";
    if ($result = $link->query($query)) {
	  while($linha = $result->fetch_object()){
		if ($linha->subclasse == "circulo") {
		   $obj = new Circulo();
           $obj->setId($linha->id);
           $obj->setCor($linha->cor_borda);
		   $obj->setRaio($linha->raio);
		   $array[] = $obj;
		}
		else if ($linha->subclasse == "retangulo"){
		   $obj = new Retangulo();
           $obj->setId($linha->id);
           $obj->setCor($linha->cor_borda);
		   $obj->setBase($linha->base);
		   $obj->setAltura($linha->altura);
		   $array[] = $obj;
		}
      }
	  $result->free();
	}
    return $array;
  }

}

?>