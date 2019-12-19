<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ControlsController extends Controller
{

    function getControlsList($str){
    $azioni = explode("X",$str);
	$controls = [];

    foreach ($azioni as $idazione){
 	  $control =\DB::select('SELECT a.VALORE as NOME,  t.VALORE , a.strumento, a.numerico FROM azioni_set_value a inner join azioni_set_value_text t using(id_azione_set_value) where ID_AZIONE_SET_VALUE ='.$idazione);
 	  $cont = $control[0];
 	  $cont->IDAZIONE = $idazione;
 	  $cont->CHECKED=false;
     array_push($controls, $cont);
     } 

   
     return $controls;

   }
   function getControlValues($id){
  $controls =\DB::select('SELECT a.VALORE , a.numero  FROM azioni_set_value_text a  where ID_AZIONE_SET_VALUE ='.$id);
 $values=[];
   foreach ($controls as $control){
array_push($values, $control);
}

  return json_encode($values);
   }
   



        
        
    
}
