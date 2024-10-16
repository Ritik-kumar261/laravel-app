<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// here it use the extend model for creating the custome model name CustomerData but here we can'nt youse the 
//functionalty of AUTH for login purpose for that we have to use to Model to Authenticatable
class CustomUser extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    protected $table='users_table' ;
    protected $fillable = [
        'name',
        'email',
        'roll_number',
        'phone_number',
        'role',
        'password',
        
    ] ;
    public function hasRole($role)
    {
        return $this->role === $role;
    }


}
