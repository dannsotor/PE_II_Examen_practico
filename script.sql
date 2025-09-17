-- ===========================================================
-- CREACIÓN DE BASE DE DATOS PETI
-- ===========================================================

CREATE DATABASE IF NOT EXISTS PETI;
USE PETI;

-- ===========================================================
-- TABLA USUARIO
-- ===========================================================

CREATE TABLE IF NOT EXISTS USUARIO (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    apellido VARCHAR(120) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password_hash VARCHAR(64) NOT NULL
);

-- ===========================================================
-- TABLA EMPRESA
-- ===========================================================

CREATE TABLE IF NOT EXISTS Empresa (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(200),
    usuario_id INT,
    descripcion TEXT,
    CONSTRAINT fk_empresa_usuario FOREIGN KEY (usuario_id) REFERENCES USUARIO(id)
);

-- ===========================================================
-- TABLAS DE VISIÓN, MISIÓN, VALORES Y OBJETIVOS
-- ===========================================================

CREATE TABLE IF NOT EXISTS Mision (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_mision_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS Vision (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_vision_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS Valores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_valores_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS ObjetivoG (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_objetivog_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS ObjetivoE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    objetivo_id INT,
    CONSTRAINT fk_objetivoe_objetivog FOREIGN KEY (objetivo_id) REFERENCES ObjetivoG(id)
);

-- ===========================================================
-- TABLAS FODA
-- ===========================================================

CREATE TABLE IF NOT EXISTS Fortaleza (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_fortaleza_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS Debilidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_debilidad_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS Oportunidad (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_oportunidad_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS Amenaza (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_amenaza_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

-- ===========================================================
-- TABLAS ESTRATÉGICAS
-- ===========================================================

CREATE TABLE IF NOT EXISTS UNID_ESTRA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_unid_estra_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS IDENT_ESTRA (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_ident_estra_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

CREATE TABLE IF NOT EXISTS CONCLUSION (
    id INT AUTO_INCREMENT PRIMARY KEY,
    descripcion TEXT,
    fecha_registro DATETIME DEFAULT CURRENT_TIMESTAMP,
    empresa_id INT,
    CONSTRAINT fk_conclusion_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

-- ===========================================================
-- TABLA MATRIZ CAME
-- ===========================================================

CREATE TABLE IF NOT EXISTS MatrizCAME (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_accion CHAR(2) NOT NULL,
    descripcion TEXT NOT NULL,
    empresa_id INT NOT NULL,
    CONSTRAINT fk_matrizcame_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

-- ===========================================================
-- TABLA AUTO CADENA VALOR
-- ===========================================================

CREATE TABLE IF NOT EXISTS AutoCadenaValor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    p1 INT NOT NULL, p2 INT NOT NULL, p3 INT NOT NULL, p4 INT NOT NULL, p5 INT NOT NULL,
    p6 INT NOT NULL, p7 INT NOT NULL, p8 INT NOT NULL, p9 INT NOT NULL, p10 INT NOT NULL,
    p11 INT NOT NULL, p12 INT NOT NULL, p13 INT NOT NULL, p14 INT NOT NULL, p15 INT NOT NULL,
    p16 INT NOT NULL, p17 INT NOT NULL, p18 INT NOT NULL, p19 INT NOT NULL, p20 INT NOT NULL,
    p21 INT NOT NULL, p22 INT NOT NULL, p23 INT NOT NULL, p24 INT NOT NULL, p25 INT NOT NULL,
    total INT NOT NULL,
    CONSTRAINT fk_autocadenavalor_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

-- ===========================================================
-- TABLA AUTO PEST
-- ===========================================================

CREATE TABLE IF NOT EXISTS AutoPEST (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    p1 INT NOT NULL, p2 INT NOT NULL, p3 INT NOT NULL, p4 INT NOT NULL, p5 INT NOT NULL,
    p6 INT NOT NULL, p7 INT NOT NULL, p8 INT NOT NULL, p9 INT NOT NULL, p10 INT NOT NULL,
    p11 INT NOT NULL, p12 INT NOT NULL, p13 INT NOT NULL, p14 INT NOT NULL, p15 INT NOT NULL,
    p16 INT NOT NULL, p17 INT NOT NULL, p18 INT NOT NULL, p19 INT NOT NULL, p20 INT NOT NULL,
    p21 INT NOT NULL, p22 INT NOT NULL, p23 INT NOT NULL, p24 INT NOT NULL, p25 INT NOT NULL,
    R1 VARCHAR(250) NOT NULL, R2 VARCHAR(250) NOT NULL, R3 VARCHAR(250) NOT NULL,
    R4 VARCHAR(250) NOT NULL, R5 VARCHAR(250) NOT NULL,
    CONSTRAINT fk_autoPEST_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

-- ===========================================================
-- TABLA BCG
-- ===========================================================

CREATE TABLE IF NOT EXISTS BCG (
    id INT AUTO_INCREMENT PRIMARY KEY,
    empresa_id INT NOT NULL,
    producto VARCHAR(200) NOT NULL,
    participacion_mercado DECIMAL(5,2) NOT NULL,
    tasa_crecimiento DECIMAL(5,2) NOT NULL,
    cuadrante VARCHAR(20) NOT NULL,
    CONSTRAINT fk_bcg_empresa FOREIGN KEY (empresa_id) REFERENCES Empresa(id)
);

-- ===========================================================
-- INSERCIONES - USUARIO (PASS: 123)
-- ===========================================================
INSERT INTO USUARIO (nombre, apellido, email, password_hash)
		VALUES  ('Edinson', 'Luna', 'ediluna@gmail.com', 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3')  
	GO
