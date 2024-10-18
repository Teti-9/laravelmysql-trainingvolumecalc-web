## Laravel + MYSQL

Esse é um projeto back-end para calcular o volume de treino semanal. Permite adicionar, procurar, editar ou deletar informações sobre exercícios dentro de uma database mysql. A API é feita usando Laravel e segue um modelo RESTful.

### Instalação Local
```
git clone https://github.com/Teti-9/laravelmysql-trainingvolumecalc-web.git
cd laravelmysql-trainingvolumecalc-web
composer install

Garanta que você tenha uma ferramenta para o banco de dados (xampp por exemplo).
Crie uma database e copie/modifique o arquivo .env.
Execute o comando php artisan migrate:fresh para rodar as migrations.

- Rode a aplicação na pasta raíz do projeto:
php artisan serve
```
### Instalação Docker
```
git clone https://github.com/Teti-9/laravelmysql-trainingvolumecalc-web.git
cd laravelmysql-trainingvolumecalc-web

- Edite o arquivo .env :

DB_CONNECTION=pgsql
DB_HOST=postgresql
DB_PORT=5432
DB_DATABASE=training_volume_calc
DB_USERNAME=postgres
DB_PASSWORD=123456

- Adicione em algum local de seu .env : 
POSTGRESQL_VERSION=17
POSTGRESQL_PASS=123456

- Rode o comando:
docker compose up -d

```
### Back-end:
```
bootstrap/app.php
routes/api.php
app/Http/Controllers/VolumeController.php
app/Http/Controllers/AuthController.php
```

### Endpoints
