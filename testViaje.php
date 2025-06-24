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
        echo "------------\n";
        echo " Menú EMPRESA \n";
        echo "------------\n";
        echo "1- Agregar Empresa\n";
        echo "2- Modificar Empresa\n";
        echo "3- Eliminar Empresa\n";
        echo "4- Ver Empresa\n";
        echo "5- Menú de Viajes\n";
        echo "0- Salir\n";
        echo "Selecciona una opción\n";
        $opcion = trim(fgets(STDIN));

        switch ($opcion){

            case "1":
                echo " Agregar Empresa "."\n";
                echo "Nombre: ";
                $nombre = trim(fgets(STDIN));
                echo "Dirección: ";
                $direccion = trim(fgets(STDIN));
                if (TestViaje::agregarEmpresa($nombre,$direccion)){
                    echo "Empresa agregada";
                }else{
                    echo "Hubo un error, empresa no agregada";
                }
            break;

            case "2":
                echo " Modificar Empresa "."\n";
                echo "ID de empresa a modificar: ";
                $idEmpresa = trim(fgets(STDIN));
                echo "Nuevo Nombre: ";
                $nombre = trim(fgets(STDIN));
                echo "Nueva Dirección: ";
                $direccion = trim(fgets(STDIN));
                if (TestViaje::modificarEmpresa($idEmpresa,$nombre,$direccion)){
                    echo "Empresa modificada";
                }else{
                    echo "Hubo un error, empresa no modificada";
                }
                
            break;

            case "3":
                echo " Eliminar Empresa "."\n";
                echo "ID de empresa a eliminar: ";
                $idEmpresa = trim(fgets(STDIN));
                if(TestViaje::eliminarEmpresa($idEmpresa)){
                    echo "Empresa eliminada";
                }else{
                    echo "Hubo un error, empresa no eliminada";
                }
            break;

            case "4":
                TestViaje::mostrarEmpresas();
            break;

            case "5":
                $this->menuViajes();
            break;

            case "0":
                echo " Saliendo del menu Empresa...\n";
            break;

            default:
                echo "Opción invalida \n";
            break;
        }
    }

    while($opcion != "0");
}

public function menuViajes(){

    do{
        echo "------------ ";
        echo " Menú VIAJES ";
        echo "------------ \n";
        echo "1- Agregar viaje\n";
        echo "2- Modificar viaje\n";
        echo "3- Eliminar viaje\n";
        echo "4- Ver viajes\n";
        echo "0- Salir\n";
        echo "Selecciona una opción\n";
        $op = trim(fgets(STDIN));

        switch ($op){

            case "1":

                echo "Agregar viaje"."\n";
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
                     echo " Viaje agregado "."\n";
                }else{
                    echo " Ha ocurrido un error "."\n";
                }

            break;

            case "2":
                echo " Modificar Viaje "."\n";
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
                    echo " Viaje modificado "."\n";
                }else{
                    echo " Ha ocurrido un error, el viaje no se modificó "."\n";
                }
            break;

            case "3":
                echo " Eliminar Viaje "."\n";
                echo "ID de viaje a eliminar: ";
                $idViaje = trim(fgets(STDIN));
                if(TestViaje::eliminarViaje($idViaje)){
                  echo " Viaje eliminado "."\n";
                }else{
                    echo " Ha ocurrido un error, el viaje no se eliminó "."\n";
                }
            break;

            case "4":
                TestViaje::mostrarViajes();
            break;

            case "0":
                echo " Saliendo del menu viajes...\n";
            break;

            default:
                echo "Opción invalida \n";
            break;
        }
    }

    while($op != "0");

}

public static function  es_numerico($valor) {
        //retorna true si es numerico y false si es string
        //solo sirve con enteros, los floats los toma como string por el . o ,
 
        // Convertir el valor a cadena si no lo es
        $cadena = strval($valor);
        
        // Utilizar preg_match para verificar si es numérico (sin incluir negativos)
        return preg_match('/^\d+$/', $cadena);
}

//EMPRESA
public static function agregarEmpresa($nombre, $direccion)
    {
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

public static function modificarEmpresa($id, $nombre, $direccion){

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