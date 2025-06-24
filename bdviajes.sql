-- Crear base de datos
CREATE DATABASE IF NOT EXISTS bdviajes;
USE bdviajes;

-- Tabla empresa
CREATE TABLE empresa (
    idempresa BIGINT AUTO_INCREMENT,
    enombre VARCHAR(150),
    edireccion VARCHAR(150),
    PRIMARY KEY (idempresa)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Tabla persona
CREATE TABLE persona (
    documento VARCHAR(15),
    nombre VARCHAR(30),
    apellido VARCHAR(30),
    telefono VARCHAR(15),
    PRIMARY KEY (documento)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Tabla responsable
CREATE TABLE responsable (
    rnumeroempleado BIGINT AUTO_INCREMENT,
    rnumerolicencia BIGINT,
    rnombre VARCHAR(150), 
    rapellido VARCHAR(150),
    rdocumento VARCHAR(15),
    PRIMARY KEY (rnumeroempleado),
    FOREIGN KEY (rdocumento) REFERENCES persona(documento)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Tabla viaje
CREATE TABLE viaje (
    idviaje BIGINT AUTO_INCREMENT, 
    vorigen VARCHAR(150),
    vdestino VARCHAR(150),
    vcantmaxpasajeros INT,
    idempresa BIGINT,
    rnumeroempleado BIGINT,
    vimporte FLOAT,
    PRIMARY KEY (idviaje),
    FOREIGN KEY (idempresa) REFERENCES empresa(idempresa)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (rnumeroempleado) REFERENCES responsable(rnumeroempleado)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

-- Tabla pasajero
CREATE TABLE pasajero (
    pdocumento VARCHAR(15),
    idviaje BIGINT,
    PRIMARY KEY (pdocumento),
    FOREIGN KEY (pdocumento) REFERENCES persona(documento)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    FOREIGN KEY (idviaje) REFERENCES viaje(idviaje)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

