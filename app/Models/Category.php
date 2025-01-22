<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural form of the model name
    protected $table = 'categories'; // Replace with the actual table name if different

    // Specify the fields that are mass assignable
    protected $fillable = [
        'name', 'image'
    ];

    // Define the timestamp format if needed
    public $timestamps = false; // Set to true if the table has created_at and updated_at columns
}
