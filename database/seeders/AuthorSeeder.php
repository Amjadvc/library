<?php

namespace Database\Seeders;

use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $authors = [
            ['name' => 'نزار قباني'],
            ['name' => 'محمد صلاح'],
            ['name' => 'د. مجدي يعقوب'],
            ['name' => 'جورجي زيدان'],
            ['name' => 'د. جمال حمدان'],
        ];
        Author::insert($authors);
    }
}