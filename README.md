Запуск проекта
Необходимо склонировать репозиторий:
    git clone https://github.com/1ravil/car_app.git
    git checkout main
Далее запускаем проект с помощью docker-compose:
    docker-compose up —build
Открываем новый терминал:
    docker-compose exec app php artisan migrate
Вбить в браузере:
    http://localhost:9000/swagger-ui/


