<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfilInstansi extends Model
{
    use HasFactory;

    protected $table = 'profil_instansi';
    protected $guarded = ['id'];
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id',
        'user_updated',
        'updated_at'
    ];
}
