CREATE DATABASE piapoi
    WITH
    OWNER = postgres
    ENCODING = 'UTF8'
    LC_COLLATE = 'es-PE'
    LC_CTYPE = 'es-PE'
    TABLESPACE = pg_default
    CONNECTION LIMIT = -1
    IS_TEMPLATE = False;


CREATE TABLE usuario (
  id           SERIAL PRIMARY KEY,
  tipo         VARCHAR(1) NOT NULL,
  numera       INT NOT NULL,
  cargo        VARCHAR(100) NOT NULL,
  usuario      VARCHAR(50) NOT NULL,
  clave        VARCHAR(50) NOT NULL
);

CREATE TABLE financia (
  id           SERIAL PRIMARY KEY,
  codigo       VARCHAR(6) NOT NULL,
  fondo        VARCHAR(60) NOT NULL
);

CREATE TABLE oocc (
  id           SERIAL PRIMARY KEY,
  numera       INT NOT NULL,
  oficina      VARCHAR(80) NOT NULL
);

CREATE TABLE oodd (
  id           SERIAL PRIMARY KEY,
  numera       INT NOT NULL,
  oodd         VARCHAR(50) NOT NULL
);

CREATE TABLE eess (
  id           SERIAL PRIMARY KEY,
  oodd_id      INT NOT NULL,
  codigo       VARCHAR(10) NOT NULL,
  eess         VARCHAR(70) NOT NULL,
  FOREIGN KEY (oodd_id) REFERENCES oodd(id)
);

CREATE TABLE actioodd (
  id           SERIAL PRIMARY KEY,
  actividad    VARCHAR(50) NOT NULL,
  prioridad    INT DEFAULT 0
);

CREATE TABLE actioocc (
  id           SERIAL PRIMARY KEY,
  oocc_id      INT NOT NULL,
  fondo        INT NOT NULL DEFAULT 0,
  codigo       VARCHAR(16) NOT NULL,
  actividad    VARCHAR(200) NOT NULL,
  prioridad    INT,
  estado       INT DEFAULT 0,
  FOREIGN KEY (oocc_id) REFERENCES oocc(id)
);

CREATE TABLE pofi (
  id           SERIAL PRIMARY KEY,
  color        INT NOT NULL DEFAULT 0,
  codigo       VARCHAR(10) NOT NULL,
  pofi         VARCHAR(80) NOT NULL,
  fonafe1      VARCHAR(80),
  fonafe2      VARCHAR(80),
  fonafe3      VARCHAR(80),
  mef1         VARCHAR(3),
  mef2         VARCHAR(20),
  mef3         VARCHAR(100)
);

CREATE TABLE cabeza (
  id           SERIAL PRIMARY KEY,
  oodd_id      INT,
  eess_id      INT,
  actioodd_id  INT,
  oocc_id      INT,
  actioocc_id  INT,
  cerrado      INT DEFAULT 0,
  imagen       INT NOT NULL DEFAULT 0,
  FOREIGN KEY (eess_id) REFERENCES eess(id),
  FOREIGN KEY (oodd_id) REFERENCES oodd(id),
  FOREIGN KEY (oocc_id) REFERENCES oocc(id),
  FOREIGN KEY (actioodd_id) REFERENCES actioodd(id),
  FOREIGN KEY (actioocc_id) REFERENCES actioocc(id)
);

CREATE TABLE IF NOT EXISTS detalle (
  id           SERIAL PRIMARY KEY,
  cabeza_id    INT NOT NULL,
  financia_id  INT,
  pofi_id      INT NOT NULL,
  tipo         INT DEFAULT 0,
  estimacion   INT DEFAULT 0,
  enero        INT DEFAULT 0,
  febrero      INT DEFAULT 0,
  marzo        INT DEFAULT 0,
  abril        INT DEFAULT 0,
  mayo         INT DEFAULT 0,
  junio        INT DEFAULT 0,
  julio        INT DEFAULT 0,
  agosto       INT DEFAULT 0,
  septiembre   INT DEFAULT 0,
  octubre      INT DEFAULT 0,
  noviembre    INT DEFAULT 0,
  diciembre    INT DEFAULT 0,
  total2026    INT DEFAULT 0,
  proy2027     INT DEFAULT 0,
  proy2028     INT DEFAULT 0,
  proy2029     INT DEFAULT 0,
  FOREIGN KEY (financia_id) REFERENCES financia(id),
  FOREIGN KEY (pofi_id) REFERENCES pofi(id),
  FOREIGN KEY (cabeza_id) REFERENCES cabeza(id)
);
