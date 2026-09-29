<?php

namespace App\Http\Controllers;

use App\Models\IconFamily;
use Database\Seeders\IconFamilySeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IconFamilyController extends Controller
{
    /**
     * Listar todas as famílias de ícones cadastradas.
     */
    public function index()
    {
        $families = IconFamily::orderBy('is_system', 'desc')->orderBy('family_name', 'asc')->get();

        if ($families->isEmpty()) {
            (new IconFamilySeeder())->run();
            $families = IconFamily::orderBy('is_system', 'desc')->orderBy('family_name', 'asc')->get();
        }

        return response()->json($families);
    }

    /**
     * Criar / Importar uma nova família de ícones (Acesso restrito ao Admin).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'display_name' => 'nullable|string|max:255',
            'import_url' => 'required|string',
            'prefix' => 'nullable|string|max:100',
            'category' => 'nullable|string|max:50',
            'provider' => 'nullable|string|max:50',
            'sample_icons' => 'nullable|array',
        ]);

        $rawUrl = trim($validated['import_url']);
        $cleanUrl = $rawUrl;

        // Se o usuário colou uma tag HTML como <link href="..." rel="stylesheet">
        if (preg_match('/href=["\']([^"\']+)["\']/', $rawUrl, $matches)) {
            $cleanUrl = $matches[1];
        }

        $familyName = trim($validated['family_name']);
        $slugId = Str::slug($familyName);
        $displayName = !empty($validated['display_name']) ? trim($validated['display_name']) : $familyName;
        $prefix = !empty($validated['prefix']) ? trim($validated['prefix']) : 'fa-solid';

        $iconFamily = IconFamily::updateOrCreate(
            ['id' => $slugId],
            [
                'family_name' => $familyName,
                'display_name' => $displayName,
                'provider' => $validated['provider'] ?? 'custom',
                'import_url' => $cleanUrl,
                'prefix' => $prefix,
                'category' => strtolower($validated['category'] ?? 'general'),
                'is_system' => false,
                'sample_icons' => $validated['sample_icons'] ?? ['heart', 'star', 'user', 'store', 'envelope', 'link'],
            ]
        );

        return response()->json($iconFamily, 201);
    }

    /**
     * Excluir uma família de ícones cadastrada.
     */
    public function destroy($id)
    {
        $iconFamily = IconFamily::find($id);

        if (!$iconFamily) {
            return response()->json(['error' => 'Família de ícones não encontrada.'], 404);
        }

        $iconFamily->delete();

        return response()->json(['message' => 'Família de ícones excluída com sucesso.']);
    }
}
