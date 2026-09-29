<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $defaultThemes = [
            [
                'id' => 'artemis',
                'label' => 'Artemis (Sage & Quince)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'inter',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'pill',
                'btn_shadow' => 'none',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'solid',
                'colors' => [
                    'background' => '#4A5844',
                    'foreground' => '#D5D4CD',
                    'primary' => '#2D3929',
                    'accent' => '#4A5844',
                    'text' => '#2D3929'
                ],
                'elements' => [
                    'subtitle' => 'Your daily dose of vitamin C',
                    'banner_image' => 'https://images.unsplash.com/photo-1610832958506-aa56368176cf?q=80&w=800&auto=format&fit=crop'
                ]
            ],
            [
                'id' => 'balcombe',
                'label' => 'Balcombe (Solar & Pool)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'outfit',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'pill',
                'btn_shadow' => 'medium',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#FFFFFF',
                    'primary' => '#0F172A',
                    'accent' => '#38BDF8',
                    'text' => '#0F172A'
                ],
                'elements' => [
                    'subtitle' => 'an innovative solar design practice bringing energy to daily life.'
                ]
            ],
            [
                'id' => 'boulton',
                'label' => 'Boulton (Warm Terracotta)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'montserrat',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'rounded',
                'btn_shadow' => 'none',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1518709268805-4e9042af9f23?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#EAA07A',
                    'primary' => '#2D231E',
                    'accent' => '#EAA07A',
                    'text' => '#FFFFFF'
                ],
                'elements' => [
                    'subtitle' => 'Aspiring skater with a taste for cooking.'
                ]
            ],
            [
                'id' => 'bourke',
                'label' => 'Bourke (Electric Track Wavy)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'poppins',
                'icon_family' => 'bootstrap-icons',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'wavy',
                'btn_shadow' => 'hard',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1461896836934-ffe607ba8211?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#FFFFFF',
                    'primary' => '#1E3A8A',
                    'accent' => '#2563EB',
                    'text' => '#1E293B'
                ],
                'elements' => [
                    'subtitle' => 'Long Distance Runner & Athletic Fitness'
                ]
            ],
            [
                'id' => 'constance',
                'label' => 'Constance (Skate Urban)',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'standard',
                'font_family' => 'outfit',
                'icon_family' => 'remixicon',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'rounded',
                'btn_shadow' => 'soft',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1520045892732-304bc3ac5d8e?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'scroll',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1520045892732-304bc3ac5d8e?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#FFFFFF',
                    'primary' => '#000000',
                    'accent' => '#64748B',
                    'text' => '#0F172A'
                ],
                'elements' => [
                    'subtitle' => 'Brand Ambassador for Helix, based in SoCal.'
                ]
            ],
            [
                'id' => 'coromandel',
                'label' => 'Coromandel (Macaron Wavy)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'playfair-display',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'wavy',
                'btn_shadow' => 'soft',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1569864358642-9d1684040f43?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1569864358642-9d1684040f43?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#FFF8F0',
                    'primary' => '#8B1E0F',
                    'accent' => '#C2410C',
                    'text' => '#8B1E0F'
                ],
                'elements' => [
                    'subtitle' => 'Plant-based bakery & artisanal tea salon'
                ]
            ],
            [
                'id' => 'hanna',
                'label' => 'Hanna (Vinyl Beats)',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'standard',
                'font_family' => 'inter',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'pill',
                'btn_shadow' => 'soft',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1539375665275-f9de415ef9ac?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'scroll',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1539375665275-f9de415ef9ac?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#FFFFFF',
                    'primary' => '#1E293B',
                    'accent' => '#475569',
                    'text' => '#0F172A'
                ],
                'elements' => [
                    'subtitle' => 'Soul beats and mech from Hackney'
                ]
            ],
            [
                'id' => 'hay',
                'label' => 'Hay (Court Blue Sectioned)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'outfit',
                'icon_family' => 'boxicons',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'pill',
                'btn_shadow' => 'soft',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'solid',
                'colors' => [
                    'background' => '#0088CC',
                    'foreground' => '#FFFFFF',
                    'primary' => '#004466',
                    'accent' => '#38BDF8',
                    'text' => '#004466'
                ],
                'elements' => [
                    'subtitle' => 'Augsburg University Men\'s Basketball Team'
                ]
            ],
            [
                'id' => 'healeys',
                'label' => 'Healeys (Neon Cyber Tokyo)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'space-grotesk',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'rounded',
                'btn_shadow' => 'glow',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1509198397868-475647b2a1e5?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1509198397868-475647b2a1e5?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#E07A5F',
                    'primary' => '#3D405B',
                    'accent' => '#F4F1DE',
                    'text' => '#FFFFFF'
                ],
                'elements' => [
                    'subtitle' => 'Mixing the old with the new in Harajuku'
                ]
            ],
            [
                'id' => 'heape',
                'label' => 'Heape (Monstera Leaf)',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'standard',
                'font_family' => 'playfair-display',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'square',
                'btn_shadow' => 'none',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'solid',
                'colors' => [
                    'background' => '#2D6A4F',
                    'foreground' => '#FFFFFF',
                    'primary' => '#1B4332',
                    'accent' => '#52B788',
                    'text' => '#1B4332'
                ],
                'elements' => [
                    'subtitle' => 'Blending the science of horticulture with the art of design'
                ]
            ],
            [
                'id' => 'heffernan',
                'label' => 'Heffernan (Line Art Outline)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'outfit',
                'icon_family' => 'line-awesome',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'outline',
                'btn_shadow' => 'none',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'solid',
                'colors' => [
                    'background' => '#F8FAFC',
                    'foreground' => 'rgba(99, 102, 241, 0.05)',
                    'primary' => '#4F46E5',
                    'accent' => '#6366F1',
                    'text' => '#4F46E5'
                ],
                'elements' => [
                    'subtitle' => 'Portfolio reviews, interview tips, and career advice'
                ]
            ],
            [
                'id' => 'iris',
                'label' => 'Iris (Soft Pastel Gradient)',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'standard',
                'font_family' => 'poppins',
                'icon_family' => 'bootstrap-icons',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'rounded',
                'btn_shadow' => 'medium',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'gradient',
                'colors' => [
                    'background' => 'linear-gradient(135deg, #E0C3FC 0%, #8EC5FC 100%)',
                    'foreground' => '#FFFFFF',
                    'primary' => '#3B82F6',
                    'accent' => '#8B5CF6',
                    'text' => '#1E293B'
                ],
                'elements' => [
                    'subtitle' => 'Solar design practice bringing solar energy into daily life.'
                ]
            ],
            [
                'id' => 'louden',
                'label' => 'Louden (Warm Nude & Beauty)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'outfit',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'pill',
                'btn_shadow' => 'soft',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => '#F3E8DE',
                    'primary' => '#8C6D58',
                    'accent' => '#D4A373',
                    'text' => '#5C4033'
                ],
                'elements' => [
                    'subtitle' => 'Makeup | Skin | Entrepreneur',
                    'banner_image' => 'https://images.unsplash.com/photo-1512496015851-a90fb38ba796?q=80&w=800&auto=format&fit=crop'
                ]
            ],
            [
                'id' => 'merlin',
                'label' => 'Merlin (Sunburst Mesh Gradient)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'inter',
                'icon_family' => 'remixicon',
                'layout_style' => 'portrait-hero',
                'card_style' => 'flat',
                'btn_shape' => 'rounded',
                'btn_shadow' => 'medium',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'gradient',
                'colors' => [
                    'background' => 'linear-gradient(180deg, #F87171 0%, #FBBF24 50%, #60A5FA 100%)',
                    'foreground' => '#FFFFFF',
                    'primary' => '#1E293B',
                    'accent' => '#F59E0B',
                    'text' => '#0F172A'
                ],
                'elements' => [
                    'subtitle' => 'Giving clothing a second life'
                ]
            ],
            [
                'id' => 'merlin-biz',
                'label' => 'Merlin Biz (Dark Espresso Glass)',
                'is_custom' => false,
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'inter',
                'icon_family' => 'fontawesome-6',
                'layout_style' => 'portrait-hero',
                'card_style' => 'glass',
                'btn_shape' => 'pill',
                'btn_shadow' => 'soft',
                'avatar_shape' => 'circle',
                'show_social_footer' => true,
                'social_style' => 'minimal',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'colors' => [
                    'background' => 'url("https://images.unsplash.com/photo-1514432324607-a09d9b4aefdd?q=80&w=1200&auto=format&fit=crop")',
                    'foreground' => 'rgba(255, 255, 255, 0.85)',
                    'primary' => '#1A0F0A',
                    'accent' => '#D4AF37',
                    'text' => '#1A0F0A'
                ],
                'elements' => [
                    'subtitle' => 'Coffee roasters, brewers and lovers'
                ]
            ]
        ];

        // Limpa a tabela de temas e insere os novos modelos padrão do Linktree
        Theme::truncate();

        foreach ($defaultThemes as $themeData) {
            Theme::create($themeData);
        }
    }
}
