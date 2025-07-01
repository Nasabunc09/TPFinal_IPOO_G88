<?php

class ResponsableV extends Persona{

    private $rNumEmpleado;
    private $rNumLicencia;
	private $mensajeOperacion;
	private $activo;
    

    public function __construct(){
        
        parent::__construct();
        $this->rNumEmpleado = 0;
        $this->rNumLicencia = 0;
		$this->activo = 1;
        
    }

    public function getRNumEmpleado(){
        return $this->rNumEmpleado;
    }

    public function getRNumLicencia(){
        return $this->rNumLicencia;
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

    public function setRNumEmpleado($rNumEmpleado){
        $this->rNumEmpleado = $rNumEmpleado;
    }

    public function setRNumLicencia($rNumLicencia){
        $this->rNumLicencia = $rNumLicencia;
    }

	public function setMensajeOperacion($mensaje){
        $this->mensajeOperacion = $mensaje;
    }
   
    public function cargar($documento,$nombre, $apellido,$telefono,$rNumLicencia = 0,$rNumEmpleado = 0,$activo=1){	
	    
        parent::cargar($documento,$nombre, $apellido,$telefono);
		$this->setRNumLicencia($rNumLicencia);
		$this->setRNumEmpleado($rNumEmpleado);
		$this->setActivo($activo);
		
    }

    /**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function Buscar($rNumEmpleado){
		$baseDatos = new BaseDatos;
		$consulta = "SELECT * FROM responsable INNER JOIN persona ON responsable.rdocumento = persona.documento WHERE rnumeroempleado=" . $rNumEmpleado;
        $responsable = false;
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consulta)){
				if ($empleado = $baseDatos->registro()) {
                    $this->cargar(
						$empleado["rdocumento"], 
                        $empleado["nombre"], 
                        $empleado["apellido"], 
                        $empleado["telefono"], 
                        $empleado["rnumerolicencia"],
						$empleado["rnumeroempleado"],
						$empleado["activo"]
                    );
					
					$responsable = true;
				}				
			
		 	}else{
		 			$this->setMensajeOperacion($baseDatos->getError());
		 		
			}
		}else{
		 		$this->setMensajeOperacion($baseDatos->getError());
		 	
		}

		return $responsable;
	}	
    

	public function listar($condicion = ""){

	    $arregloResponsable = null;
		$baseDatos = new BaseDatos;
		$consulta = "SELECT * FROM responsable 
					 INNER JOIN persona ON persona.documento = responsable.rdocumento
					 WHERE responsable.activo = 1";
		if ($condicion!=""){
		    $consulta .= "WHERE $condicion";
		}
		$consulta .= " ORDER BY rdocumento";
		
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consulta)){				
				$arregloResponsable= array();
				while ($responsableEncontrado = $baseDatos->registro()){
                    $responsable = new ResponsableV;
					
                    $responsable->cargar(
						$responsableEncontrado["rdocumento"],
                        $responsableEncontrado["nombre"],
                        $responsableEncontrado["apellido"],
                        $responsableEncontrado["telefono"],
                        $responsableEncontrado["rnumerolicencia"],
						$responsableEncontrado["rnumeroempleado"],
						$responsableEncontrado["activo"]
                    );
                    
                    array_push($arregloResponsable, $responsable);
                }
				
			
		 	}else{
		 			$this->setMensajeOperacion($baseDatos->getError());
		 		
			}
		}else{
		 		$this->setMensajeOperacion($baseDatos->getError());
		 	
		}	
		 return $arregloResponsable;
	}


	
	public function insertar() {
    $baseDatos = new BaseDatos;
    $responsable = false;

    // Paso 1: insertar en persona si no existe
    if (!parent::buscar($this->getNrodoc())) {
        if (!parent::insertar()) {
            $this->setMensajeOperacion("Error al insertar persona: " . $this->getMensajeOperacion());
            return false;
        }
    }

    // Paso 2: insertar en responsable
    $consulta = "INSERT INTO responsable (rnumeroempleado, rnumerolicencia, rdocumento, activo)
				 VALUES (" . $this->getRNumEmpleado() . ",
				" . $this->getRNumLicencia() . ",
				" . $this->getNrodoc() . ",
				1)";

    if ($baseDatos->Iniciar()) {
        if ($baseDatos->Ejecutar($consulta)) {
            $responsable = true;
        } else {
            $this->setMensajeOperacion("Error al insertar responsable: " . $baseDatos->getError());
        }
    } else {
        $this->setMensajeOperacion($baseDatos->getError());
    }

    return $responsable;
}
	
	
	
	public function modificar(){

	    $responsable = false; 
	    $baseDatos = new BaseDatos;
		if (parent::modificar()){
            $consulta = "UPDATE responsable SET rnumerolicencia = " . $this->getRNumLicencia() . 
            			" WHERE rnumeroempleado = ". $this->getRNumEmpleado();

		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consulta)){
			    $responsable =  true;
			}else{
				$this->setMensajeOperacion($baseDatos->getError());
				
			}
		}else{
				$this->setMensajeOperacion($baseDatos->getError());
			
		}
	    }
		return $responsable;
	}
	
	public function eliminar(){

		$baseDatos = new BaseDatos;
		$respuesta = false;
		if($baseDatos->Iniciar()){
				 // Borrado lógico → solo actualiza el campo activo
        		$consultaBorra = "UPDATE responsable SET activo = 0 WHERE rnumeroempleado = " . $this->getRNumEmpleado();

				if ($baseDatos->ejecutar($consultaBorra)) {
				// Borrado lógico de la persona también 
				$consultaPersona = "UPDATE persona SET activo = 0 WHERE documento = " . $this->getNrodoc();
				$baseDatos->ejecutar($consultaPersona);

				    $respuesta=  true;
				}else{
						$this->setMensajeOperacion($baseDatos->getError());
					
				}
		}else{
				$this->setMensajeOperacion($baseDatos->getError());
			
		}
		return $respuesta; 
}

public function __toString() {

    $estado = ($this->getActivo() == 1) ? "Activo" : "Inactivo";

    $cadena  = parent::__toString();
    $cadena .= "Num. Empleado: " . $this->getRNumEmpleado() . "\n";
    $cadena .= "Num. Licencia: " . $this->getRNumLicencia() . "\n";
    $cadena .= "Estado: $estado\n";

    return $cadena;
}

}
?>