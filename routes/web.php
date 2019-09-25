<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/01', function () {
    
    try {
        DB::connection()->getPdo();
        dd('Default DB Setup Connected');
    } catch (\Exception $e) {
        dd("Default DB Setup Not Connected.  Please check your configuration. error:");
        // die("Could not connect to the database.  Please check your configuration. error:" . $e );
    }    
});   

Route::get('/02', function () {
    $header = '.env checking...';
    $header .= '<br><br>create an encryption key file at your desire location <br><br>eg: /usr/local/keyfile ';
    $header .= '<br>edit the file above with random strings, that will be use for encryption key';
    $return_val ='';
    if(!file_exists(env('DB_ENCRYPTION_KEY_PATH'))){
        $return_val.='<br><br>need to add new variable at .env DB_ENCRYPTION_KEY_PATH eg: <br>DB_ENCRYPTION_KEY_PATH=/usr/local/keyfile';
    }

    if(!strlen(env('DB_IV')) == 16){
        $return_val.='<br><br>need to add new variable at .env DB_IV  must be 16 random alphanumeric character eg: <br>DB_IV=12121212342fsdsrf';
    }
    
    return (empty($return_val))?$header.'<br><br>all .env variable are correctly set<br><br>Good to go!!':$header.$return_val;
});

Route::get('/03', function () {
    $return_val = 'check DB_ENCRYPTION_KEY_PATH as valid path, valid file, valid value<br><br>';
    $return_val .= file_exists(env('DB_ENCRYPTION_KEY_PATH'))?'<br>'.env('DB_ENCRYPTION_KEY_PATH').' has valid value shown below: <br>'.file_get_contents(env('DB_ENCRYPTION_KEY_PATH')).'<br><br>Good to go!!':'ERROR! <br><br>'.env('DB_ENCRYPTION_KEY_PATH').' has wrong path or file not exist';
    return $return_val;

});

Route::get('/04', function () {
    $crypt = 'aes-256-cbc';

    $return_val = 'GENERATE ENCRYPTION';
    
    $encryption_key = file_exists(env('DB_ENCRYPTION_KEY_PATH'))?file_get_contents(env('DB_ENCRYPTION_KEY_PATH')):'';

    $iv = env('DB_IV');//openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
    $data = env('DB_PASSWORD');
    $return_val .= "<br><br>DB_PASSWORD Before encryption:<br>$data";
    $encrypted = openssl_encrypt($data, $crypt, $encryption_key, 0, $iv);

    $return_val .= "<br><br>Encrypted key DB_ENCRYPTION_KEY_PATH path:<br>".env('DB_ENCRYPTION_KEY_PATH');
    $return_val .= "<br><br>Encrypted key DB_ENCRYPTION_KEY_PATH's value:<br>$encryption_key";
    $return_val .= "<br><br>Encrypted iv DB_IV:<br>$iv";
    $return_val .= "<br><br>set new value for DB_PASSWORD as stated below [a]:<br>$encrypted";

    $encrypted = $encrypted . ':' . base64_encode($iv);
    $parts = explode(':', $encrypted);
    $decrypted = openssl_decrypt($parts[0], $crypt, $encryption_key, 0, base64_decode($parts[1]));
    $return_val .= "<br><br>Decrypted match?:<br>$decrypted";
    $return_val .= "<br><br> if decrypt match replace value at PROJECT_ROOT/config/database.php";
    $return_val .= "<br><br> REPLACE VALUE FROM:";
    $return_val .= "<br><br> 'password' => env('DB_PASSWORD', ''),";
    $return_val .= "<br><br> TO THIS[b]:";
    $return_val .= "<br><br>'password' => openssl_decrypt(env('DB_PASSWORD'), 'aes-256-cbc', file_get_contents(env('DB_ENCRYPTION_KEY_PATH')), 0, base64_decode(base64_encode(env('DB_IV')))),";
    return $return_val;
});


Route::get('/05', function () {
    
    try {
        DB::connection()->getPdo();
        dd('NEW DB Setup Connected, Good to Go!!');
    } catch (\Exception $e) {
        echo "ERROR <br>NEW DB Setup Not Connected.<br><br>Please check your new DB_PASSWORD is being set at configuration at .env <br> also review all steps at 04";
        // die("Could not connect to the database.  Please check your configuration. error:" . $e );
    }    
});



Route::get('/dec_test', function () {
    return openssl_decrypt(env('DB_PASSWORD'), 'aes-256-cbc', file_get_contents(env('DB_ENCRYPTION_KEY_PATH')), 0, base64_decode(base64_encode(env('DB_IV'))));
});   

Route::get('/dec', function () {
    $crypt = 'aes-256-cbc';

    $encryption_key = file_exists(env('DB_ENCRYPTION_KEY_PATH'))?file_get_contents(env('DB_ENCRYPTION_KEY_PATH')):'';
    $iv = env('DB_IV');
    
    $return_val = '';
    $return_val .= "<br><br>Encrypted key:<br>$encryption_key";
    $return_val .= "<br><br>Encrypted iv:<br>$iv";
    
    $encrypted = env('DB_PASSWORD') . ':' . base64_encode($iv);
    $parts = explode(':', $encrypted);
    $decrypted = openssl_decrypt($parts[0], $crypt, $encryption_key, 0, base64_decode($parts[1]));
    $return_val .= "<br><br>Decrypted:<br>$decrypted";
    return $return_val;
});

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
