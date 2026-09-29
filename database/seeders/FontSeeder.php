<?php

namespace Database\Seeders;

use App\Models\Font;
use Illuminate\Database\Seeder;

class FontSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultFonts = [
            [
                'id' => 'inter',
                'family_name' => 'Inter',
                'display_name' => 'Inter (Modern Sans)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
                'category' => 'sans-serif',
                'is_system' => true,
            ],
            [
                'id' => 'playfair-display',
                'family_name' => 'Playfair Display',
                'display_name' => 'Playfair Display (Editorial Serif)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&display=swap',
                'category' => 'serif',
                'is_system' => true,
            ],
            [
                'id' => 'cinzel',
                'family_name' => 'Cinzel',
                'display_name' => 'Cinzel (Royal & Classic Serif)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600;700;900&display=swap',
                'category' => 'serif',
                'is_system' => true,
            ],
            [
                'id' => 'outfit',
                'family_name' => 'Outfit',
                'display_name' => 'Outfit (Clean Geometry Sans)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap',
                'category' => 'sans-serif',
                'is_system' => true,
            ],
            [
                'id' => 'poppins',
                'family_name' => 'Poppins',
                'display_name' => 'Poppins (Soft Geometric Sans)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap',
                'category' => 'sans-serif',
                'is_system' => true,
            ],
            [
                'id' => 'montserrat',
                'family_name' => 'Montserrat',
                'display_name' => 'Montserrat (Urban Bold Sans)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&display=swap',
                'category' => 'sans-serif',
                'is_system' => true,
            ],
            [
                'id' => 'roboto',
                'family_name' => 'Roboto',
                'display_name' => 'Roboto (Standard Neo-Grotesque)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap',
                'category' => 'sans-serif',
                'is_system' => true,
            ],
            [
                'id' => 'cormorant-garamond',
                'family_name' => 'Cormorant Garamond',
                'display_name' => 'Cormorant Garamond (Elegante Tradicional)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@400;600;700&display=swap',
                'category' => 'serif',
                'is_system' => true,
            ],
            [
                'id' => 'dancing-script',
                'family_name' => 'Dancing Script',
                'display_name' => 'Dancing Script (Caligráfica / Manuscrita)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Dancing+Script:wght@500;700&display=swap',
                'category' => 'handwriting',
                'is_system' => true,
            ],
            [
                'id' => 'space-grotesk',
                'family_name' => 'Space Grotesk',
                'display_name' => 'Space Grotesk (Futurista / Tech Display)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;700&display=swap',
                'category' => 'display',
                'is_system' => true,
            ],
        ];

        foreach ($defaultFonts as $fontData) {
            Font::updateOrCreate(['id' => $fontData['id']], $fontData);
        }
    }
}
