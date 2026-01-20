# online-library-api

Project structure

project-root/
├── docker/
│   ├── php/
│   │   └── Dockerfile
│   └── nginx/
│       └── default.conf
├── docker-compose.yml
├── src/
│   ├── Controller/
│   │   ├── AuthController.php
│   │   ├── BookController.php
│   │   └── FavoriteController.php
│   ├── Entity/
│   │   ├── User.php
│   │   ├── Book.php
│   │   ├── Author.php
│   │   ├── Genre.php
│   │   └── Favorite.php
│   ├── Repository/
│   ├── Security/
│   │   ├── JwtAuthenticator.php
│   │   └── RoleVoter.php
│   ├── Command/
│   │   └── ChangeUserRoleCommand.php
│   └── DataFixtures/
├── migrations/
├── uml/
│   ├── erd.puml
│   ├── class-diagram.puml
│   └── sequence-auth.puml
├── config/
├── README.md
└── composer.json
