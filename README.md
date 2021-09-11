# Task 1

## Запуск и выполнение тестов
`docker-compose up -d && docker-compose exec php php bin/phpunit`

## Выполнение тестов с покрытием
`docker-compose exec php bash -c 'phpenmod xdebug && php bin/phpunit --testdox --colors --coverage-text'`

Входной точкой является UseCase (CreateUser/UpdateUser/GetUser), т.к. UI-слоя нет.