<?php

class Empresa{

    
    private $idEmpresa; 
    private $eNombre;
    private $eDireccion;
    //private $colViajes;
    private $mensajeOperacion;


    
    public function __construct(){

        $this->idEmpresa = " ";
        $this->eNombre = " ";
        $this->eDireccion = " ";
        //$this->colViajes= [];
    }

    public function getIdEmpresa(){
        return $this->idEmpresa;
    }

    public function getENombre(){
        return $this->eNombre;
    }

    public function getEDireccion(){
        return $this->eDireccion;
    }

    public function getMensajeOperacion(){
        return $this->mensajeOperacion;
    }

    
    public function setIdEmpresa($idEmpresa){
        $this->idEmpresa = $idEmpresa;
    }

    public function setENombre($eNombre){
        $this->eNombre = $eNombre;
    }

    public function setEDireccion($eDireccion){
        $this->eDireccion = $eDireccion;
    }

    public function setMensajeOperacion($mensajeOperacion){
        $this->mensajeOperacion = $mensajeOperacion;
    }

      
    public function cargar($eNombre,$eDireccion){

        $this->setENombre($eNombre);
        $this->setEDireccion($eDireccion);


    }

    /**Recupera los datos de una empresa por id Empresa
     * @param int $idEmpresa
     * @return true en caso de encontrar los datos, false en caso contrario
     */

    public function buscar ($idEmpresa){

        $baseDatos = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa WHERE idempresa=".$idEmpresa;
        $respuesta = false;

        if($baseDatos->iniciar()){
            if($baseDatos->ejecutar($consultaEmpresa)){
                if($empresa = $baseDatos->registro()){

                    $this->cargar($empresa['enombre'],$empresa['edireccion']);
                    $this->setIdEmpresa($empresa['idempresa']);
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

      /**
     * recupera una lista de empresa de la base de datos
     * @param string
     * @return array
     */

    public function listar ($condicion=""){

        $arregloEmpresa = null;
        $baseDatos = new BaseDatos();
        $consultaEmpresa = "SELECT * FROM empresa ";

        if($condicion != ""){
            $consultaEmpresa .= "WHERE $condicion ";
        }
        
        $consultaEmpresa .= "ORDER BY enombre";
        if($baseDatos->iniciar()){

            if($baseDatos->ejecutar($consultaEmpresa)){

                $arregloEmpresa = array();

                while ($empresaEncontrada = $baseDatos->registro()){
                    $empresa = new Empresa;
                    $empresa->cargar(
                        $empresaEncontrada["enombre"],
                        $empresaEncontrada["edireccion"]
                    );
                    $empresa->setIdEmpresa($empresaEncontrada["idempresa"]);
                    array_push($arregloEmpresa, $empresa);
                }

            }else{
                $this->setMensajeOperacion($baseDatos->getError());
            }

        }else{
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $arregloEmpresa;

    }

    /**
     * Inserta el objeto de empresa actual en la base de datos
     * @return boolean
     */
 
     public function insertar(){

        $baseDatos = new BaseDatos();
        $respuesta = false;
        $consultaInsertar = "INSERT INTO empresa (enombre, edireccion)
                             VALUES ('".$this->getENombre()."',
                                     '".$this->getEDireccion()."')";

        if($baseDatos->iniciar()){

            if($id = $baseDatos->devuelveIDInsercion($consultaInsertar)){
                $this->setIdEmpresa($id);
                $respuesta = true;

            }else{
                $this->setMensajeOperacion($baseDatos->getError());
            }

        }else{
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
     }

     /**
     * modifica la información de la empresa en la base de datos
     * @return boolean
     */

     public function modificar(){

        $respuesta = false;
        $baseDatos = new BaseDatos();
        $consultaModificar = "UPDATE empresa SET enombre='".$this->getENombre()."',edireccion='".$this->getEDireccion()."'WHERE idempresa=".$this->getIdEmpresa();

        if($baseDatos->iniciar()){

            if($baseDatos->ejecutar($consultaModificar)){
                $respuesta = true;
                
            }else{
                $this->setMensajeOperacion($baseDatos->getError());
            }

        }else{
            $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta;
     }

     /**
     * elimina el registro de la empresa de la base de datos
     * @return boolean
     */

     public function eliminar (){

        $respuesta = false;
        $baseDatos = new BaseDatos();

        if($baseDatos->iniciar()){
            $consultaEliminar="DELETE FROM empresa WHERE idempresa=".$this->getIdEmpresa();
            if($baseDatos->ejecutar($consultaEliminar)){
                $respuesta = true;

            }else{
                $this->setMensajeOperacion($baseDatos->getError());
            }

        }else{
             $this->setMensajeOperacion($baseDatos->getError());
        }

        return $respuesta; 
    }
     

     /**
     * retorna una coleccion de viajes en una cadena
     * @return string
     */

    /*public function mostrarViajes(){

        $viajes=$this->getColViajes();
        $cadena="";

        for($i=0; $i<count($viajes);$i++){
            $cadena.="VIAJE N°". $i+1 ."\n".$viajes[$i]."\n \n";
        }

        return $cadena;
    }*/

    
    public function __toString() {

        $cadena  = "-----EMPRESA-----\n";
        $cadena  = "ID: " . $this->getIdEmpresa() . "\n";
        $cadena .= "NOMBRE: " . $this->getENombre() . "\n";
        $cadena .= "DIRECCION: " . $this->getEDireccion() . "\n";
    
    return $cadena;
}

}
?>