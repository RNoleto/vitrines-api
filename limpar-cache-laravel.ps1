# limpar-cache-laravel.ps1
Write-Host "`n🧼 Limpando caches do Laravel..." -ForegroundColor Cyan

# Limpar cache de rotas, views e configurações (se estiverem acessíveis)
try {
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    php artisan cache:clear
    Write-Host "✔ Comandos Artisan executados." -ForegroundColor Green
} catch {
    Write-Host "⚠ Falha ao executar comandos Artisan. Prosseguindo com limpeza manual..." -ForegroundColor Yellow
}

# Limpar diretórios de cache manualmente
$pastas = @(
    ".\bootstrap\cache\*",
    ".\storage\framework\cache\data\*",
    ".\storage\framework\views\*"
)

foreach ($pasta in $pastas) {
    try {
        Remove-Item -Recurse -Force $pasta -ErrorAction SilentlyContinue
        Write-Host "✔ Limpou $pasta" -ForegroundColor Green
    } catch {
        Write-Host "❌ Erro ao limpar $pasta: $_" -ForegroundColor Red
    }
}

Write-Host "`n✅ Limpeza concluída com sucesso!" -ForegroundColor Cyan
