<?php

namespace App\Http\Controllers;

use App\Http\Resources\EquipmentTypeResource;
use App\Models\EquipmentType;
use Exception;
use Illuminate\Http\Request;

class EquipmentTypeController extends Controller
{
    /**
     * 
     * 
     */
    public function index() {
        if (request('q') != null) {
            return EquipmentTypeResource::collection(EquipmentType::searchByQ(request('q'))->paginate());
        }
        $filter = request()->all();
        try {
            return EquipmentTypeResource::collection(EquipmentType::where($filter)->paginate());
        } catch (Exception $ex) {
            return response($ex->getMessage(), 400);
        }
    }
}
