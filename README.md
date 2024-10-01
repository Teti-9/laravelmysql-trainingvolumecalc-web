## Laravel + MYSQL

Esse é um projeto back-end para calcular o volume de treino semanal. Permite adicionar, procurar, editar ou deletar informações sobre exercícios dentro de uma database mysql. A API é feita usando Laravel e segue um modelo RESTful.

### Instalação
```
git clone https://github.com/Teti-9/laravelmysql-trainingvolumecalc-web.git
cd laravelmysql-trainingvolumecalc-web
composer install
```
```
```
```
Garanta que você tenha uma ferramenta para o banco de dados (xampp como exemplo).
Crie uma database e crie/modifique o arquivo .env com a conexão para o banco de dados.
Execute o comando php artisan migrate:fresh para rodar as migrations.
```
```
Rode a aplicação na pasta raíz do projeto:
php artisan serve
```

### Endpoints
```
```