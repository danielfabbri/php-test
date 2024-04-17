# Use a imagem oficial do PHP 7.1 como base
FROM php:7.1

# Baixe o Composer PHAR da versão 1.10
RUN curl -sS https://getcomposer.org/download/1.10.23/composer.phar -o /usr/local/bin/composer

# Torne o Composer PHAR executável
RUN chmod +x /usr/local/bin/composer

# Instale as extensões necessárias do PHP para o Yii2
RUN docker-php-ext-install pdo_mysql

# Defina o diretório de trabalho como o diretório raiz do seu projeto
WORKDIR /var/www/html

# Copie todos os arquivos do seu projeto para o diretório de trabalho no contêiner
COPY . .

# Exponha a porta 80 para acessar o aplicativo Yii2
EXPOSE 80

# Comando padrão para iniciar o servidor PHP embutido e servir o aplicativo Yii2
CMD ["php", "yii", "serve", "--port=80", "--docroot=web"]