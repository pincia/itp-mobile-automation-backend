<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlarmsController extends Controller
{

	

    function getNewAlarms(){

    	$alarms = true;

    	return 0;
    }

function getMessage(){
	$messages =\DB::select('SELECT MESSAGGIO FROM MESSAGGI');
	return $messages;
}
function setMessage(){
	$messages =\DB::select('SELECT MESSAGGIO FROM MESSAGGI');
	return $messages;
}



    function getAlarms(){
    	$alarms = [];
    	$alarm = [];
    	$alarm['NOME_MACCHINA']="Drum D12";
    	$alarm['NOME_ALLARME']="Temperatura Elevata";
    	$alarm['PRIORITA']=3;
    	array_push($alarms, $alarm);
    	$alarm['NOME_MACCHINA']="Drum D8";
    	$alarm['NOME_ALLARME']="INVERTER FAULT";
    	$alarm['PRIORITA']=2;
    	array_push($alarms, $alarm);

    	return $alarms;
    }
}
