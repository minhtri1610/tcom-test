# Sử dụng hình ảnh PHP 8.3 chính thức
FROM php:8.3-fpm

# Thiết lập biến môi trường để giảm các câu hỏi trong quá trình cài đặt
ENV DEBIAN_FRONTEND=noninteractive

# Cài đặt các dependencies cần thiết
RUN apt-get update && apt-get install -y \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    libonig-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd \
    && docker-php-ext-install mysqli pdo pdo_mysql mbstring zip exif pcntl

# Sao chép mã nguồn ứng dụng
COPY . /var/www/html

# Thiết lập quyền truy cập
RUN chown -R www-data:www-data /var/www/html

# Thiết lập thư mục làm việc
WORKDIR /var/www/html
