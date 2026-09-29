<?php

namespace Database\Seeders;

use App\Models\IconFamily;
use Illuminate\Database\Seeder;

class IconFamilySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultFamilies = [
            [
                'id' => 'fontawesome-6',
                'family_name' => 'Font Awesome 6',
                'display_name' => 'Font Awesome 6 (Solid & Brands)',
                'provider' => 'cdnjs',
                'import_url' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css',
                'prefix' => 'fa-solid',
                'category' => 'general',
                'is_system' => true,
                'sample_icons' => ['fa-heart', 'fa-star', 'fa-user', 'fa-store', 'fa-envelope', 'fa-link'],
            ],
            [
                'id' => 'bootstrap-icons',
                'family_name' => 'Bootstrap Icons',
                'display_name' => 'Bootstrap Icons (Clean & Modern)',
                'provider' => 'jsdelivr',
                'import_url' => 'https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css',
                'prefix' => 'bi',
                'category' => 'minimal',
                'is_system' => true,
                'sample_icons' => ['bi-heart-fill', 'bi-star-fill', 'bi-person-fill', 'bi-shop', 'bi-envelope-fill', 'bi-link-45deg'],
            ],
            [
                'id' => 'remixicon',
                'family_name' => 'Remix Icon',
                'display_name' => 'Remix Icon (Neutral & Smooth)',
                'provider' => 'jsdelivr',
                'import_url' => 'https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css',
                'prefix' => 'ri',
                'category' => 'general',
                'is_system' => true,
                'sample_icons' => ['ri-heart-3-fill', 'ri-star-fill', 'ri-user-3-fill', 'ri-store-2-fill', 'ri-mail-fill', 'ri-link'],
            ],
            [
                'id' => 'boxicons',
                'family_name' => 'Boxicons',
                'display_name' => 'Boxicons (Vector & High Quality)',
                'provider' => 'unpkg',
                'import_url' => 'https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css',
                'prefix' => 'bx',
                'category' => 'outlined',
                'is_system' => true,
                'sample_icons' => ['bxs-heart', 'bxs-star', 'bxs-user', 'bxs-store', 'bxs-envelope', 'bx-link'],
            ],
            [
                'id' => 'line-awesome',
                'family_name' => 'Line Awesome',
                'display_name' => 'Line Awesome (Minimal Line Icons)',
                'provider' => 'maxst',
                'import_url' => 'https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css',
                'prefix' => 'las',
                'category' => 'minimal',
                'is_system' => true,
                'sample_icons' => ['la-heart', 'la-star', 'la-user', 'la-store', 'la-envelope', 'la-link'],
            ],
            [
                'id' => 'material-symbols',
                'family_name' => 'Material Symbols',
                'display_name' => 'Material Symbols (Google Outlined)',
                'provider' => 'google',
                'import_url' => 'https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0',
                'prefix' => 'material-symbols-outlined',
                'category' => 'general',
                'is_system' => true,
                'sample_icons' => ['favorite', 'grade', 'person', 'storefront', 'mail', 'link'],
            ],
        ];

        foreach ($defaultFamilies as $familyData) {
            IconFamily::updateOrCreate(['id' => $familyData['id']], $familyData);
        }
    }
}
