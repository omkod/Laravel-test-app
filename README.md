1. Клонировать репозиторий
2. Скопировть .env.example в .env
3. Указать:
    ```
    DB_CONNECTION=mysql
    DB_HOST=mysql
    DB_PORT=3306
    DB_DATABASE=example_db
    DB_USERNAME=example_dbu
    DB_PASSWORD=5DW0tECM
   ```

4. docker compose up -d
5. docker exec -it laravel-app-backend bash
6. composer install
7. php artisan migrate
8. php artisan db:seed
9. php artisan test
10. php artisan key:generate
11. Приложение доступно на http://172.25.248.3/

## API

### Авторизация

**URL**: ```/api/login```

**Method**: POST

#### Пример запроса

```
{
    "login": 'mail@mail.com',
    "password": '12345'
}
```

#### Пример ответа

```
{
"success": true,
    "data":{
        "token": "4|hNyFbOCO0Ec2xMAce45gUSDx4X2udCMBYcWWCxQa01ad60a5"
    }
}
```

token необходимо добавить в заголовки всех следующих запросов:
```Authorization: Bearer {token}```


### Информация о пользователе

**URL**: ```/api/user```

**Method**: GET

#### Пример запроса

```
{}
```

#### Пример ответа

```
{
    "success": true,
    "data": {
        "id": 14,
        "name": "Yesenia Erdman",
        "email": "userB@mail.com"
    }
}
```

### Информация о кошельках пользователя

**URL**: ```/api/wallet```

**Method**: GET

#### Пример запроса

```
{}
```

#### Пример ответа

```
{
    "success": true,
    "data": [
        {
            "id": 3,
            "user_id": 14,
            "currency": "USD",
            "balance": "10.00",
            "created_at": "2024-09-19T13:25:58.000000Z",
            "updated_at": "2024-09-19T13:25:58.000000Z"
        }
    ] 
}
```

### Список доступных заявок

**URL**: ```/api/offer```

**Method**: GET

#### Пример запроса

```
{}
```

#### Пример ответа

```
{
    "success": true,
    "data" : [
        {
            "id": 12,
            "user_id": 13,
            "status": "active",
            "currency_from": "USD",
            "amount_from": 20,
            "currency_to": "RUB",
            "amount_to": 2000,
            "cost": 2040 // стоимость с учетом комисии
        }
    ]
}
```

### Создание заявки

**URL**: ```/api/offer```

**Method**: POST

#### Пример запроса

```
{
    "currency_from": "RUB",
    "currency_to": "USD",
    "amount_from": 10,
    "amount_to": 1000
}
```

#### Пример ответа

```
{
    "success": true,
        "data": {
            "id": 18,
            "user_id": 1,
            "currency_from": "RUB",
            "currency_to": "USD",
            "amount_from": 10,
            "amount_to": 1000,
            "status": "active",
            "cost": 1020
        }
}
```

### Принятие заявки

**URL**: ```/api/offer/accept/{offer_id}```

**Method**: POST

#### Пример запроса

```
{}
```

#### Пример ответа

```
{
    "success": true,
    "data":[]
}
```
