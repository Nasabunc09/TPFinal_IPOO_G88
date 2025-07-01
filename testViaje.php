<?php

include_once "BaseDatos.php";
include_once 'Persona.php';
include_once 'Empresa.php';
include_once 'Viaje.php';
include_once 'ResponsableV.php';
include_once 'Pasajero.php';


class TestViaje {

public function menu(){


    do{
        
        echo "\n ---------------------------- \n " .
            "Elegir una opción \n " .
             "   ---  EMPRESA --\n " .
            "   1. Cargar empresa \n " .
            "   2. Modificar empresa \n " .
            "   3. Eliminar empresa \n " .
            "   4. Mostrar empresas \n " .
	     "   ---  VIAJE --\n " .
            "   5. Cargar viaje \n " .
            "   6. Modificar viaje \n " .
            "   7. Eliminar viaje \n " .
            "   8. Mostrar viajes \n " .
             "   ---  RESPONSABLE --\n " .
            "   9.  Cargar responsable \n " .
            "   10. Modificar responsable \n " .
            "   11. Eliminar responsable \n " .
            "   12. Mostrar responsables \n " .
             "   ---  PASAJERO --\n " .
            "   13. Cargar pasajero  \n " .
            "   14. Modificar pasajero \n " .
            "   15. Eliminar pasajero \n " .
            "   16. Mostrar pasajeros \n " .
           /*  "   ---  PERSONA --\n " .
            "   17. Cargar persona \n " .
            "   18. Modificar persona \n " .
            "   19. Eliminar persona \n " .
            "   20. Mostrar personas \n " .*/
            "   0. SALIR \n";

        echo "\nOpción ingresada: ";
        $opcion = trim(fgets(STDIN));
        switch ($opcion){

            case "1":
                TestViaje::menuCargarEmpresa();
            break;

            case "2":
                TestViaje::menuModificarEmpresa();
            break;

            case "3":
                TestViaje::menuEliminarEmpresa();
            break;

            case "4":
                TestViaje::mostrarEmpresas();
            break;

            case "5":
                TestViaje::menuCargarViaje();
            break;

            case "6":
               TestViaje::menuModificarViaje();
            break;

            case "7":
                TestViaje::menuEliminarViaje();
            break;

            case "8":
                TestViaje::mostrarViajes();
            break;

            case "9":
                TestViaje::menuCargarResponsable();
            break;

            case "10":
                TestViaje::menuModificarResponsable();
            break;

            case "11":
                TestViaje::menuEliminarResponsable();
            break;

            case "12":
                TestViaje::mostrarResponsables();
            break;

            case "13":
                TestViaje::menuCargarPasajero();
            break;

            case "14":
                TestViaje::menuModificarPasajero();
            break;

            case "15":
                TestViaje::menuEliminarPasajero();
            break;

            case "16":
                TestViaje::mostrarPasajeros();
            break;

            case "17":
                //TestViaje::menuCargarPersona();
            break;

            case "18":
                //TestViaje::menuModificarPersona();
            break;

            case "19":
                //TestViaje::menuEliminarPersona();
            break;

            case "20":
                //TestViaje::mostrarPersonas();
            break;

            case "0":
                echo " Saliendo del menu...\n";
            break;

            default:
                echo "Opción invalida \n";
            break;
        }
    }

    while($opcion != "0");
}



public static function menuCargarEmpresa(){

    echo " Cargar Empresa "."\n";
    echo "Nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Dirección: ";
    $direccion = trim(fgets(STDIN));
    if (TestViaje::agregarEmpresa($nombre,$direccion)){
        echo "Empresa cargada";
    }else{
        echo "Hubo un error, empresa no cargada";
    }
}

public static function agregarEmpresa($nombre, $direccion){

    $empresa = new Empresa();
    $empresa->cargar($nombre, $direccion);
    return $empresa->insertar();
}

public static function buscarEmpresa($id){

    $rta = null;
    $empresa = new Empresa();
    if(TestViaje::es_numerico($id) == TRUE){
        if ($empresa->buscar($id)) {
            $rta = $empresa;
        }
    }else{
        echo "El id de Empresa debe ser un numero mayor a cero";
    }
    return $rta;
}

public static function mostrarEmpresas(){

    $empresaObj = new Empresa();
    $empresas = $empresaObj->listar();

    foreach ($empresas as $unaEmpresa) {
        echo "\n$unaEmpresa";
    }
}

public static function menuModificarEmpresa(){

    echo " Modificar Empresa "."\n";
    echo "ID de empresa a modificar: ";
    $idEmpresa = trim(fgets(STDIN));
    echo "Nuevo Nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Nueva Dirección: ";
    $direccion = trim(fgets(STDIN));
    if (TestViaje::modificarEmpresa($idEmpresa,$nombre,$direccion)){
        echo " Empresa modificada \n";
    }else{ 
        echo " Hubo un error, empresa no modificada \n";
    }
}

public static function modificarEmpresa($id, $nombre, $direccion){

    TestViaje::mostrarEmpresas();
    $empresa = new Empresa();
    $respuesta = false;
    if (TestViaje::es_numerico($id)) {
        if ($empresa->buscar($id)) {
            if ($nombre !== "") {
                $empresa->setENombre($nombre);
            }
            if ($direccion !== "") {
                $empresa->setEDireccion($direccion);
            }
            $respuesta = $empresa->modificar();
        }else{
            echo "la empresa no existe";
        }
    }else{
        echo "El id de Empresa debe ser un numero mayor a cero";
    }
    return $respuesta;
}

public static function menuEliminarEmpresa(){

    TestViaje::mostrarEmpresas();
    echo "\n Eliminar Empresa \n";
    echo " ID de empresa a eliminar: ";
    $idEmpresa = trim(fgets(STDIN));
    if(TestViaje::eliminarEmpresa($idEmpresa)){
        echo " Empresa eliminada \n";
    }else{
        echo " Hubo un error, empresa no eliminada \n";
    }
}

public static function eliminarEmpresa($id){

    $empresa = new Empresa();
    $respuesta = false ;
    if (TestViaje::es_numerico($id)){
        if ($empresa->buscar($id)) {
            $respuesta = $empresa->eliminar();
        }else{
            echo "La empresa no existe";
        }
    }else{
        echo "El id de Empresa debe ser un numero mayor a cero";
    }
    return $respuesta;
}


//VIAJE 

public static function menuCargarViaje(){
    echo "\nCargar viaje \n";
    echo "Origen: ";
    $vOrigen = trim(fgets(STDIN));
    echo "Destino: ";
    $vDestino = trim(fgets(STDIN));
    echo "Cant. pasajeros: ";
    $cantPasajeros = trim(fgets(STDIN));
    echo "Num. Responsable: ";
    $rnumeroEmpleado = trim(fgets(STDIN));
    echo "Importe: ";
    $importe = trim(fgets(STDIN));
    echo "Ingresa el id de la empresa: ";
    $idEmpresa = trim(fgets(STDIN));
    if(TestViaje::agregarViaje($vOrigen,$vDestino,$cantPasajeros,[],$rnumeroEmpleado,$importe,$idEmpresa)){
            echo " Viaje cargado \n";
    }else{
        echo " Ha ocurrido un error \n";
    }
}
public static function agregarViaje($origen,$destino, $cantMaxPasajeros, $colPasajeros, $nroResponsable, $costo, $idEmpresa){

    $viaje = new Viaje();
    $respuesta = false;
    if (TestViaje::es_numerico($nroResponsable)){
        $objResponsableV = new ResponsableV;
        $objResponsableV->buscar($nroResponsable);
        if (TestViaje::es_numerico($idEmpresa)) {
            $objEmpresa = new Empresa;
            $objEmpresa->buscar($idEmpresa);
            if (!TestViaje::es_numerico($cantMaxPasajeros)) {
                $cantMaxPasajeros = 0;
            }
            if ($costo < 0 ) {
                $costo = 0;
            }
            $viaje->cargar($origen,$destino, $cantMaxPasajeros, $colPasajeros, $objResponsableV, $costo,$objEmpresa);
            $respuesta = $viaje->insertar();
        }else{
            echo "El id de Empresa debe ser un numero mayor a cero";
        }
    }else{
        echo "El numero de empleado responsable del viaje tiene que ser mayor a cero";
    }
    return $respuesta;
}

public static function menuModificarViaje(){

    TestViaje::mostrarViajes();
    echo " Modificar Viaje \n";
    echo "Id del viaje a modificar: ";
    $idViaje = trim(fgets(STDIN));
    echo "Nuevo Origen: ";
    $vOrigen = trim(fgets(STDIN));
    echo "Nuevo Destino: ";
    $vDestino = trim(fgets(STDIN));
    echo "Nueva Cant. pasajeros: ";
    $cantPasajeros = trim(fgets(STDIN));
    echo "Nuevo Id empresa: ";
    $idEmpresa = trim(fgets(STDIN));
    echo "Nuevo Num. Responsable: ";
    $rnumeroEmpleado = trim(fgets(STDIN));
    echo "Importe: ";
    $importe = trim(fgets(STDIN));
    if(TestViaje::modificarViaje($idViaje,$vOrigen,$vDestino,$cantPasajeros,[],$rnumeroEmpleado,$importe,$idEmpresa)){
        echo " Viaje modificado \n";
    }else{
        echo " Ha ocurrido un error, el viaje no se modificó \n";
    }
}
public static function modificarViaje($idViaje,$origen,$destino, $cantMaxPasajeros, $colObjPasajeros, $nroResponsable,$costoViaje,$idEmpresa){

    $viaje = new Viaje;
    $respuesta = false;
    if (TestViaje::es_numerico($idViaje)) {
        if ($viaje->buscar($idViaje)) {
            $viaje->setColPasajeros($colObjPasajeros);
            if ($origen !== "") {
                $viaje->setVOrigen($origen);
            }
            if ($destino !== "") {
                $viaje->setVDestino($destino);
            }
            if ($costoViaje > 0) {
                $viaje->setCosto($costoViaje);
            }
            if (TestViaje::es_numerico($cantMaxPasajeros)) {
                $viaje->setCantMaxPasajeros($cantMaxPasajeros);
            }
            if (TestViaje::es_numerico($nroResponsable)) {
                $objResponsableV = new ResponsableV;
                if ($objResponsableV->buscar($nroResponsable)) {
                    $viaje->setObjResponsableV($objResponsableV);
                }
            }
            if (TestViaje::es_numerico($idEmpresa)) {
                $objEmpresa = new Empresa;
                if ($objEmpresa->buscar($idEmpresa)) {
                    $viaje->setObjEmpresa($objEmpresa);
                }
            }
            $respuesta = $viaje->modificar();
        }else{
            echo "El viaje no existe";
        }
    }else{
        echo "El id de Viaje tiene que ser un numero positivo";
    }
    return $respuesta;
}

public static function menuEliminarViaje(){

    TestViaje::mostrarViajes();
    echo " Eliminar Viaje \n";
    echo "ID de viaje a eliminar: ";
    $idViaje = trim(fgets(STDIN));
    if(TestViaje::eliminarViaje($idViaje)){
        echo " Viaje eliminado \n";
    }else{
        echo " Ha ocurrido un error, el viaje no se eliminó \n";
    }
}

public static function eliminarViaje($idViaje){

    $viaje = new Viaje;
    $respuesta = false ;
    if (TestViaje::es_numerico($idViaje)){
        if ($viaje->buscar($idViaje)) {
            $respuesta = $viaje->eliminar();
        }else{
            echo "El viaje no existe";
        }
    }else{
        echo "El id del viaje debe ser un número positivo";
    }
    return $respuesta;
}

public static function mostrarViajes(){ 

    $viaje = new Viaje;
    $viajes = $viaje->listar();

    foreach ($viajes as $viaje) {
        echo "\n$viaje";
    }
  }

  //Pasajeros

public static function menuBuscarPasajero(){
        TestViaje::mostrarPasajeros();
        echo "\nBuscar pasajero \n";
        echo "Ingresa el documento del pasajero: ";
        $documento = trim(fgets(STDIN));
        if ($pasajero = TestViaje::buscarPasajero($documento)) {
            echo $pasajero;
        } else {
            echo " Pasajero no encontrado";
        }
}

public static function buscarPasajero($documento){

    $rta = null;
    $pasajero = new Pasajero;
    if (TestViaje::es_numerico($documento)){
        if ($pasajero->buscar($documento)) {
            $rta = $pasajero;
        }
    }else{
        echo " Debe agregar el numero documento del Pasajero";
    }
    return $rta;
}

    
public static function menuCargarPasajero(){

    TestViaje::mostrarViajes();
    echo "\nAgregar pasajero \n";
    echo "Ingresa el nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingresa el apellido: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingresa el número de documento: ";
    $documento = trim(fgets(STDIN));
    echo "Ingresa el número de teléfono: ";
    $telefono = trim(fgets(STDIN));
    echo "Ingresa el id de viaje: ";
    $idViaje = trim(fgets(STDIN));
    if (TestViaje::agregarPasajero($nombre, $apellido, $documento, $telefono, $idViaje)) {
        echo " Pasajero agregado";
    } else {
        echo " Ha ocurrido un error";
    }
}

public static function agregarPasajero($nombre, $apellido, $documento, $telefono, $idViaje){   

        $respuesta = false;
        $viaje = new Viaje;
        if (TestViaje::es_numerico($idViaje)) {
            if ($viaje->buscar($idViaje)) {
                $pasajero = new Pasajero;
                if (TestViaje::es_numerico($documento)){
                    $pasajero->cargar($nombre, $apellido, $documento, $telefono, $idViaje);
                    $respuesta = $pasajero->insertar();
                }else{
                    echo " El documento del Pasajero NO se agrego de forma correcta";
                }
            }else{
                echo " Viaje no encontrado ";
            }
        }else{
            echo " El id del Viaje debe ser un numero mayor a cero ";
        }
        return $respuesta;
}

public static function menuModificarPasajero(){

        TestViaje::mostrarPasajeros();
        echo "\nModificar pasajero \n";
        echo "Ingresa el documento: ";
        $doc = trim(fgets(STDIN));
        echo "Ingresa el nombre: ";
        $nombre = trim(fgets(STDIN));
        echo "Ingresa apellido: ";
        $apellido = trim(fgets(STDIN));
        echo "Ingresa el  telefono: ";
        $telefono = trim(fgets(STDIN));
        echo "Ingresa el  idViaje: ";
        $idViaje = trim(fgets(STDIN));
        if (TestViaje::modificarPasajero($doc, $nombre, $apellido, $telefono, $idViaje)) {
            echo " Pasajero modificado";
        } else {
            echo "Ha ocurrido un error";
        }
}

public static function modificarPasajero($documento, $nombre, $apellido, $telefono, $idViaje){
        $pasajero = new Pasajero;
        $respuesta = false;
        if (TestViaje::es_numerico($documento)) {
            if ($pasajero->buscar($documento)) {
                if ($nombre !== "") {
                    $pasajero->setNombre($nombre);
                }
                if ($apellido !== "") {
                    $pasajero->setApellido($apellido);
                }
                if ($telefono !== "") {
                    $pasajero->setTelefono($telefono);
                }
                $viaje = new Viaje;
                if (TestViaje::es_numerico($idViaje)){
                    if ($viaje->buscar($idViaje)) {
                        $pasajero->setIdViaje($idViaje);
                    }
                }
                $respuesta = $pasajero->modificar();
            }else{
                echo " El pasajero no existe";
            }
        }else{
            echo " El documento del Pasajero NO se agrego de forma correcta";
        }
        return $respuesta;
}

public static function menuEliminarPasajero(){

    TestViaje::mostrarPasajeros();
    echo "\nEliminar pasajero \n";
    echo "Ingresa el doc del pasajero: ";
    $doc = trim(fgets(STDIN));
    if (TestViaje::eliminarPasajero($doc)) {
        echo " Pasajero eliminado";
    } else {
        echo " Ha ocurrido un error";
    }
}


public static function eliminarPasajero($doc){

    $pasajero = new Pasajero;
    $respuesta = false ;
    if (TestViaje::es_numerico($doc)){
        if ($pasajero->buscar($doc)) {
            $respuesta = $pasajero->eliminar();
        }else{
            echo " El pasajero no existe";
        }
    }else{
        echo " El documento del Pasajero NO se agrego de forma correcta";
    }
    return $respuesta;
}


public static function mostrarPasajeros(){
    $pasajeroObj = new Pasajero();
    $pasajeros = $pasajeroObj->listar();

    foreach ($pasajeros as $unPasajero) {
        echo "\n$unPasajero";
    }
}

//Responsables
public static function menuCargarResponsable(){

    echo "\nAgregar responsable \n";
    echo "Ingresa el nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingresa el apellido: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingresa el número de documento: ";
    $documento = trim(fgets(STDIN));
    echo "Ingresa el número de telefono: ";
    $telefono = trim(fgets(STDIN));
    echo "Ingresa el número de licencia: ";
    $numeroLicencia = trim(fgets(STDIN));
    if (TestViaje::agregarResponsable($documento,$nombre, $apellido, $telefono, $numeroLicencia)) {
        echo " Responsable agregado \n";
    } else {
        echo " Ha ocurrido un error \n";
    }
}
public static function agregarResponsable($documento,$nombre, $apellido,$telefono, $numeroLicencia){

    $responsable = new ResponsableV;
    $responsable->cargar($documento,$nombre, $apellido,$telefono, $numeroLicencia);
    return $responsable->insertar();
}

public static function menuModificarResponsable(){

    TestViaje::mostrarResponsables();
    echo "\nModificar responsable \n";
    echo "Ingresa el número de empleado a modificar: ";
    $numeroEmpleado = trim(fgets(STDIN));
    echo "Ingresa el nuevo número de licencia: ";
    $numeroLicencia = trim(fgets(STDIN));
    echo "Ingresa el nuevo nombre: ";
    $nombre = trim(fgets(STDIN));
    echo "Ingresa el nuevo apellido: ";
    $apellido = trim(fgets(STDIN));
    echo "Ingresa el nuevo telefono: ";
    $telefono = trim(fgets(STDIN));
    
    
    if (TestViaje::modificarResponsable($nombre, $apellido,$telefono,$numeroLicencia,$numeroEmpleado)) {
        echo " Responsable modificado";
    } else {
        echo " Ha ocurrido un error";
    }
}

public static function modificarResponsable($nombre, $apellido,$telefono,$numeroLicencia,$numeroEmpleado){

    $responsable = new ResponsableV;
    $respuesta = false;
    if (TestViaje::es_numerico($numeroEmpleado)) {
        if ($responsable->buscar($numeroEmpleado)) {
            if ($nombre !== "") {
                $responsable->setNombre($nombre);
            }
            if ($apellido !== "") {
                $responsable->setApellido($apellido);
            }
            if (TestViaje::es_numerico($telefono)) {
                $responsable->setTelefono($telefono);
            }
            if (TestViaje::es_numerico($numeroLicencia)) {
                $responsable->setRNumLicencia($numeroLicencia);
            }
            $respuesta = $responsable->modificar();
        }else{
            echo " El responsable no existe";
        }
    }else{
        echo " El numero de empleado del Responsable debe ser mayor a cero";
    }
    return $respuesta;
}

public static function menuEliminarResponsable(){

    TestViaje::mostrarResponsables();
    echo "\nEliminar responsable \n";
    echo "Ingresa el número de empleado: ";
    $numeroEmpleado = trim(fgets(STDIN));
    if (TestViaje::eliminarResponsable($numeroEmpleado)) {
        echo " Responsable eliminado";
    } else {
        echo " Ha ocurrido un error";
    }
}

public static function eliminarResponsable($numeroEmpleado){

    $responsable = new ResponsableV;
    $respuesta = false ;
    if (TestViaje::es_numerico($numeroEmpleado)){
        if ($responsable->buscar($numeroEmpleado)) {
            $respuesta = $responsable->eliminar();
        }else{
            echo " El responsable no existe";
        }
    }else{
        echo "El numero de empleado del Responsable debe ser mayor a cero";
    }
    return $respuesta;
}


public static function mostrarResponsables(){
    $responsableObj = new ResponsableV();
    $responsables = $responsableObj->listar();

    foreach ($responsables as $unResponsable) {
        echo "\n$unResponsable";
    }
}

public static function  es_numerico($valor) {
    //retorna true si es numerico y false si es string
    //solo sirve con enteros, los floats los toma como string por el . o ,

    // Convertir el valor a cadena si no lo es
    $cadena = strval($valor);
    
    // Utilizar preg_match para verificar si es numérico (sin incluir negativos)
    return preg_match('/^\d+$/', $cadena);
}


}

$bd = new BaseDatos();

if ($bd->iniciar()) {
    echo " Conexión exitosa con la base de datos.<br>";

    // Ejecutar el menú
    $test = new TestViaje();
    $test->menu();

} else {
    echo "Error de conexión: " . $bd->getError();
}


?>