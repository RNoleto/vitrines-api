<?php

namespace App\Http\Controllers;

use App\Models\Font;
use Database\Seeders\FontSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FontController extends Controller
{
    /**
     * Listar todas as fontes cadastradas.
     */
    public function index()
    {
        $fonts = Font::orderBy('is_system', 'desc')->orderBy('family_name', 'asc')->get();

        if ($fonts->isEmpty()) {
            (new FontSeeder())->run();
            $fonts = Font::orderBy('is_system', 'desc')->orderBy('family_name', 'asc')->get();
        }

        return response()->json($fonts);
    }

    /**
     * Criar / Importar uma nova fonte personalizada (Acesso restrito ao Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'family_name' => 'nullable|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'import_url' => 'required|string',
            'category' => 'nullable|string|max:50',
            'provider' => 'nullable|string|max:50',
        ]);

        $rawUrl = trim($validated['import_url']);
        $cleanUrl = $rawUrl;
        $familyName = !empty($validated['family_name']) ? trim($validated['family_name']) : null;

        // Se o usuário colou uma tag HTML como <link href="..." rel="stylesheet">
        if (preg_match('/href=["\']([^"\']+)["\']/', $rawUrl, $matches)) {
            $cleanUrl = $matches[1];
        }

        // Tentar extrair o nome da família de fontes da URL do Google Fonts se não fornecido
        if (empty($familyName) && preg_match('/family=([^:&]+)/i', $cleanUrl, $familyMatches)) {
            $familyName = urldecode(str_replace('+', ' ', $familyMatches[1]));
        }

        if (empty($familyName)) {
            return response()->json(['error' => 'Não foi possível identificar o nome da família da fonte. Por favor informe o nome da fonte.'], 422);
        }

        $slugId = Str::slug($familyName);
        $category = strtolower($validated['category'] ?? 'sans-serif');
        $displayName = !empty($validated['display_name']) ? trim($validated['display_name']) : $familyName . ' (' . ucfirst($category) . ')';

        $font = Font::updateOrCreate(
            ['id' => $slugId],
            [
                'family_name' => $familyName,
                'display_name' => $displayName,
                'provider' => $validated['provider'] ?? (str_contains($cleanUrl, 'googleapis') ? 'google' : 'custom'),
                'import_url' => $cleanUrl,
                'category' => $category,
                'is_system' => false,
            ]
        );

        return response()->json($font, 201);
    }

    /**
     * Excluir uma fonte cadastrada.
     */
    public function destroy($id)
    {
        $font = Font::find($id);

        if (!$font) {
            return response()->json(['error' => 'Fonte não encontrada.'], 404);
        }

        $font->delete();

        return response()->json(['message' => 'Fonte excluída com sucesso.']);
    }
}
