<?php
session_start();
require_once("../php/connection.php");
$connection = new connection();

header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
header('Access-Control-Allow-Methods: POST');

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(openssl_random_pseudo_bytes(32));
}

if (isset($_SERVER['HTTP_CSRFTOKEN'])) {
    if ($_SERVER['HTTP_CSRFTOKEN'] !== $_SESSION['csrf_token']) {
        exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        $address = 'http://' . $_SERVER['SERVER_NAME'];
        if (strpos($address, $_SERVER['HTTP_ORIGIN']) !== 0 || strpos($address, "http://localhost")) {
            exit(json_encode([
                'error' => 'Invalid Origin header: ' . $_SERVER['HTTP_ORIGIN']
            ]));
        }

        $query = '';
        if (isset($_POST['delete']) && isset($_POST['clave'])){
            echo $query;
           echo $connection->deleteCasa($_POST['clave']);
        } else if(isset($_POST['create']) && isset($_POST['clave'])){
            $values ='';
            $columns = 'clave,nombre,zona,direccion,horario,venta,compra,latitude,longitude,disponible';

            $values = "'".$_POST['clave']."','".$_POST['nombre']."','".$_POST['zona']."','".$_POST['direccion']."','".$_POST['horarios']."','".$_POST['venta']."','".$_POST['compra']."','" .$_POST['latitude']."','".$_POST['longitude']."','".$_POST['disponible']."'";

            $query = '('.$columns.')  VALUES ('.$values.')';
            echo $query;

            echo $connection->createCasa($query) == 1 ? 'success' : 'fail';

        }else{
            if(isset($_POST['nombre'])){
                $query = "nombre='".$_POST['nombre']."', ";
            }

            if(isset($_POST['direccion'])){
                $query = $query."direccion='".$_POST['direccion']."', ";
            }

            if(isset($_POST['horarios'])){
                $query = $query."horario='".$_POST['horarios']."', ";
            }

            if(isset($_POST['zona'])){
                $query = $query."zona='".$_POST['zona']."', ";
            }

            if(isset($_POST['venta'])){
                $query = $query."venta='".$_POST['venta']."', ";
            }

            if(isset($_POST['compra'])){
                $query = $query."compra='".$_POST['compra']."', ";
            }

            if(isset($_POST['latitude'])){
                $query = $query."latitude='".$_POST['latitude']."', ";
            }

            if(isset($_POST['longitude'])){
                $query = $query."longitude='".$_POST['longitude']."', ";
            }

            if(isset($_POST['disponible'])){
                $query = $query."disponible='".$_POST['disponible']."', ";
            }

            if (!empty($query)){
                $query = rtrim($query,', ');
                $query = $query." WHERE clave='".$_POST['clave']."'";
                echo $query;

                echo $connection->updateTable($query) == 1 ? 'success' : 'fail';

            }
        }

    }
    else {
        exit(json_encode(['error' => 'No Origin header']));
    }

} else {
    exit(json_encode(['error' => 'No CSRF token.']));
}

?>
