<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;



class ListData extends BaseModel
{
  
    use HasFactory;

    protected $table = "listdata";
    protected $fillable = ['i_con','title','value','status'] ;
}
