# Visão Geral

Todos Social é um pequeno projeto de código aberto que implementa os conceitos básicos de rede e mídia sociais. Foi desenvolvido com PHP utilizando Laravel Framework e banco de dados MySQL com padrão MVC. Esse repositório é a RESTful
API backend somente. O frontend é escrito em Angular 9 e poder ser visto aqui:

[Todos Frontend](https://github.com/paduanton/todos-frontend)

As entidades que esta API possui são: Users, Todos, ProfileImages, TodosImages, Followers e Comments. 

#### Relacionamentos:
- User pode ter N - Todos
- User pode ter N - ProfileImages
- User pode ter N - N Followers
- User pode ter N - Comments (um comentário pertence a um Todos)
- Todos pode ter N - Comments (N - N com User)
- Todos pode ter N - TodosImages
- ProfileImages pode ter 1 - User
- TodosImages pode ter 1 - Todo
- Todo pode ter 1 - User

As funcionalidades que englobam esta API consistem em permitir o usuário a criar um cadastro, se autenticar, criar todos (uma postagem), adicionar imagens a um todos, adicionar imagens de perfil, comentar todos e seguir outros usuários.

Os endpoints disponíveis consistem em atualizar informações em relação a todas entidades. Existem todos endpoints para fazer operações de CRUD seguindo padrão Restful para atualizar informações de todas entidades.

## Modelo ER do banco de dados
![](https://raw.githubusercontent.com/paduanton/todos-social/master/public/ER.png)

## Requisitos de sistema (Mac OS, Windows ou Linux)
* [Docker](https://www.docker.com/get-started)
* [Docker Compose](https://docs.docker.com/compose/install)


## Setup do projeto

Adicione a seguinte linha no arquivo hosts da sua máquina:
```
127.0.0.1       api.todos.social
```

Após clonar o repositório, rode os seguintes comandos no bash dentro do diretório do projeto:


Copiar variáveis de ambiente do projeto:
```
cp .env.example .env
```

Montar e criar ambiente de dev local:
```
 docker-compose up --build
```

Instalar dependências e configurar permissões de diretórios e cache:
```
docker exec -it todosweb /bin/sh bootstrap.sh
```

Para acompanhar as mudanças no banco de dados da API, acesse http://api.todos.social:8181/ no navegador.

#### Autenticação de usuário OAuth2:

Nesta aplicação, através do Laravel foi utilizado autenticação OAuth2 utilizando biblioteca [Passport](https://laravel.com/docs/7.x/passport), então é possível consumir autenticação server side através de um Json Web Token.

#### Observações

No arquivo **./bootstrap.sh** estão todos comandos para configurar o projeto, então para fazer alterações dentro do container é preciso somente rodar o arquivo e setar os comandos nele. 

Após seguir todos passos de setup, o projeto estará operando na porta 80: http://api.todos.social:80. As requisições para a API somente enviam dados e recebem JSON.

## Autenticação

Para **todos** endpoints é necessário fazer requisições com `header Accept:application/json` e `Content-Type:application/json` 

Para cadastrar um usuário, envie uma requisição POST para `/v1/signup` com os dados:
```json
{
    "name": "Antonio de Pádua",
	"email" : "antonio.junior.h@gmail.com",
	"password" : "201125",
	"password_confirmation" : "201125",
	"birthday": "1999/09/22",
	"remember_me": true
}
```
Para autenticar um usuário existente, envie uma requisição POST `/v1/login` com os dados:

Envie com campo **username** ou **email**
```json
{
	"username" : "antonio.padua",
	"password" : "nheac4257",
	"remember_me": false
}
```

Em sucesso, um API access token será retornado com o tipo do token e a expiração dele:
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiL.CJhbGciOiJSUzI1NiIm.p0aSI6Ic4ZDAwNG",
    "token_type": "Bearer",
    "expires_at": "2021-05-02 21:47:23"
}
```

Todas requisições subsequentes **devem incluir esse token no `cabeçalho HTTP` para identificação de usuários**. O indíce do cabeçalho deve ser `Authorization` com o valor **Bearer** seguido de espaço simples com o valor do token:
```
Authorization: Bearer eyJ0eXAiOiJKV1QiL.CJhbGciOiJSUzI1NiIm.p0aSI6Ic4ZDAwNG
```

Para buscar usuário autenticado, envie requisição GET para `/v1/user` somente com cabeçalho de autenticação e será retornado o seguinte response:

```json
HTTP - 200

{
    "id": 6,
    "name": "Antonio de Pádua",
    "username": "antonio.padua",
    "email": "antonio.junior.h@gmail.com",
    "birthday": "1999-09-22",
    "created_at": "2020-05-03T06:17:20.000000Z",
    "updated_at": "2020-05-03T06:17:20.000000Z"
}
```

Para criar um Todo, envie requisição POST para `/v1/users/{userId}/todos` com os dados:

```json
{
	"title": "Uma tarefa",
	"description": "tarefa dos guri",
	"completed": 1
}
```
Para buscar todos os Todos, envie requisição GET para `/v1/todos` e será recebido o response:

```json
HTTP - 200

{
    "data": [
        {
            "id": 4,
            "users_id": 1,
            "title": "Uma tarefa",
            "description": "Lorem ipsum",
            "completed": 1,
            "images": [],
            "comments": [],
            "created_at": "2020-05-03T06:37:29.000000Z",
            "updated_at": "2020-05-03T06:37:29.000000Z"
        },
        {
            "id": 5,
            "users_id": 1,
            "title": "Segunda tarefa",
            "description": "Lorem ipsum 2",
            "completed": 1,
            "images": [],
            "comments": [],
            "created_at": "2020-05-03T06:40:52.000000Z",
            "updated_at": "2020-05-03T06:40:52.000000Z"
        }
    ],
    "links": {
        "first": "http://api.todos.social/v1/todos?page=1",
        "last": "http://api.todos.social/v1/todos?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "path": "http://api.todos.social/v1/todos",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```
- Para páginar passe o argumento **?page=1**
- É possível filtrar Todos através dos atributos **completed** e **title** passando como argumentos: **?completed=1&title=Uma tarefa**

Para atualizar um Todo, envie requisição PUT para `/v1/todos/{todosId}` com os dados:

```json
{
	"title": "Lorem ipsum",
	"description": "É uma description",
	"completed": false
}
```

Para deletar um Todo, envie requisição DELETE para `/v1/todos/{todosId}` e receba o response:

```json
HTTP - 204
```

## Tratamento de responses e erros

Parte dos responses já foram exemplificados, mas aqui será explicado os responses para cada tipo de requisição e os erros para todos eles.

#### HTTP POST

Em caso de sucesso será retornado `HTTP CODE 201 - 200` com body do objeto da requisição

Ex:

```json
{
        "id": 4,
        "users_id": 1,
        "title": "Uma Tarefa",
        "description": "descrição tarefa",
        "completed": 1,
        "images": [],
        "comments": [],
        "created_at": "2020-05-03T06:37:29.000000Z",
        "updated_at": "2020-05-03T06:37:29.000000Z"
}
```

#### HTTP PUT - PATCH

Em caso de sucesso será retornado `HTTP CODE 200` com body do objeto da requisição

```json
{
        "id": 4,
        "users_id": 1,
        "title": "Uma Tarefa",
        "description": "descrição tarefa",
        "completed": 1,
        "images": [],
        "comments": [],
        "created_at": "2020-05-03T06:37:29.000000Z",
        "updated_at": "2020-05-03T06:37:29.000000Z"
}
```

#### HTTP GET

Em caso de sucesso será retornado `HTTP CODE 200` com um body de array de objetos do objeto alvo da requisição ou um body somente o objeto filtrado na requisição feitas (ex: `/v1/todos/{todosId}`).
```json
[
    {
        "id": 4,
        "users_id": 1,
        "title": "Uma Tarefa",
        "description": "descrição tarefa",
        "completed": 1,
        "images": [],
        "comments": [],
        "created_at": "2020-05-03T06:37:29.000000Z",
        "updated_at": "2020-05-03T06:37:29.000000Z"
    }
]
```

#### HTTP DELETE

Em caso de sucesso será retornado `HTTP CODE 204`

### Erros

Caso não possua token no cabeçalho será retornado um html informado exception de Route: login ou na maioria do casos, o seguinte:

```json
HTTP - 401
{
    "message": "Unauthenticated."
}
```

Caso o seu body não esteja formatado corretamente

```json
HTTP - 422
{
    "message": "The given data was invalid.",
    "errors": {
        "completed": [
            "The completed field must be true or false."
        ]
    }
}
```

Caso o servidor não consiga achar informações com a requisição passada 

```json
HTTP - 404
{
    "message": "There is no data",
    "error": "Model not found in the server"
}
```

Caso o servidor não consiga processar sua requisição 

Ex:
```json
HTTP - 400
{
    "message": "could not delete data"
}
```

Caso o servidor gere uma exception que não foi tratada

Ex:
```json
HTTP - 500
{
    "message": "ERROR TO HANDLE REQUEST",
    "error": "xxxxxx",
    "....": "....."
}
```

Lembrando que todas requisições **devem** conter o cabeçalho de autenticação com o token de usuário. Outro ponto a ser levantado é que é usado Soft Deletes para deletar informações, então ao consultar o banco de dados, a coluna **deleted_at** populada corresponde aos dados deletados das entidades.

## Testes Unitários

O código dos testes ficam no diretório /tests e para rodá-los use o comando:

```
docker exec -it todosweb php ./vendor/bin/phpunit
```

## Postman

Se você usa o postman, pode usar o link abaixo para importar uma **Collection** com grande parte das requisições da API. Atualmente o link contém 28 requisições documentadas.

Somente substitua o cabeçalho de autenticação pelo token gerado no seu ambiente local.

https://www.getpostman.com/collections/18009794791e5384e19a
