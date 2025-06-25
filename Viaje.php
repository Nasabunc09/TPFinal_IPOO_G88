<?php

class Viaje {

    private $idViaje;
    private $vOrigen;
    private $vDestino;
    private $cantMaxPasajeros;
    private $objEmpresa;
    private $colPasajeros;
    private $objResponsableV;
    private $costo;
    private $mensajeOperacion;
    

    public function __construct(){

        
        $this->vOrigen = '';
        $this->vDestino = '';
        $this->cantMaxPasajeros = 0;
        $this->colPasajeros = [];
        $this->costo = 0;
    }

    public function getIdViaje() {
        return $this->idViaje;
    }

    public function getVOrigen() {
        return $this->vOrigen;
    }

    public function getVDestino() {
        return $this->vDestino;
    }

    public function getCantMaxPasajeros() {
        return $this->cantMaxPasajeros;
    }

    public function getObjEmpresa() {
		return $this->objEmpresa;
	}

    public function getColPasajeros() {
        return $this->colPasajeros;
    }

    public function getObjResponsableV() {
        return $this->objResponsableV;
    }

    public function getCosto() {
		return $this->costo;
	}

    public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}
    
    public function setIdViaje($idViaje) {
        $this->idViaje = $idViaje;
    }

    public function setVOrigen($vOrigen) {
        $this->vOrigen = $vOrigen;
    }

    public function setVDestino($vDestino) {
        $this->vDestino = $vDestino;
    }

    public function setCantMaxPasajeros($cantMaxPasajeros) {
        $this->cantMaxPasajeros = $cantMaxPasajeros;
    }

    public function setObjEmpresa($objEmpresa) {
        $this->objEmpresa = $objEmpresa;
    }

    public function setColPasajeros($colPasajeros) {
        $this->colPasajeros = $colPasajeros;
    }

    public function setObjResponsableV($objResponsableV) {
        $this->objResponsableV = $objResponsableV;
    }

	public function setCosto($value) {
		$this->costo = $value;
	}

    public function setMensajeOperacion($mensaje){
        $this->mensajeOperacion = $mensaje;
    }

    public function cargar(String $vOrigen, String $vDestino, int $cantMaxPasajeros, array $colPasajeros, ResponsableV $objResponsableV, float $costo, Empresa $objEmpresa){

        $this->setVOrigen($vOrigen);
        $this->setVDestino($vDestino);
        $this->setCantMaxPasajeros($cantMaxPasajeros);
        $this->setColPasajeros($colPasajeros);
        $this->setObjResponsableV($objResponsableV);
        $this->setCosto($costo);
        $this->setObjEmpresa($objEmpresa);
    }

    public function insertar()
    {
        $baseDatos = new BaseDatos;
        $respuesta = false;
        $consultaInsertar = "INSERT INTO viaje (vorigen,vdestino,vcantmaxpasajeros,rnumeroempleado,vimporte,idempresa) VALUES 
                            ('" . $this->getVOrigen() . "',
                             '" . $this->getVDestino() . "',
                              " . $this->getCantMaxPasajeros() . ",
                              " . $this->getObjResponsableV()->getRNumEmpleado() . ",
                              " . $this->getCosto() . ",
                              " . $this->getObjEmpresa()->getIdEmpresa() . "
                            )";

        if ($baseDatos->iniciar()) {

            if ($id = $baseDatos->devuelveIDInsercion($consultaInsertar)) {
                $this->setIdViaje($id);
                $respuesta =  true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    public function buscar(int $idviaje)
    {
        $baseDatos = new BaseDatos;
        $consulta = "SELECT * FROM viaje WHERE idviaje=" . $idviaje;
        $respuesta = false;
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                if ($viaje = $baseDatos->registro()) {
                    $empresa = new Empresa;
                    $empresa->buscar($viaje['idempresa']);
                    $empleado = new ResponsableV;
                    $empleado->buscar($viaje['rnumeroempleado']);
                    $this->cargar(
                        $viaje['vorigen'],
                        $viaje['vdestino'],
                        $viaje['vcantmaxpasajeros'],
                        [],
                        $empleado,
                        $viaje['vimporte'],
                        $empresa
                    );

                    $this->setIdViaje($viaje['idviaje']);

                    $respuesta = true;
                }
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    public function modificar(){

        $baseDatos = new BaseDatos;
        // Preparar la consulta asegurándonos de que todos los valores estén correctamente concatenados
        $consulta = "UPDATE viaje SET 
                     vorigen = '" . $this->getVOrigen() . "',
                     vdestino = '" . $this->getVDestino() . "',
                     vcantmaxpasajeros = " . $this->getCantMaxPasajeros() . ",
                     idempresa = " . $this->getObjEmpresa()->getIdEmpresa() . ",
                     rnumeroempleado = " . $this->getObjResponsableV()->getRNumEmpleado() . ",
                     vimporte = " . $this->getCosto() . "
                     WHERE idviaje = " . $this->getIdViaje();

        $respuesta = false;
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setMensajeOperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeOperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    public function eliminar()
    {
        $baseDatos = new BaseDatos;
        $consulta = "DELETE FROM viaje WHERE idviaje = " . $this->getIdViaje();
        $respuesta = false;
        if ($baseDatos->iniciar()) {
            if ($baseDatos->ejecutar($consulta)) {
                $respuesta = true;
            } else {
                $this->setMensajeoperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeoperacion($baseDatos->getError());
        }
        return $respuesta;
    }

    
// Implementar método listar en orm de viaje  //
    public function listar($condicion = "")
    {
        $arrayViaje = null;
        $baseDatos = new BaseDatos;
        $consulta = "SELECT * FROM viaje INNER JOIN empresa ON empresa.idempresa = viaje.idempresa INNER JOIN responsable ON responsable.rnumeroempleado = viaje.rnumeroempleado INNER JOIN persona ON persona.documento = responsable.rdocumento ";
        if ($condicion != "") {
            $consulta .= "WHERE $condicion ";
        }
        $consulta .= "ORDER BY idviaje";

        if ($baseDatos->iniciar()) {

            if ($baseDatos->ejecutar($consulta)) {
                $arrayViaje = [];
                while ($viajeEncontrado = $baseDatos->registro()) {
                    $responsable = new ResponsableV;
                    $responsable->cargar(
                        $viajeEncontrado["nombre"],
                        $viajeEncontrado["apellido"],
                        $viajeEncontrado["rdocumento"],
                        $viajeEncontrado["rnumerolicencia"]
                    );
                    $responsable->setRNumEmpleado($viajeEncontrado["rnumeroempleado"]);
                    $empresa = new Empresa;
                    $empresa->cargar(
                        $viajeEncontrado["enombre"],
                        $viajeEncontrado["edireccion"]
                    );
                    $empresa->setIdEmpresa($viajeEncontrado["idempresa"]);
                    $viaje = new Viaje;
                    $viaje->cargar(
                        $viajeEncontrado["vorigen"],
                        $viajeEncontrado["vdestino"],
                        $viajeEncontrado["vcantmaxpasajeros"],
                        [],
                        $responsable,
                        $viajeEncontrado["vimporte"],
                        $empresa

                    );
                    $viaje->setIdViaje($viajeEncontrado["idviaje"]);
                    array_push($arrayViaje, $viaje);
                }
            } else {
                $this->setMensajeoperacion($baseDatos->getError());
            }
        } else {
            $this->setMensajeoperacion($baseDatos->getError());
        }

        return $arrayViaje;
    }

	
    private function mostrarCadena($col){

        $cadena = "";
        foreach($col as $elemento){
            $cadena = $cadena."".$elemento."\n";

        }

        $cadena;
    }

    public function __toString(){

        $cadena = "ID VIAJE: ".$this->getIdViaje() ."\n".
        $cadena = "ORIGEN: ".$this->getVOrigen() ."\n".
        $cadena = "DESTINO: ".$this->getVDestino() ."\n".
        $cadena = "COSTO DEL VIAJE: " . $this->getCosto() . "\n" .
        $cadena = "CANT. PASAJEROS: ".$this->getCantMaxPasajeros() ."\n". 
        $cadena = "PASAJEROS: ".$this->mostrarCadena($this->getColPasajeros()) ."\n".
        $cadena = "RESPONSABLE: ".$this->getObjResponsableV();

        return $cadena;
    }

    public function buscarPasajero(int $numeroDeDocumento)
    {
        $colPasajeros = $this->getColPasajeros();
        $pasajeroEncontrado = null;
        $encontrado = false;
        $i = 0;
        while (!$encontrado && count($this->getColPasajeros()) > $i) {
            if ($colPasajeros[$i]->getNumeroDeDocumento() == $numeroDeDocumento) {
                $encontrado = true;
                $pasajeroEncontrado = $colPasajeros[$i];
            }
            $i++;
        }
        return $pasajeroEncontrado;
    }

    public function agregarPasajero(Pasajero $pasajero)
    {
        $colPasajeros = $this->getColPasajeros();
        $pasajeroAgregado = false;
        if ($this->hayPasajesDisponibles()) {
            $pasajeroRepetido = false;
            $i = 0;
            while (!$pasajeroRepetido && $i < count($colPasajeros)) {
                if ($colPasajeros[$i]->getDocumento() == $pasajero->getNroDoc()) {
                    $pasajeroRepetido = true;
                }
                $i++;
            }
            if (!$pasajeroRepetido) {
                array_push($colPasajeros, $pasajero);
                $this->setColPasajeros($colPasajeros);
                $pasajeroAgregado = true;
            }
        }
        return $pasajeroAgregado;
    }

   
 
    public function hayPasajesDisponibles(){

        $colPasajeros = $this->getColPasajeros();
        $hayPasaje = count($colPasajeros) < $this->getCantMaxPasajeros();
        return $hayPasaje;
    }
    
}
?>