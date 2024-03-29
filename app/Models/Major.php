<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Major extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%$search%");
    }

    public function classes()
    {
        return $this->hasMany(IClass::class);
    }
}
