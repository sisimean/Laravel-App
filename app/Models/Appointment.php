<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'service_type', 'appointment_date', 'notes', 'status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
