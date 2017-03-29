# plugin-api-client

Пример использования
```php
$client = new Client(['base_uri' => 'http://hostname']);
$client->setAccessToken('access_token');


try {
    $data = $client->systemTime();
} catch (BadResponseException $exc) {

}
```

## Тестирование
- скопировать файл phpunit.xml.dist -> phpunit.xml
- заполнить правильными данными
- запустить ` vendor/bin/phpunit`


## todo
Рефакторинг. Сделать вызов методов через __call. Но стоит подумать на методами, где в url адресе передается ID.