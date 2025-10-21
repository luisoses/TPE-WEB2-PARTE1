<?php 

require_once 'app/models/config.php';
require_once 'app/models/database.php';


class BrandsModel {

    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function insertBrands ($Marca) {
        $query = $this -> db -> prepare('INSERT INTO marcas(Marca) VALUES (?)');
        $query -> execute([$Marca]);

        $id = $this->db->lastInsertId();

        return $id;
    }

    public function getAllBrands() {
        $query = $this->db->prepare('SELECT * FROM marcas');
        $query->execute();
        return $query->fetchAll(PDO::FETCH_OBJ);
    }  

    public function eliminarMarca($idMarca) {
        $query = $this->db->prepare('DELETE FROM marcas WHERE ID_Marca = ?');
        return $query->execute([$idMarca]);
    }

    public function modificarMarca($idMarca, $nuevoNombre) {
        $query = $this->db->prepare("UPDATE marcas SET Marca = ? WHERE ID_Marca = ?");
        $query->execute([$nuevoNombre, $idMarca]);
    }

    public function tieneModelosAsociados($idMarca) {
        $query = $this->db->prepare('SELECT COUNT(*) FROM modelos WHERE ID_Marca = ?');
        $query->execute([$idMarca]);
        return $query->fetchColumn() > 0;
    }
}

