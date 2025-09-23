<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class Activity extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 
        'description', 
        'type', 
        'school_id', 
        'content_id',
        'difficulty',
        'max_score'
    ];

    // Tipos de atividades permitidas
    const TYPES = [
        'verdadeiro_falso', 
        'multipla_escolha', 
        'drag_drop', 
        'subjetiva'
    ];

    // Níveis de dificuldade
    const DIFFICULTIES = ['facil', 'medio', 'dificil'];

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
            'school_id' => ['required', 'exists:schools,id'],
            'content_id' => ['nullable', 'exists:contents,id'],
            'difficulty' => [
                'required', 
                Rule::in(self::DIFFICULTIES)
            ],
            'max_score' => ['required', 'numeric', 'min:0', 'max:100']
        ];
    }

    // Relacionamento com School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relacionamento com Content
    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    // Relacionamento com StudentActivity
    public function studentActivities()
    {
        return $this->hasMany(StudentActivity::class);
    }

    // Relacionamento com ForumPost
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }
}