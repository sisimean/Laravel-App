<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // for Auth support
use Laravel\Sanctum\HasApiTokens; // if you want token support
use Illuminate\Support\Facades\Hash;

class Login extends Authenticatable
{
    use HasApiTokens, HasFactory;
    public function customers()
    {
        return $this->hasMany(Customer::class, 'login_id');
    }

    protected $fillable = [
        'email',
        'password',
        'name',
        'profile', // ✅ added to allow mass assignment
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // No need for timestamps hiding, default is fine
}
$user1 = Login::find(1);
$user1->password = Hash::make('123'); // update correct plain password
$user1->save();

$user2 = Login::find(2);
$user2->password = Hash::make('123'); // update correct plain password
$user2->save();
