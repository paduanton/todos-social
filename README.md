# Visão Geral

Todos Social é um pequeno projeto de código aberto que implementa os conceitos básicos de rede e mídia sociais. Foi desenvolvido com PHP utilizando Laravel Framework. Esse repositório é a RESTful
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

Dentro do diretório do repositório, roda os seguintes comandos no bash.

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

Em sucesso, um API access token será retornado com o tipo do token e a expiração delete:
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiL.CJhbGciOiJSUzI1NiIm.p0aSI6Ic4ZDAwNG",
    "token_type": "Bearer",
    "expires_at": "2021-05-02 21:47:23"
}
```

Todas requisições subsequentes **devem incluir esse token no `cabeçalho HTTP` para identificação de usuários**. O indíce do cabeçalho deve ser `Authorization` com o valor **Bearer** seguido de espaço simples com o valor do token:
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ic4ZDAwNG
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

## Tratamento de erros

Lembrando que todas requisições **devem** conter o cabeçalho de autenticação com o token de usuário.

## POSTMAN

Se você usa o postman, pode usar o link abaixo para importar uma **Collection** com grande parte das requisições da API. Atualmente o link contém 28 requisições documentadas.

https://www.getpostman.com/collections/18009794791e5384e19a
