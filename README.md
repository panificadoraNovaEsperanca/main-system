Requisitos: Docker, docker-compose e composer(execução do laravel sail)

## Ambientes

### Desenvolvimento

Para executar o projeto em ambiente de desenvolvimento (HTTP apenas):

```bash
docker-compose -f docker-compose-dev.yml up -d
```

O ambiente de desenvolvimento estará disponível em `http://localhost`

### Produção (HTTPS)

Para executar o projeto em ambiente de produção com HTTPS:

```bash
docker-compose up -d
```

#### Configuração de Certificados SSL

O ambiente de produção utiliza certificados SSL do Let's Encrypt. Os certificados devem estar localizados em:

- `/etc/letsencrypt/docker-nginx/fullchain.pem`
- `/etc/letsencrypt/docker-nginx/privkey.pem`

**Para gerar/renovar certificados Let's Encrypt:**

1. Certifique-se de que o domínio `admin.paesnovaesperanca.com.br` está apontando para o servidor
2. Instale o certbot:
   ```bash
   sudo apt-get update
   sudo apt-get install certbot
   ```
3. Gere o certificado:
   ```bash
   sudo certbot certonly --standalone -d admin.paesnovaesperanca.com.br --email panificadoranovaesperanca6@gmail.com --agree-tos --non-interactive
   ```
4. Copie os certificados para o diretório esperado:
   ```bash
   sudo mkdir -p /etc/letsencrypt/docker-nginx
   sudo cp /etc/letsencrypt/live/admin.paesnovaesperanca.com.br/fullchain.pem /etc/letsencrypt/docker-nginx/
   sudo cp /etc/letsencrypt/live/admin.paesnovaesperanca.com.br/privkey.pem /etc/letsencrypt/docker-nginx/
   ```

**Renovação automática:**

Configure um cron job para renovar os certificados automaticamente:
```bash
0 0 * * * certbot renew --quiet && docker-compose restart nginx
```

## Passos para executar o projeto (método antigo)

- sudo chmod +x run.sh
- ./run.sh

sudo lpadmin -x zebra_zd220
✅ 2. Criar a impressora em modo RAW (SEM DRIVER)
Esse é o modo correto para aplicações que enviam ZPL.

bash
Copiar código
sudo lpadmin \
  -p zebra_zd220 \
  -E \
  -v "usb://Zebra%20Technologies/ZTC%20ZD220-203dpi%20ZPL?serial=D5N250701816" \
  -m raw
Ativar:

bash
Copiar código
sudo cupsenable zebra_zd220
sudo cupsaccept zebra_zd220
✅ 3. Testar ZPL — agora deve imprimir IMEDIATO
bash
Copiar código
lp -d zebra_zd220 <<< $'^XA\n^FO30,30\n^A0N,40,40\n^FDRAW OK\n^FS\n^XZ'