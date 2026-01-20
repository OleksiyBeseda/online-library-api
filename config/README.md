## Генерация ключей для JWT

В Docker или локально:

mkdir -p config/jwt
# Создаем приватный ключ
openssl genrsa -out config/jwt/private.pem -aes256 4096
# Создаем публичный ключ
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem


Когда создаешь приватный ключ, введи пароль, такой же как в pass_phrase.