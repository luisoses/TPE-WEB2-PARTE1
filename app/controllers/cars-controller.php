<?php

require_once 'app/models/cars-model.php';
require_once 'app/models/brands-model.php';
require_once 'app/views/cars-view.php';

class CarsController {
    private $carsmodel;
    private $brandmodel;
    private $view;

    public function __construct() {
        $this->carsmodel = new CarsModel();
        $this->brandmodel = new BrandsModel();
        $this->view = new CarsView();
    }

    // Mostrar listado de autos y marcas
    public function showCars() {
        $cars = $this->carsmodel->getAllCars();
        $brands = $this->brandmodel->getAllBrands();
        $this->view->showCars($cars, $brands);
    }

    // Agregar nuevo auto
    public function addCars() {
        if (!empty($_POST['ID_Marca']) && isset($_POST['Modelo'], $_POST['Motor'], $_POST['Combustible'], $_POST['Transmision'], $_POST['Tipo'])) {
            $ID_Marca = $_POST['ID_Marca'];
            $Modelo = $_POST['Modelo'];
            $Motor = $_POST['Motor'];
            $Combustible = $_POST['Combustible'];
            $Transmision = $_POST['Transmision'];
            $Tipo = $_POST['Tipo'];

            $this->carsmodel->insertCars($ID_Marca, $Modelo, $Motor, $Combustible, $Transmision, $Tipo);
        }
        header('Location: ' . BASE_URL);
    }

    // Agregar nueva marca
    public function addBrands() {
        if (isset($_POST['nueva_marca']) && !empty($_POST['nueva_marca'])) {
            $Marca = $_POST['nueva_marca'];
            $this->brandmodel->insertBrands($Marca);
        }
        header('Location: ' . BASE_URL);
    }

    // Eliminar auto
    public function deleteCars($id) {
        $car = $this->carsmodel->getCar($id);
        if (!$car) {
            return $this->view->showError("No existe el auto con el id = $id");
        }
        $this->carsmodel->eraseCar($id);
        header('Location: ' . BASE_URL);
    }

    // Editar auto (mostrar formulario)
    public function editCar($id) {
        $car = $this->carsmodel->getCar($id);
        $brands = $this->brandmodel->getAllBrands();
        if (!$car) {
            return $this->view->showError("El auto no existe.");
        }
        $this->view->showEditForm($car, $brands);
    }

    // Actualizar auto
    public function updateCar() {
        if (!empty($_POST['ID_Modelo']) && isset($_POST['ID_Marca'], $_POST['Modelo'], $_POST['Motor'], $_POST['Combustible'], $_POST['Transmision'], $_POST['Tipo'])) {
            $ID_Modelo = $_POST['ID_Modelo'];
            $ID_Marca = $_POST['ID_Marca'];
            $Modelo = $_POST['Modelo'];
            $Motor = $_POST['Motor'];
            $Combustible = $_POST['Combustible'];
            $Transmision = $_POST['Transmision'];
            $Tipo = $_POST['Tipo'];

            $this->carsmodel->updateCar($ID_Modelo, $ID_Marca, $Modelo, $Motor, $Combustible, $Transmision, $Tipo);
            header('Location: ' . BASE_URL);
        } else {
            $this->view->showError('Faltan datos obligatorios.');
        }
    }

    // Filtrar autos
    public function filteredCars() {
        $filters = [];
        if (!empty($_POST['ID_Marca'])) $filters['ID_Marca'] = $_POST['ID_Marca'];
        if (!empty($_POST['Tipo'])) $filters['Tipo'] = $_POST['Tipo'];
        if (!empty($_POST['Combustible'])) $filters['Combustible'] = $_POST['Combustible'];
        if (!empty($_POST['Transmision'])) $filters['Transmision'] = $_POST['Transmision'];

        $cars = $this->carsmodel->getFilteredCars($filters);
        $brands = $this->brandmodel->getAllBrands();
        $this->view->showCars($cars, $brands);
    }

    // Mostrar detalles de un auto
    public function carDetails($id) {
        $carDetails = $this->carsmodel->getCar($id);
        if ($carDetails) {
            $this->view->showCarDetails($carDetails);
        } else {
            $this->view->showError("No se encontraron detalles para el auto con id = $id");
        }
    }

    // Eliminar marca
    public function eliminarMarca() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idMarca = $_POST['ID_Marca'];
            if ($this->brandmodel->tieneModelosAsociados($idMarca)) {
                echo 'No se puede eliminar la marca porque hay modelos asociados a ella. Primero elimine los modelos.';
            } else {
                if ($this->brandmodel->eliminarMarca($idMarca)) {
                    header('Location: ' . BASE_URL);
                } else {
                    echo 'Error al eliminar la marca';
                }
            }
        } else {
            $this->showCars(); // Redirige al listado principal
        }
    }

    // Modificar marca
    public function modificarMarca() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $idMarca = $_POST['ID_Marca'];
            $nuevaMarca = $_POST['Marca'];
            $this->brandmodel->modificarMarca($idMarca, $nuevaMarca);
            header('Location: ' . BASE_URL);
        }
    }
}

