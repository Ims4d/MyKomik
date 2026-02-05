<?php

namespace Database\Seeders;

use App\Models\Genre;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // A comprehensive list of genres for Manga, Manhwa, and Manhua
        $genres = [
            'Action', 'Adventure', 'Comedy', 'Drama', 'Fantasy', 'Horror', 'Mystery', 
            'Romance', 'Sci-Fi', 'Slice of Life', 'Sports', 'Thriller', 'Isekai', 
            'Reincarnation', 'Regression', 'Murim', 'Cultivation', 'System', 
            'Shonen', 'Shojo', 'Seinen', 'Josei', 'Manhwa', 'Manhua', 'Manga',
            'Historical', 'Mecha', 'Psychological', 'Supernatural', 'Harem'
        ];

        // Use a timestamp for both created_at and updated_at
        $timestamp = now();

        $genreData = [];
        foreach ($genres as $genreName) {
            $genreData[] = [
                'name' => $genreName,
                'created_at' => $timestamp,
                'updated_at' => $timestamp,
            ];
        }

        // Use insertOrIgnore to prevent errors on duplicates and for better performance
        DB::table('genres')->insertOrIgnore($genreData);

        $this->command->info('Genre seeder finished.');
    }
}
