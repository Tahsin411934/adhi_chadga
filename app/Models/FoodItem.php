<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FoodItem extends Model
{
    use HasFactory;
    public $timestamps = false;
    // যেসব ফিল্ডগুলিকে mass assignable হবে সেগুলি নির্দিষ্ট করুন
    protected $fillable = [
        'name', 'description', 'price', 'image_url', 'category_id', 'available', 'available_day'
    ];


    protected $casts = [
        'available_day' => 'array', // 'available_day' ফিল্ডটি JSON আকারে স্টোর এবং রিট্রিভ হবে
    ];





    public function getImageUrlPathAttribute()
    {
        return asset('storage/' . $this->image_url);
    }


    public function category()
    {
        return $this->belongsTo(Category::class); // Assuming each FoodItem belongs to a category
    }
}
