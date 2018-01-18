# Plugin API

- [Авторизация и доступ до API](#Доступ-и-авторизация)
- [Описание методов](#Описание-методов)
  - [Создание аватара](#Создание-аватара)
  - [Обновление аватара](#Обновление-аватара)


## Доступ и авторизация

Обращение к API просиходит с помощью GET и POST запросы на соответствующие адреса методов.

Основной адрес API сервера: `http://plugin-web.globedrobe.com/api/v1/`

Авторизация производится с помощью токена, который можно получить, обратившись к
нам через форму на сайте - http://sharecloth.com/contacts/

В каждом запросе следует передавать token в заголовках с именем `Access-Token`.


## Описание методов

### Создание аватара

```
[POST] avatar/create
```

Параметры:

В качестве параметром передается название аватара, а так же метрики, по которым аватар будет создаваться:

```json
{
    "name": "avatar name",
    "parameters": {
        "metric": {
            "HIPS": "90",
            "WAIST": "60",
            "HEIGHT": "170",
            "BUST": "90",
            "NECK_CIRCLE": "40",
            "UNDER_BREAST": "70",
            "WEIGHT": "60",
            "GENDER": "male",
        }
    }
}
```

Пример ответа:

```json
{
    "id": 43,
    "ident": "49d57b4c-6aee-4f66-b806-cd15a43ed2be",
    "name": "avatar name",
    "update_time": "2018-01-18 21:55:42",
    "avatar_texture_id": 1,
    "parameters": {
        "metric": {
            "HIPS": "90",
            "WAIST": "60",
            "HEIGHT": "170",
            "BUST": "90",
            "NECK_CIRCLE": "40",
            "UNDER_BREAST": "70",
            "WEIGHT": "60",
            "hair_id": "0",
            "hair_style": "BaletHair",
            "GENDER": "male",
            "eye_id": "0",
            "TEST_KEY": 1,
            "HandsDown": 0.6
        }
    }
}
```


### Обновление аватара

```
[POST] avatar/update/{id}
```

`{id}` - ID аватара

Входные параметры и ответ идентичны тем, что указаны в методе `avatar/create`
