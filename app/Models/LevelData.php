<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelData extends BaseModel
{
    use HasFactory;
    protected $table= 'level_data';
    public function customerData()
    {
        return $this->belongsTo(CustomerData::class);
    }
}
class CustomerData extends Model{
    protected $table= 'customer_data';
    public function levelData()
    {
        return $this->belongsTo(LevelData::class);
    }
}
