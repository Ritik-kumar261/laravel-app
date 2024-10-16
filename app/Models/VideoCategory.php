<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoCategory extends BaseModel
{
    use HasFactory;
    protected $table= 'bw_video_categories';
    protected $fillable = ['title','image_url','order' ];
    // public function customerData()
    // {
    //     return $this->belongsTo(WorkoutSession::class);
    // }

// class WorkoutSession extends Model{
//     protected $table= 'customer_data';
//     public function videoCategory()
//     {
//         return $this->belongsTo(VideoCategory::class);
//     }
}
