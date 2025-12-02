FROM nginx:latest AS nginx
WORKDIR /var/www/html

# Criar diretório para certificados SSL
RUN mkdir -p /etc/nginx/ssl

# Copiar a configuração do Nginx
COPY ./nginx.conf /etc/nginx/conf.d/default.conf
