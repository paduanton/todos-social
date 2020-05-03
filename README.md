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

## Autenticação

To signup, send a POST request to `/api/auth/signup` with the data:
* nome      | String 
* sobrenome | String
* email     | String (email format)
* password - String
* deficiente boolean

To login send a POST request to `/api/auth/login` with the data:
* email
* password

On success, an API access token will be returned with the type of it and its timing to expire:
```json
{
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ic4ZDAwNG",
    "token_type": "Bearer",
    "expires_at": "2019-12-22 20:50:42"
}
```

All subsequent API requests must include this token in the HTTP header for user identification.
Header key will be `Authorization` with value of 'Bearer' followed by a single space and then token string:
```
Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ic4ZDAwNG
```


## API Documentation
Coming soon...
<!--
To view API documentation, run development server and visit [http://127.0.0.1:8000/docs/](http://127.0.0.1:8000/docs/)
-->
## Links

https://www.getpostman.com/collections/18009794791e5384e19a
<!-- - [API Docs](http://127.0.0.1:8000/docs/) -->
- [Frontend (GitHub)](https://github.com/nataliaPintos/EyeSee)
- [Natália (GitHub)](https://github.com/nataliaPintos)
- [Antonio (GitHub)](https://github.com/paduanton)
