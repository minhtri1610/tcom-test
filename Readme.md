#step 1: build docker
docker-compose up -d

#step 2: install laravel
- cd src/tcom
- composer install

#step 3: 
    - docker exec -it tcom_php /bin/bash
    - php artisan migrate
    - php artisan db:seed
    - php artisan test




#