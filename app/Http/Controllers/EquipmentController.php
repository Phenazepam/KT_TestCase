<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Http\Requests\StoreEquipmentRequest;
use App\Rules\SerialNumberRule;
use App\Http\Requests\UpdateEquipmentRequest;
use App\Http\Resources\EquipmentResource;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return EquipmentResource::collection(Equipment::paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $data)
    {
        $response = [];
        foreach ($data['data'] as $key => $item) {
            $validator = Validator::make($item, [ 
                'serial_number' => ['required', new SerialNumberRule]
            ]);
            if ($validator->fails()) {
                $response[]["error"] = $validator->errors();
            }
            else {
                try {
                    $response[] = new EquipmentResource(Equipment::create($item));
                } catch (Exception $e) {
                    $response[]["error"] = $e->getMessage();
                }
            }
        }
        return response($response);
    }

    /**
     * Display the specified resource.
     */
    public function show(Equipment $equipment)
    {
        return new EquipmentResource($equipment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEquipmentRequest $request, Equipment $equipment)
    {
        $equipment->update($request->validated());
        return new EquipmentResource($equipment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return response(null, 204);
    }
}
