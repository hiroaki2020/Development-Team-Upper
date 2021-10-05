<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'requirement', 'required'        
    ];

    public function skillOptions()
    {
        return $this->belongsTo(SkillOption::class, 'requirement', 'skill');
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
