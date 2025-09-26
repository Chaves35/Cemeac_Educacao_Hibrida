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
        'difficulty_level',
        'estimated_duration',
        'is_active'
    ];

    const TYPES = ['presencial', 'online', 'hibrida'];
    const DIFFICULTY_LEVELS = ['basico', 'intermediario', 'avancado'];

    public static function rules()
    {
        return [
            'title' => ['required', 'min:3', 'max:255'],
            'description' => ['nullable', 'max:1000'],
            'type' => ['required', Rule::in(self::TYPES)],
            'school_id' => ['required', 'exists:schools,id'],
            'difficulty_level' => ['required', Rule::in(self::DIFFICULTY_LEVELS)],
            'estimated_duration' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['boolean']
        ];
    }

    // Relacionamentos
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function content()
    {
        return $this->belongsTo(Content::class);
    }

    public function studentActivities()
    {
        return $this->hasMany(StudentActivity::class);
    }

    public function forumPosts()
    {
        return $this->hasMany(ForumPost::class);
    }
}
