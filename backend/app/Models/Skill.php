<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'skill',
    ];

    public function skillOption()
    {
        return $this->belongsTo(SkillOption::class, 'skill', 'skill');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
