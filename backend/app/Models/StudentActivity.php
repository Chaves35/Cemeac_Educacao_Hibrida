<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class StudentActivity extends Model
{
    use HasFactory;

    // Status possíveis para atividade do estudante
    const STATUS = [
        'nao_iniciado', 
        'em_progresso', 
        'concluido', 
        'reprovado'
    ];

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'user_id', 
        'activity_id', 
        'status', 
        'score', 
        'attempts',
        'started_at',
        'completed_at'
    ];

    // Configurações de cast para datas e tipos
    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    // Regras de validação
    public static function rules($id = null)
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'activity_id' => ['required', 'exists:activities,id'],
            'status' => [
                'required', 
                Rule::in(self::STATUS)
            ],
            'score' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'attempts' => ['nullable', 'integer', 'min:0', 'max:10'],
            'started_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date', 'after_or_equal:started_at']
        ];
    }

    // Relacionamento com User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relacionamento com Activity
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }
}