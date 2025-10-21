<?php

class CarsView {

    function showCars($cars, $brands) {
        require 'templates/listar.phtml';
    }
    
    function showError($error) {
        require 'templates/error.phtml';
    }

    function showEditForm($car, $brands) {
        require 'templates/form-editcar.phtml';
    }

    function showCarDetails($car) {
        require 'templates/car-details.phtml';
    }
}

