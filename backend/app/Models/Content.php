<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Content extends Model
{
    use HasFactory;

    // Tipos de conteúdo permitidos
    const TYPES = [
        'video', 
        'documento', 
        'link_externo', 
        'texto'
    ];

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'title', 
        'description', 
        'type', 
        'url', 
        'file_path',
        'school_id'
    ];

    // Regras de validação
    public static function rules($id = null)
    {
        return [
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['nullable', 'max:1000'],
            'type' => [
                'required', 
                Rule::in(self::TYPES)
            ],
            'url' => [
                'nullable', 
                'url', 
                Rule::requiredIf(fn($input) => $input['type'] === 'link_externo')
            ],
            'file_path' => [
                'nullable', 
                'string', 
                Rule::requiredIf(fn($input) => in_array($input['type'], ['video', 'documento']))
            ],
            'school_id' => ['nullable', 'exists:schools,id']
        ];
    }

    // Relacionamento com School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relacionamento com Activities
    public function activities()
    {
        return $this->hasMany(Activity::class);
    }
}