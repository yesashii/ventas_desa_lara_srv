<?php


/*******************************/
/**         LOGIN            **/
/*****************************/

// rutas de logeo
Route::get('/login', 'Login\LoginController@index')->name('login')->name('login');
Route::get('/auth/login', 'Login\LoginController@index')->name('login')->name('login');
Route::post('/login', 'Login\LoginController@verificaCredenciales')->name('login');

// rutas de reseteo de contraseña
Route::get('password/email', 'Login\ClaveController@index');
Route::post('password/email', 'Login\ClaveController@postEmail');

//rutas de la ventana de reseteo
Route::get('password/reset', 'Login\ClaveController@reset');
Route::post('password/reset', 'Login\ClaveController@passwordReset');


route::get('/logout', 'Login\LoginController@logout')->name('logout');

/*******************************/

Route::get( '/enviocorreo',function (){

    return view('Login.mensajes.envio_correo');
} );


Route::group(['middleware' => 'auth'], function(){


    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('/home', function () {
        return view('welcome');
    })->name('home');

    /*
    |--------------------------------------------------------------------------
    | PEDIDOS
    |--------------------------------------------------------------------------
    */
    Route::get('/estadopedidos/{fecha}', 'pedido\pedidoController@estadoPedidos')->name('estadopedidos');
    Route::get('/estadopedidos', 'pedido\pedidoController@estadoPedidos')->name('estadopedidos');
    Route::post('/estadopedidos', 'pedido\pedidoController@buscarPorFecha')->name('estadopedidos');

    //buscaNota
    Route::get('/buscanota/{pedido}', 'pedido\pedidoController@buscaNota')->name('buscanota');

});






