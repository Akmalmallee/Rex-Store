# ==========================================
# Optimized Production Dockerfile for Laravel
# ==========================================
FROM php:8.2-apache

# 1. Install System Dependencies & PHP Extensions
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. Configure Apache for Laravel (Mengarahkan ke folder public)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 3. Enable Apache Rewrite Module untuk Routing Laravel
RUN a2enmod rewrite

# 4. FIX BENTROK MPM: Matikan mpm_event dan pastikan mpm_prefork aktif sebelum running
RUN a2dismod mpm_event || true
RUN a2enmod mpm_prefork || true

# 5. Install Composer Terbaru
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Atur Folder Kerja
WORKDIR /var/www/html

# 7. Salin Seluruh Source Code Project
COPY . .

# 8. Atur Hak Akses Folder Storage & Cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Jalankan Instalasi Composer (Optimasi Production)
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs

# 10. Buka Port 80 (Akan otomatis dimapping oleh variabel PORT di Railway)
EXPOSE 80

# 11. Jalankan Apache Web Server
CMD ["apache2-foreground"]