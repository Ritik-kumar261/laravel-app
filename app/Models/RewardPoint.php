<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RewardPoint extends BaseModel
{
    protected $table = 'bw_customer_reward_points'; // Replace with your table name

    protected $fillable = ['reward_points']; // Replace with your column names
    use HasFactory;
}
