#!/bin/bash
# Script para renovar certificados SSL Let's Encrypt
# Este script deve ser executado pelo cron job

set -e

LOG_FILE="/var/log/certbot-renewal.log"
COMPOSE_DIR="/home/ec2-user/main-system"
DOMAIN="admin.paesnovaesperanca.com.br"

echo "$(date): Iniciando renovação do certificado SSL" >> "$LOG_FILE"

# Parar o nginx
cd "$COMPOSE_DIR"
/usr/local/bin/docker-compose stop nginx >> "$LOG_FILE" 2>&1

# Renovar o certificado (só renova se necessário, dentro de 30 dias do vencimento)
/usr/bin/certbot renew --standalone --quiet >> "$LOG_FILE" 2>&1 || {
    echo "$(date): Erro na renovação do certificado" >> "$LOG_FILE"
    # Se falhar, tentar renovação forçada
    /usr/bin/certbot certonly --standalone -d "$DOMAIN" --email panificadoranovaesperanca6@gmail.com --agree-tos --non-interactive --force-renewal >> "$LOG_FILE" 2>&1
}

# Copiar os novos certificados (executado como root pelo cron)
cp /etc/letsencrypt/live/"$DOMAIN"/fullchain.pem /etc/letsencrypt/docker-nginx/ >> "$LOG_FILE" 2>&1
cp /etc/letsencrypt/live/"$DOMAIN"/privkey.pem /etc/letsencrypt/docker-nginx/ >> "$LOG_FILE" 2>&1
chmod 644 /etc/letsencrypt/docker-nginx/fullchain.pem >> "$LOG_FILE" 2>&1
chmod 600 /etc/letsencrypt/docker-nginx/privkey.pem >> "$LOG_FILE" 2>&1
chown root:root /etc/letsencrypt/docker-nginx/*.pem >> "$LOG_FILE" 2>&1

# Reiniciar o nginx
cd "$COMPOSE_DIR"
/usr/local/bin/docker-compose start nginx >> "$LOG_FILE" 2>&1

echo "$(date): Renovação do certificado SSL concluída com sucesso" >> "$LOG_FILE"
