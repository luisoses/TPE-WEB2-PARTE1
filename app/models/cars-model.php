<?php 

require_once 'app/models/config.php';
require_once 'app/models/database.php';

class CarsModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAllCars() {
        $query = "SELECT modelos.ID_Modelo, modelos.Modelo, marcas.Marca FROM modelos JOIN marcas ON modelos.ID_Marca = marcas.ID_Marca";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }


    public function getCar($id) {
        $query = 'SELECT modelos.*, marcas.Marca FROM modelos LEFT JOIN marcas ON modelos.ID_Marca = marcas.ID_Marca WHERE modelos.ID_Modelo = ?';
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function insertCars ($ID_Marca, $Modelo, $Motor, $Combustible, $Transmision, $Tipo) {

        $query = $this->db->prepare('INSERT INTO modelos(ID_Marca, Modelo, Motor, Combustible, Transmision, Tipo) VALUES (?,?,?,?,?,?)');
        $query->execute([$ID_Marca, $Modelo, $Motor, $Combustible, $Transmision, $Tipo]);

        $id = $this->db->lastInsertId();

        return $id;
    } 

    public function eraseCar($id) {
        $query = $this -> db ->prepare('DELETE FROM modelos WHERE ID_Modelo = ?');
        $query->execute([$id]);
    }

    public function updateCar($ID_Modelo, $ID_Marca, $Modelo, $Motor, $Combustible, $Transmision, $Tipo) {
        $query = $this->db->prepare('UPDATE modelos SET ID_Marca = ?, Modelo = ?, Motor = ?, Combustible = ?, Transmision = ?, Tipo = ? WHERE ID_Modelo = ?');
        $query->execute([$ID_Marca, $Modelo, $Motor, $Combustible, $Transmision, $Tipo, $ID_Modelo]);
    }

    public function getFilteredCars($filters) {
        $query = 'SELECT modelos.*, marcas.Marca FROM modelos LEFT JOIN marcas ON modelos.ID_Marca = marcas.ID_Marca WHERE 1=1';
        $params = [];

        if (!empty($filters['ID_Marca'])) {
            $query .= ' AND modelos.ID_Marca = ?';
            $params[] = $filters['ID_Marca'];
        }

        if (!empty($filters['Tipo'])) {
            $query .= ' AND modelos.Tipo = ?';
            $params[] = $filters['Tipo'];
        }

        if (!empty($filters['Combustible'])) {
            $query .= ' AND modelos.Combustible = ?';
            $params[] = $filters['Combustible'];
        }

        if (!empty($filters['Transmision'])) {
            $query .= ' AND modelos.Transmision = ?';
            $params[] = $filters['Transmision'];
        }

        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
