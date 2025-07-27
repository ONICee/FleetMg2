<?php
namespace Controllers;

use Core\Controller;
use Models\Vehicle;

class VehicleController extends Controller
{
    public function index(): void
    {
        $vehicles = Vehicle::all();
        $this->view('vehicles/index', ['vehicles' => $vehicles]);
    }
}