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

configure .env with working credentials
```
php artisan migrate
```

### Setup Encryption Password

browser to project url eg:
(code at PROJECT_ROOT/routes/web.php)

1) localhost/01
- make sure get the message below, then go to next step
```
"Default DB Setup Connected"
```

2) localhost/02
- configure accordingly until you see message below, then go to next step
```
Good to go!!
```

3) localhost/03
- configure accordingly until you see message below, then go to next step
```
Good to go!!
```

4) localhost/04
- if Decrypted match?: has value below it means its working, do [a],[b]
[a] at PROJECT_ROOT/.env , replace DB_PASSWORD with new encrypted value 
```
DB_PASSWORD=<encrypted value>
```

[b] at PROJECT_ROOT/config/database.php, replace entire 'password' line of code below code

REPLACE VALUE FROM:
```
'password' => env('DB_PASSWORD', ''),
```
TO VALUE:
```
'password' => openssl_decrypt(env('DB_PASSWORD'), 'aes-256-cbc', file_get_contents(env('DB_ENCRYPTION_KEY_PATH')), 0, base64_decode(base64_encode(env('DB_IV')))),
```

1) localhost/05
- configure until see the message below
```
"NEW DB Setup Connected, Good to Go!!"
```