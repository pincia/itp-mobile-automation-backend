<?php
use App\Events\NewDrumData;
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

Route::get('/', function () {
	return view('welcome');
});

Route::get('/products/{stato}/', function ($stato) {
	$query = 'SELECT  l.ID_BOTTALE, f.ID_ODP,b.nome AS NOME_BOTTALE, l.SIGLA,  f.PASSO AS PASSO, f.ID_PRODOTTO, p.PRODOTTO, f.QT_PRODOTTO ,f.ID_ODP_FASI, b.SIGLA AS macchina, f.INIZIO, sum(c.QT_PESATA) as QT_PESATA_,l.stato, p.UNITA,  0 as pronto, p.PRECISIONE, f.PROD_PREPARATO , f.rata , f.ordine_prodotto as ORDINE, f.VERIFICA_PESO AS VERIFICA
		FROM odp_ricetta_fasi f 
		left join odp_ricetta l on f.id_odp=l.id_odp 
		left join prodotti p on p.id_prodotto = f.id_prodotto 
		left join bottali b on b.id_bottale = l.id_bottale 
		left join azioni a on a.id_azione = f.id_azione  
		left join contenitori_fasi c on c.ID_ODP_FASI = f.ID_ODP_FASI
		where f.id_prodotto > 0  
		AND (a.codice_az = 10 OR a.codice_az = 11) 	
		AND l.stato='.$stato.'
		AND not isnull(p.prodotto)
		GROUP BY f.ID_ODP_FASI';

	$products=DB::select($query);
	return json_encode($products);
});




Route::get('/check/',function(){

    $client = new Predis\Client();
    try
    {
    	$redis =  Redis::publish('test-channel', json_encode(['foo' => 'bar']));
        //$client->connect();
    }
    catch (Predis\Connection\ConnectionException $exception)
    {
    	dd($exception);
        exit("whoops, couldn't connect to the remote redis instance!");
    }
dd($redis);
    $client->info();
});


Route::get('/products/{stato}/{bottale}', function ($stato,$bottale) {
	$products=DB::select('SELECT  l.ID_BOTTALE, f.ID_ODP,b.nome AS NOME_BOTTALE, l.SIGLA,  f.PASSO AS PASSO, f.ID_PRODOTTO, p.PRODOTTO, f.QT_PRODOTTO ,f.ID_ODP_FASI, b.SIGLA AS macchina, f.INIZIO, sum(c.QT_PESATA) as QT_PESATA_,l.stato, p.UNITA,  0 as pronto, p.PRECISIONE, f.PROD_PREPARATO , f.rata , f.ordine_prodotto as ORDINE, f.VERIFICA_PESO AS VERIFICA
		FROM odp_ricetta_fasi f 
		left join odp_ricetta l on f.id_odp=l.id_odp 
		left join prodotti p on p.id_prodotto = f.id_prodotto 
		left join bottali b on b.id_bottale = l.id_bottale 
		left join azioni a on a.id_azione = f.id_azione  
		left join contenitori_fasi c on c.ID_ODP_FASI = f.ID_ODP_FASI
		where f.id_prodotto > 0  
		AND (a.codice_az = 10 OR a.codice_az = 11) 	
		AND l.stato='.$stato.'
		AND b.id_bottale='.$bottale.'
		GROUP BY f.ID_ODP_FASI');
	return json_encode($products);
});

Route::get('/products2', function () {
	$products=DB::select('SELECT * from prodotti');
	return json_encode($products);
});

Route::get('/controls/{stato}/', function ($stato) {
	$query = 'SELECT *
		FROM (
		SELECT r.id_odp as odp, o.id_odp_fasi, b.nome as bottale, o.ID_AZIONE_GROUP, o.passo/*,v.valore as descrizione*/, r.passoattivo, o.CONTROLLO_NOTA, b.id_bottale/*.id_azione_set_value, c.data, c.esito as ESITO, c.valore as VALORE, c.note as NOTE */
		FROM odp_ricetta_fasi o
		inner join odp_ricetta r using(id_odp)
		inner join bottali b using (id_bottale)
		#inner join azioni_set_value v using(id_azione_set_value)
		#inner join azioni_set_value_text t on v.id_azione_set_value=t.ID_AZIONE_SET_VALUE
		inner join azioni  a using(id_azione)
		inner join azioni_set s using(id_azione)
		     #left join controlli c using (id_odp_fasi)
		where o.codice_az = 8 AND r.stato='.$stato.' 
		#order by c.data desc
		)
		AS sub
		group by id_odp_fasi';
	
	$controls=DB::select($query);
			return json_encode($controls);
});

Route::get('/products2', function () {
	$products=DB::select('SELECT * from prodotti');
	return json_encode($products);
});


Route::get('/login/{password}/', array('uses' => 'LoginController@doLogin' ));


Route::get('/logincode/{code}/', array('uses' => 'LoginController@doLoginCode' ));

/*AND {stato} 
	
		AND {preparato} 
		AND {idbottale} 
		AND {idodp}
		*/

/*COMUNICAZIONE MODBUS 

$ip = IP del device con le cifre completate a zero e senza punti (es. 192.168.77.12 => '192168077012')
$action = 'R' Read , 'W' Write
$area = D 'DT', R 'Relè'
$data = Dato da scrivere


*/
Route::get('/modbus/{ip}/{action}/{area}/{address}/{data}', array('uses' => 'ModbusController@doAction' ));

Route::get('/getdrumsdata/', array('uses' => 'ModbusController@getDrumsData' ));


Route::get('/gettanksdata/', array('uses' => 'ModbusController@getTanksData' ));
Route::get('/gettankdata/{id}/',['uses' =>'ModbusController@getTankData']); 
Route::get('/getnewalarms',['uses' =>'AlarmsController@getNewAlarms']); 
Route::get('/getmessage', array('uses' => 'AlarmsController@getMessage' ));
Route::get('/getalarms',['uses' =>'AlarmsController@getAlarms']); 
Route::get('/getproducts',['uses' =>'ProductsController@getProducts']); 
Route::get('/getproductscodes',['uses' =>'ProductsController@getProductsCodes']); 
Route::get('/verifyproductintank/{idproduct}/{idtank}/', array('uses' => 'ProductsController@verifyProductInTank'));
Route::get('/getcontrolslist/{azioni}/', array('uses' => 'COntrolsController@getControlsList')); 
Route::post('/upload', 'UploadController@uploadControlImage'); 
Route::get('/getcontrolvalues/{id}',['uses' =>'ControlsController@getControlValues']); 
Route::get('/senddata/',function(){

		$data =[  
		'name'    => 'DRUM1',
        'ODP'   => '1900002',
        'STATUS' => '110000111',
        'url'     => 'PIPPO',
        ];
	event(new NewDrumData($data));
}); 

