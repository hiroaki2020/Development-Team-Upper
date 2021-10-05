<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SkillOption;

class SkillOptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SkillOption::create(['skill' => 'php']);
        SkillOption::create(['skill' => 'python']);
        SkillOption::create(['skill' => 'ruby']);
        SkillOption::create(['skill' => 'go']);
        SkillOption::create(['skill' => 'rust']);
        SkillOption::create(['skill' => 'java']);
        SkillOption::create(['skill' => 'javascript']);
        SkillOption::create(['skill' => 'swift']);
        SkillOption::create(['skill' => 'kotlin']);
        SkillOption::create(['skill' => 'objective-c']);
        SkillOption::create(['skill' => 'html']);
        SkillOption::create(['skill' => 'css']);
        SkillOption::create(['skill' => 'laravel']);
        SkillOption::create(['skill' => 'django']);
        SkillOption::create(['skill' => 'ruby on rails']);
        SkillOption::create(['skill' => 'react']);
        SkillOption::create(['skill' => 'vue']);
        SkillOption::create(['skill' => 'nuxt']);
        SkillOption::create(['skill' => 'node.js']);
        SkillOption::create(['skill' => 'angular']);
        SkillOption::create(['skill' => 'tailwind']);
        SkillOption::create(['skill' => 'bootstrap']);
        SkillOption::create(['skill' => 'groovy']);
        SkillOption::create(['skill' => 'docker']);
        SkillOption::create(['skill' => 'kubernetes']);
        SkillOption::create(['skill' => 'aws']);
        SkillOption::create(['skill' => 'azure']);
        SkillOption::create(['skill' => 'git']);
    }
}
