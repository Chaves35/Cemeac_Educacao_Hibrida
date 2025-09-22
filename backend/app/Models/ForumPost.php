///ForumPost.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;

class ForumPost extends Model
{
    use HasFactory;

    // Status possíveis para o post
    const STATUS = [
        'ativo', 
        'fixado', 
        'arquivado'
    ];

    // Campos que podem ser preenchidos em massa
    protected $fillable = [
        'user_id', 
        'activity_id', 
        'parent_id', 
        'title', 
        'content', 
        'status'
    ];

    // Regras de validação
    public static function rules($id = null)
    {
        return [
            'user_id' => ['required', 'exists:users,id'],
            'activity_id' => ['nullable', 'exists:activities,id'],
            'parent_id' => ['nullable', 'exists:forum_posts,id'],
            'title' => ['required', 'min:3', 'max:255'],
            'content' => ['required', 'min:10', 'max:5000'],
            'status' => [
                'nullable', 
                Rule::in(self::STATUS)
            ]
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

    // Relacionamento com respostas (posts filhos)
    public function replies()
    {
        return $this->hasMany(ForumPost::class, 'parent_id');
    }

    // Relacionamento com post pai
    public function parentPost()
    {
        return $this->belongsTo(ForumPost::class, 'parent_id');
    }
}