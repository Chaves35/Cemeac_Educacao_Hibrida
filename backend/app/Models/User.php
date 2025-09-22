// backend/app/Models/User.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Validation\Rule;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'school_id'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Regras de validação para criação/atualização
    public static function rules($id = null)
    {
        return [
            'name' => ['required', 'min:3', 'max:255'],
            'email' => [
                'required', 
                'email', 
                Rule::unique('users', 'email')->ignore($id)
            ],
            'password' => $id ? 
                ['sometimes', 'min:8'] : 
                ['required', 'min:8'],
            'role' => [
                'required', 
                Rule::in(['admin', 'gestor', 'professor', 'aluno'])
            ],
            'school_id' => [
                'nullable', 
                'exists:schools,id'
            ]
        ];
    }

    // Relacionamento com School
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // Relacionamento com StudentActivity
    public function activities()
    {
        return $this->hasMany(StudentActivity::class);
    }

    // Relacionamento com ForumPost
    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }
}