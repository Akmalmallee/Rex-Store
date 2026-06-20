FROM php:8.2-apache

# Install dependencies sistem dan ekstensi PHP yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql gd zip bcmath

# Aktifkan mod_rewrite Apache untuk routing Laravel
# Aktifkan mod_rewrite Apache untuk routing Laravel
RUN a2enmod rewrite

# TARO DI SINI MAL:
RUN a2dismod mpm_event

# Atur Document Root Apache agar mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Atur Document Root Apache agar mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur folder kerja di dalam container
WORKDIR /var/www/html

# Copy semua file projek ke dalam container
COPY . .

# Install dependencies PHP via Composer (optimasi untuk production)
RUN composer install --no-interaction --optimize-autoloader --no-dev

# Atur hak akses (permissions) folder storage dan bootstrap/cache agar bisa ditulis oleh Apache
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Buka port 80
EXPOSE 80

# Jalankan Apache di foreground
CMD ["apache2-foreground"]