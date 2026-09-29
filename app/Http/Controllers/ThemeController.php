<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Database\Seeders\ThemeSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ThemeController extends Controller
{
    /**
     * Listar todos os temas cadastrados no banco de dados.
     */
    public function index()
    {
        $themes = Theme::orderBy('is_premium', 'desc')->orderBy('created_at', 'desc')->get();

        // Se o banco estiver sem nenhum tema, executa a seed de backup automaticamente
        if ($themes->isEmpty()) {
            (new ThemeSeeder())->run();
            $themes = Theme::orderBy('is_premium', 'desc')->orderBy('created_at', 'desc')->get();
        }

        return response()->json($themes);
    }

    /**
     * Obter detalhes de um tema específico pelo ID/slug.
     */
    public function show($id)
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return response()->json(['error' => 'Tema não encontrado.'], 404);
        }

        return response()->json($theme);
    }

    /**
     * Criar um novo tema (Acesso restrito ao Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'label' => 'required|string|max:255',
            'id' => 'nullable|string|max:255',
            'is_premium' => 'nullable|boolean',
            'category' => 'nullable|string',
            'font_family' => 'nullable|string',
            'icon_family' => 'nullable|string',
            'layout_style' => 'nullable|string',
            'card_style' => 'nullable|string',
            'btn_shape' => 'nullable|string',
            'btn_shadow' => 'nullable|string',
            'avatar_shape' => 'nullable|string',
            'show_social_footer' => 'nullable|boolean',
            'social_style' => 'nullable|string',
            'elements' => 'nullable|array',
            'bg_type' => 'nullable|string',
            'bg_image_url' => 'nullable|string',
            'bg_attachment' => 'nullable|string',
            'bg_size' => 'nullable|string',
            'bg_position' => 'nullable|string',
            'bg_animation_type' => 'nullable|string',
            'bg_overlay' => 'nullable|array',
            'colors' => 'nullable|array',
            'backdrop_blur' => 'nullable|integer',
        ]);

        $slugId = !empty($validated['id']) ? Str::slug($validated['id']) : 'custom-' . Str::slug($validated['label']) . '-' . time();

        $theme = Theme::updateOrCreate(
            ['id' => $slugId],
            [
                'label' => $validated['label'],
                'is_custom' => true,
                'is_premium' => $validated['is_premium'] ?? true,
                'category' => $validated['category'] ?? 'premium',
                'font_family' => $validated['font_family'] ?? 'serif',
                'icon_family' => $validated['icon_family'] ?? 'fontawesome-6',
                'layout_style' => $validated['layout_style'] ?? 'portrait-hero',
                'card_style' => $validated['card_style'] ?? 'gold-bordered',
                'btn_shape' => $validated['btn_shape'] ?? 'pill',
                'btn_shadow' => $validated['btn_shadow'] ?? 'soft',
                'avatar_shape' => $validated['avatar_shape'] ?? 'circle',
                'show_social_footer' => $validated['show_social_footer'] ?? true,
                'social_style' => $validated['social_style'] ?? 'minimal',
                'elements' => $validated['elements'] ?? null,
                'bg_type' => $validated['bg_type'] ?? 'solid',
                'bg_image_url' => $validated['bg_image_url'] ?? null,
                'bg_attachment' => $validated['bg_attachment'] ?? 'scroll',
                'bg_size' => $validated['bg_size'] ?? 'cover',
                'bg_position' => $validated['bg_position'] ?? 'center',
                'bg_animation_type' => $validated['bg_animation_type'] ?? null,
                'bg_overlay' => $validated['bg_overlay'] ?? ['enabled' => false, 'color' => '#000000', 'opacity' => 0, 'blur' => 0],
                'colors' => $validated['colors'] ?? ['background' => '#FFFFFF', 'foreground' => '#F8FAFC', 'primary' => '#6366F1', 'accent' => '#4F46E5', 'text' => '#1E293B'],
                'backdrop_blur' => $validated['backdrop_blur'] ?? 0,
            ]
        );

        return response()->json($theme, 201);
    }

    /**
     * Atualizar qualquer tema existente no sistema (Acesso restrito ao Admin).
     */
    public function update(Request $request, $id)
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return response()->json(['error' => 'Tema não encontrado.'], 404);
        }

        $validated = $request->validate([
            'label' => 'sometimes|required|string|max:255',
            'is_premium' => 'nullable|boolean',
            'category' => 'nullable|string',
            'font_family' => 'nullable|string',
            'icon_family' => 'nullable|string',
            'layout_style' => 'nullable|string',
            'card_style' => 'nullable|string',
            'btn_shape' => 'nullable|string',
            'btn_shadow' => 'nullable|string',
            'avatar_shape' => 'nullable|string',
            'show_social_footer' => 'nullable|boolean',
            'social_style' => 'nullable|string',
            'elements' => 'nullable|array',
            'bg_type' => 'nullable|string',
            'bg_image_url' => 'nullable|string',
            'bg_attachment' => 'nullable|string',
            'bg_size' => 'nullable|string',
            'bg_position' => 'nullable|string',
            'bg_animation_type' => 'nullable|string',
            'bg_overlay' => 'nullable|array',
            'colors' => 'nullable|array',
            'backdrop_blur' => 'nullable|integer',
        ]);

        $theme->update($validated);

        return response()->json($theme);
    }

    /**
     * Excluir qualquer tema do sistema (Acesso restrito ao Admin).
     */
    public function destroy($id)
    {
        $theme = Theme::find($id);

        if (!$theme) {
            return response()->json(['error' => 'Tema não encontrado.'], 404);
        }

        $theme->delete();

        return response()->json(['message' => 'Tema excluído com sucesso.']);
    }
}
