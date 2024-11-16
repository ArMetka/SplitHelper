# Split Helper

### Work in progress (ignore some shitty code)

## Production

[not yet]

## Development

```bash
cd ./docker
docker-compose up -d --build
```

### Требуемый функционал

#### Уровень сплитов
- Выбор(просмотр) сплита
- Создание сплита

#### Добавление/редактирование/удаление позиции из чека
- Название
- ?Количество
- Стоимость за штуку
- ?Модификаторы стоимости

#### Добавление/редактирование/удаление клиента
- Имя

#### Таблица со всеми товарами и суммами с возможностью отметится
- idk

### Cool features
- ?oauth гугла/яндекса прикрутить

### Tables

#### users
- id
- ?displayed name
- username
- ?email
- password
- created at
- updated at

```postgresql
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    displayed_name VARCHAR(40),
    username VARCHAR(40) UNIQUE NOT NULL,
    email VARCHAR(64) UNIQUE,
    password VARCHAR(256) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL
);
```

#### splits
- id
- title
- is public
- owner id
- ?viewer ids[]
- ?editor ids[]
- items table name
- created at
- updated at

```postgresql
CREATE TABLE splits (
    id VARCHAR(11) PRIMARY KEY,
    title VARCHAR(40) NOT NULL,
    is_public BOOLEAN NOT NULL,
    owner_id INT NOT NULL,
    viewer_ids INT[] NOT NULL,
    editor_ids INT[] NOT NULL,
    items_table_name VARCHAR(17) NOT NULL,
    created_at TIMESTAMP NOT NULL,
    updated_at TIMESTAMP NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES users (id)
);
```

#### items_{Split.id}
- id
- name
- base price
- price modifier
- modified price

```postgresql
CREATE TABLE items_TEST123TEST (
    id SERIAL PRIMARY KEY,
    name VARCHAR(40) NOT NULL,
    base_price REAL NOT NULL,
    price_modifier REAL NOT NULL,
    modified_price REAL NOT NULL
);
```

#### clients_{Split.id}
- id
- user id
- ?item ids[]

```postgresql
CREATE TABLE clients_TEST123TEST (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL,
    item_ids INT[] NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users (id)
);
```
