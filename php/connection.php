<?php
require_once("database.php");

class connection {

	private $hostname  = '';
	private $username  = '';
	private $password  = '';
	private $dbname    = '';
	private $userTable = "";

	private $db; 
	private $dbAll_rows;
	private $dbMejorCompra;
	private $dbMejorVenta;
	private $dbLastUpdate;
	private $dbUpdateRows;

	// CONSTRUCTOR
	public function __construct(){
		global $hostname, $username, $password, $dbname, $db;
		$this->db = new mysqli($hostname, $username, $password, $dbname);
		if($db->connect_errno > 0){
    		error_log("connection to database: ".$db->connect_error);
		}
	}

    //Get all rows, to update
    public function updateRows(){
        global $dbUpdateRows;
        if (empty($dbUpdateRows) || is_null($dbUpdateRows)){
            $this->getUpdateRows();
        }
        return $dbUpdateRows;
    }

    private function getUpdateRows(){
        global $dbUpdateRows;
        $sql = 'SELECT clave, nombre, compra, venta, zona, disponible, direccion, latitude, longitude,horario FROM CASAS_DE_CAMBIO';
        $dbUpdateRows = $this->handlerSqlError($sql);
    }

    //get  best sell
	public function bestSell(){
		global $dbMejorVenta;
		if (empty($dbMejorVenta) || is_null($dbMejorVenta)){
      		$this->getBestSell();
      	}
		return $dbMejorVenta;		
	}

 	private function getBestSell(){
		global $dbMejorVenta;
		$sql = 'SELECT nombre, venta, latitude, longitude, zona FROM CASAS_DE_CAMBIO WHERE disponible = 1 ORDER BY venta ASC LIMIT 1';
		$rows = $this->handlerSqlError($sql);
		$rows = $rows->fetch_assoc();
		$dbMejorVenta = array('NOMBRE' => $rows['nombre'],'VENTA' => $rows['venta'], 'LAT' => $rows['latitude'], 'LONG' => $rows['longitude'], 'ZONA' => $rows['zona']);
   	}

   	//get  best buy
	public function bestBuy(){
		global $dbMejorCompra;
		if (empty($dbMejorCompra) || is_null($dbMejorCompra)){
      		$this->getBestBuy();
      	}
		return $dbMejorCompra;		
	}

 	private function getBestBuy(){
		global $dbMejorCompra;
		$sql = 'SELECT nombre, compra, latitude, longitude ,zona FROM CASAS_DE_CAMBIO WHERE disponible = 1 ORDER BY compra DESC LIMIT 1';
		$rows = $this->handlerSqlError($sql);
		$rows = $rows->fetch_assoc();
		$dbMejorCompra = array('NOMBRE' => $rows['nombre'], 'COMPRA' => $rows['compra'], 'LAT' => $rows['latitude'], 'LONG' => $rows['longitude'],'ZONA' => $rows['zona']);
   	}

	// ALL ROWS
	public function allRows() {
      global $dbAll_rows;
      if (empty($dbAll_rows) || is_null($dbAll_rows)){
      	$this->getAllRows();
      }
      return $dbAll_rows;
   	}

   	private function getAllRows(){
   		global $dbAll_rows;
   		$sql = 'SELECT nombre,venta,compra,latitude,longitude,zona,clave FROM CASAS_DE_CAMBIO WHERE disponible = 1';
   		$dbAll_rows = $this->handlerSqlError($sql);
   	}

   	// LAST UPDATE
   	public function lastUpdate() {
      global $dbLastUpdate;

      if (empty($dbLastUpdate) || is_null($dbLastUpdate)){
      	$this->getLastUpdate();
      }
      return mysqli_fetch_assoc($dbLastUpdate);
   	}

   	private function getLastUpdate(){
   		global $dbLastUpdate;
   		$sql = 'SELECT LAST_UPDATE FROM OLD_AVERAGE ORDER BY LAST_UPDATE DESC LIMIT 1';
   		$dbLastUpdate = $this->handlerSqlError($sql);
   	}

   	// Handler mysql error
   	private function handlerSqlError($sql){	
   		global $db;
   		if(!$rows = $db->query($sql)){
   			error_log("mysql[".$db->error ."]");
		}
		return $rows;
   	}

   	public function updateTable($query){
        $query = 'UPDATE CASAS_DE_CAMBIO SET '.$query;
        return $this->sendQuery($query);
	}

    private function sendQuery($query){
		return $this->handlerSqlError($query);
    }

    public function createCasa($query){
        $query = 'INSERT INTO CASAS_DE_CAMBIO  '.$query;
        return $this->sendQuery($query);
    }

    public function deleteCasa($query){
        $query = "DELETE FROM CASAS_DE_CAMBIO WHERE clave='".$query."'";
        return $this->sendQuery($query);
    }
}
?>
