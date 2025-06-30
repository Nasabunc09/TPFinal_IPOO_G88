<?php

class ResponsableV extends Persona{

    private $rNumEmpleado;
    private $rNumLicencia;
	private $mensajeOperacion;
    

    public function __construct(){
        
        parent::__construct();
        $this->rNumEmpleado = 0;
        $this->rNumLicencia = 0;
        
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

    public function setRNumEmpleado($rNumEmpleado){
        $this->rNumEmpleado = $rNumEmpleado;
    }

    public function setRNumLicencia($rNumLicencia){
        $this->rNumLicencia = $rNumLicencia;
    }

	public function setMensajeOperacion($mensaje){
        $this->mensajeOperacion = $mensaje;
    }
   
    public function cargar($nombre, $apellido, $documento, $telefono,$rNumEmpleado = 0,$rNumLicencia = 0){	
	    
        parent::cargar($nombre, $apellido, $documento,$telefono);
		$this->setRNumEmpleado($rNumEmpleado);
		$this->setRNumLicencia($rNumLicencia);
		
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
                        $empleado["nombre"], 
                        $empleado["apellido"], 
                        $empleado["documento"], 
                        $empleado["rnumerolicencia"]
                    );
					// Como la función cargar no recibe el número de empleado como parametro, lo seteamos aparte
                    $this->setRNumEmpleado($empleado["rnumeroempleado"]);
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
		$consulta = "SELECT * FROM responsable INNER JOIN persona ON persona.documento = responsable.rdocumento ";
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
                        $responsableEncontrado["nombre"],
                        $responsableEncontrado["apellido"],
                        $responsableEncontrado["rdocumento"],
                        $responsableEncontrado["rnumerolicencia"]
                    );
                    $responsable->setRNumEmpleado($responsableEncontrado["rnumeroempleado"]);
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


	
	public function insertar(){

		$baseDatos = new BaseDatos;
		$responsable = false;
		$consulta = "INSERT INTO responsable(rnumerolicencia, rdocumento) VALUES ( /**numero empleado */
            				" . $this->getRNumLicencia() . ",
            				'" . $this->getNrodoc() . "')";
		
		if($baseDatos->iniciar()){
			if ($numeroEmpleado = $baseDatos->devuelveIDInsercion($consulta)){
                    $this->setRNumEmpleado($numeroEmpleado);
			    $responsable=  true;

			}else{
					$this->setMensajeOperacion($baseDatos->getError());
					
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
				$consultaBorra = "DELETE FROM responsable WHERE rnumeroempleado = " . $this->getRNumEmpleado();
				if($baseDatos->ejecutar($consultaBorra)){
					// Si pudimos borrar el responsable, borramos la persona
                    parent::eliminar();
				    $respuesta=  true;
				}else{
						$this->setMensajeOperacion($baseDatos->getError());
					
				}
		}else{
				$this->setMensajeOperacion($baseDatos->getError());
			
		}
		return $respuesta; 
	}

    public function __toString(){
        
		$cadena  = "----------\n";
        $cadena  = parent::__toString();
        $cadena .= "Num. Empleado: ".$this->getRNumEmpleado()."\n".
        $cadena .= "Num. Licencia: ". $this->getRNumLicencia()."\n";
       
        return $cadena;
    }

}
?>