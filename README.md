# avoid-db-credential-plain-text
encrypt decrypt db password using native php functions
-base64_encode
-openssl_encrypt
-openssl_decrypt

### Setup Laravel
```
git clone git clone https://github.com/alexteh/avoid-db-credential-plain-text.git encrypt_psw
cd encrypt_psw
composer install
php artisan key:generate
```

1) configure .env with working credentials
```
php artisan migrate
```
### Setup Encryption Password