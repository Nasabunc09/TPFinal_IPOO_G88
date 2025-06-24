<?php

Class Pasajero extends Persona{

    private $idViaje;
    private $mensajeOperacion;

    public function __construct(){

        parent::__construct();
        $this->idViaje = 0;
    }

    public function getIdViaje(){
        return $this->idViaje;
    }

    public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}

    public function setMensajeOperacion($mensajeOperacion){
		$this->mensajeOperacion = $mensajeOperacion;
	}
    public function setIdViaje ($idViaje){
        $this->idViaje = $idViaje;
    }

    public function cargar($nombre, $apellido, $documento, $telefono = 0, $idViaje = 0)
    {
        // llama a los metodos de la clase padre
        parent::cargar($documento,$nombre, $apellido,$telefono);
        $this->setTelefono($telefono);
        $this->setIdViaje($idViaje);
    }

    public function insertar(){

        $database = new BaseDatos;
        $respuesta = false;
        // se llama al metodo insertar de la clase padre y si se inserta correctamente se inserta en la tabla pasajero
        if (parent::insertar()) {
            $consulta = "INSERT INTO pasajero(pdocumento, ptelefono, idviaje) VALUES (
                        '" . $this->getNrodoc() . "',
                         " . $this->getTelefono() . ",
                         " . $this->getIdViaje() . "
                         )";
            if ($database->iniciar()) {
                if ($database->ejecutar($consulta)) {
                    $respuesta =  true;
                } else {
                    $this->setMensajeoperacion($database->getError());
                }
            } else {
                $this->setMensajeoperacion($database->getError());
            }
        }

        return $respuesta;
    }

    public function buscar($documento){

        $database = new BaseDatos;
        $consulta = "SELECT * FROM pasajero INNER JOIN persona ON pasajero.pdocumento = persona.documento WHERE pdocumento='" . $documento . "'";
        $rta = false;
        if ($database->iniciar()) {
            if ($database->ejecutar($consulta)) {
                if ($pasajero = $database->registro()) {
                    $this->cargar(
                        $pasajero["nombre"], 
                        $pasajero["apellido"], 
                        $pasajero["documento"], 
                        $pasajero["ptelefono"], 
                        $pasajero["idviaje"]
                    );
                    $rta = true;
                }
            } else {
                $this->setMensajeOperacion($database->getError());
            }
        } else {
            $this->setMensajeOperacion($database->getError());
        }
        return $rta;
    }

    public function modificar(){

        $database = new BaseDatos;
        $respuesta = false;
        if (parent::modificar()) {
            $consulta = "UPDATE pasajero SET 
                        ptelefono = " . $this->getTelefono() . ",
                        idviaje = " . $this->getIdViaje() .
                        " WHERE pdocumento = " . $this->getNrodoc();

            if ($database->iniciar()) {
                if ($database->ejecutar($consulta)) {
                    $respuesta = true;
                } else {
                    $this->setMensajeOperacion($database->getError());
                }
            } else {
                $this->setMensajeOperacion($database->getError());
            }
        }

        return $respuesta;
    }

    public function eliminar(){

        $database = new BaseDatos;
        $respuesta = false;

        $consulta = "DELETE FROM pasajero WHERE pdocumento = '" . $this->getNrodoc() . "'";
        if ($database->iniciar()) {
            if ($database->ejecutar($consulta)) {
                $respuesta = true;
                parent::eliminar();
            } else {
                $this->setMensajeOperacion($database->getError());
            }
        } else {
            $this->setMensajeOperacion($database->getError());
        }

        return $respuesta;
    }

    public function listar($condicion = ""){

        $arregloPasajero = null;
        $database = new BaseDatos;
        $consulta = "SELECT * FROM pasajero INNER JOIN persona ON persona.documento = pasajero.pdocumento ";
        if ($condicion != "") {
            $consulta .= "WHERE $condicion ";
        }

        $consulta .= "ORDER BY apellido";

        if ($database->iniciar()) {
            if ($database->ejecutar($consulta)) {
                $arregloPasajero = [];
                
                while ($pasajeroEncontrado = $database->registro()) {
                    $pasajero = new Pasajero;
                    $pasajero->cargar(
                        $pasajeroEncontrado["nombre"],
                        $pasajeroEncontrado["apellido"],
                        $pasajeroEncontrado["pdocumento"],
                        $pasajeroEncontrado["ptelefono"],
                        $pasajeroEncontrado["idviaje"]
                    );
                    array_push($arregloPasajero, $pasajero);
                }
            } else {
                $this->setMensajeOperacion($database->getError());
            }
        } else {
            $this->setMensajeOperacion($database->getError());
        }

        return $arregloPasajero;
    }
   
	public function __toString(){

        $cadena = "-----PASAJERO-----."."\n".
        $cadena = parent::__toString();
        $cadena = "ID VIAJE: ".$this->getIdViaje()."\n";
        

        return $cadena;
    }   
}
?>