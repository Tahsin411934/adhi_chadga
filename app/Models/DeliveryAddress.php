<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryAddress extends Model
{
    use HasFactory;

    // Define the table name
    protected $table = 'delivery_addresses';

    // Define the primary key (optional if it's 'id')
    protected $primaryKey = 'id';

    // Define which fields are mass assignable
    protected $fillable = [
        'user_id',
        'roadStreet',
        'houseNo',
        'floorNo',
        'blockSector',
        'flatNo',
        'name',
        'number',
        'deliveryNotes',
    ];

    // Define relationships
    public function user()
    {
        // Define the relationship with the 'User' model (assuming the 'users' table has a 'User' model)
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
