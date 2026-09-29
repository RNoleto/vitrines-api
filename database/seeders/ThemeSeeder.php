<?php

namespace Database\Seeders;

use App\Models\Theme;
use Illuminate\Database\Seeder;

class ThemeSeeder extends Seeder
{
    public function run(): void
    {
        $presetThemes = [
            [ 
                'id' => 'premium-marina-editorial', 
                'label' => '👑 Premium Editorial (Marble Luxe)', 
                'is_custom' => false, 
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'serif',
                'layout_style' => 'landing-page',
                'card_style' => 'gold-bordered',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'parallax',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'bg_overlay' => ['enabled' => true, 'color' => '#1f1510', 'opacity' => 0.45, 'blur' => 2],
                'colors' => ['background' => '#F5EFEB', 'foreground' => 'rgba(255, 255, 255, 0.9)', 'primary' => '#6E4D3B', 'accent' => '#D4AF37', 'text' => '#2A1D16'],
                'backdrop_blur' => 0
            ],
            [ 
                'id' => 'premium-advocacy-royal', 
                'label' => '👑 Premium Advocacy Royal Parallax', 
                'is_custom' => false, 
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'cinzel',
                'layout_style' => 'portrait-hero',
                'card_style' => 'gold-bordered',
                'bg_type' => 'image',
                'bg_image_url' => 'https://images.unsplash.com/photo-1589829545856-d10d557cf95f?q=80&w=1200&auto=format&fit=crop',
                'bg_attachment' => 'fixed',
                'bg_size' => 'cover',
                'bg_position' => 'center',
                'bg_overlay' => ['enabled' => true, 'color' => '#091322', 'opacity' => 0.65, 'blur' => 3],
                'colors' => ['background' => '#0D1B2D', 'foreground' => 'rgba(17, 34, 57, 0.85)', 'primary' => '#D4AF37', 'accent' => '#F59E0B', 'text' => '#FFFFFF'],
                'backdrop_blur' => 0
            ],
            [ 
                'id' => 'premium-neon-mesh', 
                'label' => '👑 Premium Mesh Animated VIP', 
                'is_custom' => false, 
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'sans',
                'layout_style' => 'landing-page',
                'card_style' => 'glass',
                'bg_type' => 'animation',
                'bg_animation_type' => 'gradient-flow',
                'colors' => ['background' => 'linear-gradient(-45deg, #0F172A, #312E81, #581C87, #4c1d95)', 'foreground' => 'rgba(255, 255, 255, 0.12)', 'primary' => '#38BDF8', 'accent' => '#F43F5E', 'text' => '#FFFFFF'],
                'backdrop_blur' => 14
            ],
            [ 
                'id' => 'premium-medical-pearl', 
                'label' => '👑 Premium Medical Pearl VIP', 
                'is_custom' => false, 
                'is_premium' => true,
                'category' => 'premium',
                'font_family' => 'sans',
                'layout_style' => 'portrait-hero',
                'card_style' => 'glass',
                'bg_type' => 'solid',
                'colors' => ['background' => '#F0F9FF', 'foreground' => '#FFFFFF', 'primary' => '#0284C7', 'accent' => '#0D9488', 'text' => '#0F172A'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'default',
                'label' => 'Default Minimal',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'standard',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'flat',
                'bg_type' => 'solid',
                'colors' => ['background' => '#FAFAFA', 'foreground' => '#FFFFFF', 'primary' => '#6366F1', 'accent' => '#4F46E5', 'text' => '#1E293B'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'dark',
                'label' => 'Elegance Dark',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'dark',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'flat',
                'bg_type' => 'solid',
                'colors' => ['background' => '#0F172A', 'foreground' => '#1E293B', 'primary' => '#A855F7', 'accent' => '#38BDF8', 'text' => '#F8FAFC'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'light',
                'label' => 'Modern Light',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'standard',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'flat',
                'bg_type' => 'solid',
                'colors' => ['background' => '#F8FAFC', 'foreground' => '#FFFFFF', 'primary' => '#3B82F6', 'accent' => '#1D4ED8', 'text' => '#334155'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'light-gradient',
                'label' => 'Aurora Gradient',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'gradient',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'glass',
                'bg_type' => 'gradient',
                'colors' => ['background' => 'linear-gradient(135deg, #E0E7FF, #F3E8FF, #FCE7F3)', 'foreground' => 'rgba(255, 255, 255, 0.75)', 'primary' => '#4F46E5', 'accent' => '#7C3AED', 'text' => '#1E1B4B'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'cyberpunk',
                'label' => '⚡ Cyberpunk Neon',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'dark',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'flat',
                'bg_type' => 'animation',
                'bg_animation_type' => 'floating-orbs',
                'colors' => ['background' => '#0B0F19', 'foreground' => '#151D30', 'primary' => '#00F0FF', 'accent' => '#FF007F', 'text' => '#F0F6FC'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'emerald',
                'label' => '🌲 Emerald & Gold',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'dark',
                'font_family' => 'serif',
                'layout_style' => 'standard',
                'card_style' => 'gold-bordered',
                'bg_type' => 'solid',
                'colors' => ['background' => '#062C24', 'foreground' => '#0E4337', 'primary' => '#10B981', 'accent' => '#F59E0B', 'text' => '#ECFDF5'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'sunset-gradient',
                'label' => '🌅 Sunset Gradient',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'gradient',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'glass',
                'bg_type' => 'gradient',
                'colors' => ['background' => 'linear-gradient(135deg, #FF512F, #DD2476)', 'foreground' => 'rgba(255, 255, 255, 0.2)', 'primary' => '#FF512F', 'accent' => '#FFE000', 'text' => '#FFFFFF'],
                'backdrop_blur' => 0
            ],
            [
                'id' => 'ocean-gradient',
                'label' => '🌌 Ocean Gradient',
                'is_custom' => false,
                'is_premium' => false,
                'category' => 'gradient',
                'font_family' => 'sans',
                'layout_style' => 'standard',
                'card_style' => 'glass',
                'bg_type' => 'gradient',
                'colors' => ['background' => 'linear-gradient(135deg, #0F2027, #203A43, #2C5364)', 'foreground' => 'rgba(255, 255, 255, 0.12)', 'primary' => '#00D2FF', 'accent' => '#38BDF8', 'text' => '#FFFFFF'],
                'backdrop_blur' => 0
            ]
        ];

        foreach ($presetThemes as $themeData) {
            $theme = Theme::find($themeData['id']) ?? new Theme();
            $theme->fill($themeData);
            $theme->save();
        }
    }
}
