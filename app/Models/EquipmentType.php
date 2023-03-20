<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentType extends Model
{
    use HasFactory;
    protected $table = 'equipment_types';

    protected $fillable = [
        'name',
        'serial_mask',
    ];

    public static function searchByQ(string $q) {
        $q = '%'.$q.'%';
        return EquipmentType::Where('id', 'like', $q)
                        ->orWhere('name', 'like', $q)
                        ->orWhere('serial_mask', 'like', $q);
    }
}
