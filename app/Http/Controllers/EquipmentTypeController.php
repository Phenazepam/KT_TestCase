<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentTypeResource;
use App\Models\EquipmentType;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    /**
     * 
     * 
     */
    public function index() {
        return EquipmentTypeResource::collection(EquipmentType::paginate());
    }
}
