<?php

class Persona{

   
	private $nrodoc;
	private $nombre;
	private $apellido;
	private $telefono;
	private $mensajeOperacion;


	public function __construct(){
	    
		$this->nrodoc = "";
		$this->nombre = "";
		$this->apellido = "";
		$this->telefono = "";
	}

	public function getNrodoc(){
		return $this->nrodoc;
	}

	public function getNombre(){
		return $this->nombre ;
	}

	public function getApellido(){
		return $this->apellido ;
	}

	public function getTelefono(){
		return $this->telefono ;
	}

	public function getMensajeOperacion(){
		return $this->mensajeOperacion;
	}

    public function setNrodoc($nrodoc){
		$this->nrodoc = $nrodoc;
	}

	public function setNombre($nombre){
		$this->nombre = $nombre;
	}

	public function setApellido($apellido){
		$this->apellido = $apellido;
	}

	public function setTelefono($telefono){
		$this->telefono = $telefono;
	}

	public function setMensajeOperacion($mensajeOperacion){
		$this->mensajeOperacion = $mensajeOperacion;
	}
	
	public function cargar($nrodoc,$nombre,$apellido,$telefono){	
	    
		$this->setNrodoc($nrodoc);
		$this->setNombre($nombre);
		$this->setApellido($apellido);
		$this->setTelefono($telefono);
    }

	/**
	 * Recupera los datos de una persona por dni
	 * @param int $dni
	 * @return true en caso de encontrar los datos, false en caso contrario 
	 */		
    public function buscar($nrodoc){
		$baseDatos = new BaseDatos;
		$consultaPersona = "SELECT * FROM persona WHERE documento = '". $nrodoc ."'";
		$respuesta = false;
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaPersona)){
				if($persona=$baseDatos->registro()){
				    $this->cargar(
						$persona['documento'],
                        $persona['nombre'],
                        $persona['apellido'],
                        $persona['telefono']
					);
					$respuesta = true;
				}				
			
		 	}else{
		 			$this->setMensajeOperacion($baseDatos->getError());
		 		
			}
		}else{
		 		$this->setMensajeOperacion($baseDatos->getError());
		 	
		}

		return $respuesta;
	}	
    

	public function listar($condicion=""){

	    $arregloPersona = null;
		$baseDatos = new BaseDatos;
		$consultaPersonas="SELECT * FROM persona ";
		if ($condicion!=""){
		    $consultaPersonas .= "WHERE $condicion";
		}
		$consultaPersonas.= " ORDER BY apellido ";
		
		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaPersonas)){				
				$arregloPersona= array();
				while($personaEncontrada = $baseDatos->registro()){
				    $persona = new self;
                    $persona->cargar(
						$personaEncontrada["documento"],
                        $personaEncontrada["nombre"],
                        $personaEncontrada["apellido"],
						$personaEncontrada["telefono"],
                    );
				
					array_push($arregloPersona,$persona);
		        }
			
		 	}else{
		 		$this->setMensajeOperacion($baseDatos->getError());
		 		
			}
		}else{
		 		$this->setMensajeOperacion($baseDatos->getError());
		 	
		}	
		 return $arregloPersona;
	}	


	
	public function insertar(){

		$baseDatos = new BaseDatos();
		$persona = false;
		$consultaInsertar = "INSERT INTO persona(documento,nombre, apellido,telefono) 
							VALUES ('" . $this->getNrodoc() . "',
							        '" . $this->getNombre() . "',
									'" . $this->getApellido() . "',
									'" . $this->getTelefono() . "')";
		
		if($baseDatos->Iniciar()){

			if($baseDatos->Ejecutar($consultaInsertar)){
                
			    $persona=  true;

			}else{
					$this->setMensajeOperacion($baseDatos->getError());
					
			}

		} else {
				$this->setMensajeOperacion($baseDatos->getError());
			
		}
		return $persona;
	}
	
	
	
	public function modificar(){

	    $respuesta = false; 
	    $baseDatos = new BaseDatos();
		$consultaModifica = "UPDATE persona SET 
							nombre='" . $this->getNombre() . "',
							apellido='" . $this->getApellido() . "',
							telefono='" . $this->getTelefono() . "'
							WHERE documento=" . (int)$this->getNrodoc();

		if($baseDatos->iniciar()){
			if($baseDatos->ejecutar($consultaModifica)){
			    $respuesta =  true;
			}else{
				$this->setMensajeOperacion($baseDatos->getError());
				
			}
		}else{
				$this->setMensajeOperacion($baseDatos->getError());
			
		}
		return $respuesta;
	}
	
	public function eliminar(){

		$baseDatos = new BaseDatos();
		$respuesta = false;
		if($baseDatos->iniciar()){
				$consultaBorra = "DELETE FROM persona WHERE documento = ".$this->getNrodoc();
				if($baseDatos->ejecutar($consultaBorra)){
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

		$cadena = "DNI: ".$this->getNrodoc()."\n".
	    $cadena = "NOMBRE: ".$this->getNombre(). "\n". 
		$cadena = "APELLIDO: ".$this->getApellido()."\n". 
		$cadena = "TELEFONO: ".$this->getTelefono()."\n";

		return $cadena;
			
	}
}
?>
