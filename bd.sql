DROP VIEW  IF EXISTS relatorio;
DROP TABLE IF EXISTS retangulo;
DROP TABLE IF EXISTS circulo;
DROP TABLE IF EXISTS forma;

CREATE TABLE forma (
  id INT NOT NULL AUTO_INCREMENT,
  cor_borda VARCHAR(20) NOT NULL,
  PRIMARY KEY (id)
) ENGINE = InnoDB;

CREATE TABLE retangulo (
  id INT NOT NULL,
  altura DOUBLE NOT NULL,
  base DOUBLE NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id) REFERENCES forma (id)
) ENGINE = InnoDB;

CREATE TABLE circulo (
  id INT NOT NULL,
  raio DOUBLE NOT NULL,
  PRIMARY KEY (id),
  FOREIGN KEY (id) REFERENCES forma (id)
) ENGINE = InnoDB;

CREATE VIEW relatorio AS
SELECT 
     forma.id,
	 CASE 
	   WHEN (cir.raio IS NOT NULL) THEN "circulo"
	   WHEN (ret.base IS NOT NULL) THEN "retangulo"
	 END AS subclasse,
     forma.cor_borda, cir.raio, ret.base, ret.altura
FROM forma
LEFT JOIN circulo AS cir on cir.id = forma.id
LEFT JOIN retangulo AS ret on ret.id = forma.id;

-- inserindo um círculo verde
START TRANSACTION;
INSERT INTO forma (id, cor_borda) VALUES (default, "green");
SELECT @IDFORMA := last_insert_id();
INSERT INTO circulo (id, raio) VALUES (@IDFORMA, 50);
COMMIT;

-- inserindo um círculo vermelho
START TRANSACTION;
INSERT INTO forma (id, cor_borda) VALUES (default, "red");
SELECT @IDFORMA := last_insert_id();
INSERT INTO circulo (id, raio) VALUES (@IDFORMA, 50);
COMMIT;

-- inserindo um retangulo verde
START TRANSACTION;
INSERT INTO forma (id, cor_borda) VALUES (default, "green");
SELECT @IDFORMA := last_insert_id();
INSERT INTO retangulo (id, base, altura) VALUES (@IDFORMA, 100, 100);
COMMIT;

-- inserindo um retangulo vermelho
START TRANSACTION;
INSERT INTO forma (id, cor_borda) VALUES (default, "red");
SELECT @IDFORMA := last_insert_id();
INSERT INTO retangulo (id, base, altura) VALUES (@IDFORMA, 100, 100);
COMMIT;

CREATE INDEX ix_cor_borda ON forma (cor_borda);

CREATE INDEX ix_raio ON circulo (raio);

-- CREATE INDEX ix_base ON retangulo (base);
CREATE INDEX ix_altura ON retangulo (altura);
CREATE INDEX ix_base_altura ON retangulo (base, altura);
CREATE INDEX ix_base_x_altura ON retangulo ((base * altura)); -- MYSQL >= 8.0.13