<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillOption extends Model
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

    public function skills()
    {
        return $this->hasMany(Skill::class, 'skill', 'skill');
    }

    public function requirements()
    {
        return $this->hasMany(Skill::class, 'requirement', 'skill');
    }

}
