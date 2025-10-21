<?php

class Database {
    
    private static $instance = null;
    private $db;

    private function __construct() {
        $this->db = new PDO(
            "mysql:host=".MYSQL_HOST.";dbname=".MYSQL_DB.";charset=utf8", 
            MYSQL_USER, MYSQL_PASS
        );
        $this->deploy();
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->db;
    }

    private function deploy() {
        $query = $this->db->query('SHOW TABLES');
        $tables = $query->fetchAll();

        if (count($tables) == 0) {
            $sql = <<<END
                CREATE TABLE IF NOT EXISTS `marcas` (
                    `ID_Marca` int(11) NOT NULL,
                    `Marca` varchar(20) NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                INSERT INTO `marcas` (`ID_Marca`, `Marca`) VALUES
                (1, 'Volkswagen'), (2, 'Ford'), (3, 'Fiat'), (4, 'Honda'), 
                (8, 'Subaru'), (9, 'Ferrari'), (10, 'Susuki'), 
                (11, 'Chevrolet'), (12, 'Scania'), (13, 'Volvo');

                CREATE TABLE IF NOT EXISTS `modelos` (
                    `ID_Modelo` int(11) NOT NULL,
                    `ID_Marca` int(11) NOT NULL,
                    `Modelo` varchar(20) NOT NULL,
                    `Motor` varchar(20) NOT NULL,
                    `Combustible` varchar(20) NOT NULL,
                    `Transmision` varchar(20) NOT NULL,
                    `Tipo` varchar(20) NOT NULL,
                    PRIMARY KEY (`ID_Modelo`),
                    KEY `ID_Marca` (`ID_Marca`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                INSERT INTO `modelos` (`ID_Modelo`, `ID_Marca`, `Modelo`, `Motor`, `Combustible`, `Transmision`, `Tipo`) VALUES
                (23, 2, 'F1000', 'IN LINE 6', 'Gasoil', 'Manual', 'Camioneta'),
                (24, 3, 'Uno', 'i4', 'Gasoil', 'Manual', 'Sedan'),
                (25, 1, 'Passat', 'V6', 'Nafta', 'Manual', 'Sedan'),
                (26, 11, 'Corsa', 'i4', 'Nafta', 'Manual', 'Sedan'),
                (27, 4, 'Civic', 'i4', 'Nafta', 'Manual', 'Coupe');

                CREATE TABLE IF NOT EXISTS `usuarios` (
                    `id` int(11) NOT NULL,
                    `email` varchar(250) NOT NULL,
                    `password` char(60) NOT NULL,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `email` (`email`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

                INSERT INTO `usuarios` (`id`, `email`, `password`) VALUES
                (1, 'webadmin@gmail.com', 'admin');
            END;
            $this->db->exec($sql);
        }
    }
}