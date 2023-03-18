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
use PhpParser\Node\Expr\BinaryOp\Equal;

class EquipmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $filter = request()->all();
        try {
            return EquipmentResource::collection(Equipment::where($filter)->paginate());
        } catch (Exception $ex) {
            return response($ex->getMessage(), 400);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEquipmentRequest $request)
    {
        $response = [];
        foreach ($request->serial_number as $item) {
            if (Equipment::validateSerialNumber($item, $request->type_id)) {
                $equipmentItem = [
                    'type_id' => $request->type_id,
                    'serial_number' => $item,
                    'comment' => $request->comment
                ];
                try {
                    $response[$item] = Equipment::create($equipmentItem);
                } catch (Exception $ex ) {
                    $response[$item] = new Equipment();
                    $response[$item]["error"] = $ex->getMessage();
                }
            }
            else {
                $response[$item] = new Equipment();
                    $response[$item]["error"] = "Серийный номер не соответствует маске.";
            };
        }
        return EquipmentResource::collection($response);
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
