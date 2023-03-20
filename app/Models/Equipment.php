<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Equipment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'type_id',
        'serial_number',
        'comment'
    ];

    private static $pattern = [
        "N" => "[0-9]",
        "A" => "[A-Z]",
        "a" => "[a-z]",
        "X" => "[A-Z0-9]",
        "Z" => "[-|_|@]"
    ];

    private static function checkSerialNumber($sn, $mask) {
        if (strlen($sn) != strlen($mask)) 
            return false;
        
        $splitted_mask = str_split($mask);

        $pattern_by_mask = "/^";
        foreach ($splitted_mask as $char) {
            $pattern_by_mask .= Equipment::$pattern[$char];
        }
        $pattern_by_mask .= "/";
        // dd($pattern_by_mask);
        return preg_match($pattern_by_mask, $sn) > 0 ? true : false;
    }

    public static function validateSerialNumber($sn, $equipment_type) {
        $mask = EquipmentType::findOrFail($equipment_type)->serial_mask;
        return Equipment::checkSerialNumber($sn, $mask);
    }

    public static function searchByQ(string $q) {
        $q = '%'.$q.'%';
        return Equipment::Where('id', 'like', $q)
                    ->orWhere('type_id', 'like', $q)
                    ->orWhere('serial_number', 'like', $q)
                    ->orWhere('comment', 'like', $q);
    }

    public function type(){
        return $this->belongsTo(EquipmentType::class, 'type_id', 'id');
    }
}
