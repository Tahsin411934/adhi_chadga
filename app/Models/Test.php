<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    // Specify the table name (optional if the table name matches the plural of the model name)
    protected $table = 'test';
public $timestamps = false;
    // Define the fillable fields
    protected $fillable = ['name', 'age'];
}
