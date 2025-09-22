// backend/app/Models/School.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class School extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'name', 
        'inep_code', 
        'address', 
        'city', 
        'state'
    ];

    // Regras de validação para criação/atualização
    public static function rules($id = null)
    {
        return [
            'name' => [
                'required', 
                'min:3', 
                'max:255',
                Rule::unique('schools', 'name')->ignore($id)
            ],
            'inep_code' => [
                'required', 
                'size:8', 
                'numeric',
                Rule::unique('schools', 'inep_code')->ignore($id)
            ],
            'address' => ['required', 'max:255'],
            'city' => ['required', 'max:100'],
            'state' => ['required', 'size:2', 'regex:/^[A-Z]{2}$/']
        ];
    }

    // Relacionamento com Users
    public function users()
    {
        return $this->hasMany(User::class);
    }

    // Relacionamento com Activities
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}