<?php

Class Pasajero extends Persona{
    
    
    private $idViaje;
    private $mensajeOperacion;
    private $activo;

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

    public function getActivo() {
    return $this->activo;
	}

	public function setActivo($valor) {
		$this->activo = $valor;
	}

    public function setMensajeOperacion($mensajeOperacion){
		$this->mensajeOperacion = $mensajeOperacion;
	}
    public function setIdViaje ($idViaje){
        $this->idViaje = $idViaje;
    }

    public function cargar($nombre,$apellido,$documento,$telefono,$idViaje = 0,$activo=1)
    {
        // llama a los metodos de la clase padre
        parent::cargar($documento,$nombre,$apellido,$telefono);
        $this->setIdViaje($idViaje);
        $this->setActivo($activo);
    }

    public function insertar(){

        $database = new BaseDatos;
        $respuesta = false;
        // Inserta en persona si no existe
        if (!parent::buscar($this->getNrodoc())) {
            if (!parent::insertar()) {
                $this->setMensajeOperacion("Error al insertar persona: " . $this->getMensajeOperacion());
                return false;
            }
        }

        // Luego inserta en pasajero
        $consulta = "INSERT INTO pasajero(pdocumento, idviaje, activo) 
                    VALUES (" . $this->getNrodoc() . ",
                            " . $this->getIdViaje() . ",
                            1)";

        if ($database->iniciar()) {
            if ($database->ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($database->getError());
            }
        } else {
            $this->setMensajeOperacion($database->getError());
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
                        $pasajero["telefono"], 
                        $pasajero["idviaje"],
                        $pasajero["activo"],
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
                        idviaje = " . $this->getIdViaje() .
                        " WHERE pdocumento = '" . $this->getNrodoc() . "'";

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

    public function eliminar() {
        $database = new BaseDatos;
        $respuesta = false;

        // Borrado lógico de la tabla pasajero
        $consultaPasajero = "UPDATE pasajero SET activo = 0 WHERE pdocumento = " . $this->getNrodoc();

        if ($database->iniciar()) {
            if ($database->ejecutar($consultaPasajero)) {
                // También desactivamos a la persona
                $consultaPersona = "UPDATE persona SET activo = 0 WHERE documento = " . $this->getNrodoc();
                $database->ejecutar($consultaPersona); // no importa si falla, es decorativo

                $respuesta = true;
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
        $consulta = "SELECT * FROM pasajero 
                    INNER JOIN persona ON persona.documento = pasajero.pdocumento 
                    WHERE pasajero.activo = 1";

        if ($condicion != "") {
            $consulta .= " AND $condicion ";
        }

        $consulta .= " ORDER BY apellido";

        if ($database->iniciar()) {
            if ($database->ejecutar($consulta)) {
                $arregloPasajero = [];
                
                while ($pasajeroEncontrado = $database->registro()) {
                    $pasajero = new Pasajero;
                    $pasajero->cargar(
                        $pasajeroEncontrado["nombre"],
                        $pasajeroEncontrado["apellido"],
                        $pasajeroEncontrado["pdocumento"],
                        $pasajeroEncontrado["telefono"],
                        $pasajeroEncontrado["idviaje"],
                        $pasajeroEncontrado["activo"]
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

        $estado = ($this->getActivo() == 1) ? "Activo" : "Inactivo";

    
        $cadena  = parent::__toString();
        $cadena .= "ID VIAJE: ".$this->getIdViaje()."\n";
        $cadena .= "Estado: $estado\n";
        

        return $cadena;
    }   
}
?>