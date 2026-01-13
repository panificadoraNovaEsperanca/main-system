#!/bin/bash

# Script para restaurar backup.sql no banco de dados PostgreSQL

echo "Iniciando restore do backup.sql..."

# Verificar se o container está rodando
if ! docker ps | grep -q "db"; then
    echo "Erro: Container do banco de dados não está rodando!"
    echo "Execute: docker-compose -f docker-compose-dev.yml up -d db"
    exit 1
fi

# Verificar se o arquivo existe
if [ ! -f "backup.sql" ]; then
    echo "Erro: Arquivo backup.sql não encontrado!"
    exit 1
fi

echo "Executando restore (isso pode levar alguns minutos)..."
# Método 1: Pipe direto (mais eficiente para arquivos grandes)
cat backup.sql | docker exec -i db psql -U panificadora_nova_esperanca -d panificadora_nova_esperanca

# Método alternativo: copiar para o container primeiro (descomente se o método acima não funcionar)
# echo "Copiando backup.sql para o container..."
# docker cp backup.sql db:/tmp/backup.sql
# echo "Executando restore..."
# docker exec db psql -U panificadora_nova_esperanca -d panificadora_nova_esperanca -f /tmp/backup.sql
# echo "Limpando arquivo temporário..."
# docker exec db rm -f /tmp/backup.sql

echo "Restore concluído com sucesso!"

