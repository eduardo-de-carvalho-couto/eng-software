## Passo a passo para utilizar esse ambiente Laravel

#### Instale o docker

https://docs.docker.com/engine/install/

```sh
cd example-app/
```

Crie o Arquivo .env
```sh
cp .env.example .env
```

**Se preferir**, atualize essas variáveis de ambiente no arquivo .env
```dosini
APP_NAME="App Name"
APP_URL=http://localhost:80

DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=nome_que_desejar_db
DB_USERNAME=nome_usuario
DB_PASSWORD=senha_aqui

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

REDIS_HOST=redis
REDIS_PASSWORD=null
REDIS_PORT=6379
```


Suba os containers do projeto
```sh
docker-compose up -d
```


Acesse o container
```sh
docker-compose exec app bash
```


Instale as dependências do projeto
```sh
composer install
```


Gere a key do projeto Laravel
```sh
php artisan key:generate
```

Rode as migrations
```sh
php artisan migrate
```

Se der algum problema quando tentar rodar as migrations, tente isso e depois rode as migrations novamente
```sh
php artisan config:clear
```


Acesse o projeto
[http://localhost:80](http://localhost:80)
