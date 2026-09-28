-- Script para limpar todas as tabelas do banco de dados (exceto migrations)
-- Reseta também todos os IDs (auto-incrementos/sequências)
-- Use com atenção no banco PostgreSQL local

TRUNCATE TABLE 
    public.store_links,
    public.contact_store,
    public.stores,
    public.contacts,
    public.users,
    public.sessions,
    public.jobs,
    public.failed_jobs,
    public.job_batches,
    public.password_reset_tokens,
    public."cache",
    public.cache_locks
RESTART IDENTITY CASCADE;
