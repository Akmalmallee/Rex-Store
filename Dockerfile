# Aktifkan mod_rewrite Apache untuk routing Laravel
RUN a2enmod rewrite

# Hapus semua kemungkinan symlink MPM event agar mati total
RUN rm -f /etc/apache2/mods-enabled/mpm_event.load /etc/apache2/mods-available/mpm_event.load

# Atur Document Root Apache agar mengarah ke folder public Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Atur folder kerja di dalam container
WORKDIR /var/www/html

# Salin seluruh kode project ke dalam container
COPY . .

# Berikan hak akses penuh ke folder storage dan bootstrap/cache Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Jalankan instalasi library PHP via composer
RUN composer install --no-interaction --optimize-autoloader --no-dev

# PAKSA MATIKAN MODUL BENTROK SAAT CONTAINER STARTUP SEBELUM APACHE JALAN
CMD ["sh", "-c", "a2dismod mpm_event && a2enmod mpm_prefork && apache2-foreground"]