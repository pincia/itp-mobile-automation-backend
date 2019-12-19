<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
   public function doLogin($password){
 
   $pieces = explode("@", $password);
   $query = 'SELECT * from utenti where utente ="'.$pieces[0].'" and password="'.$pieces[1].'"';
   $users=\DB::select($query);

   echo count($users);
	

   }
    public function doLoginCode($code){
   $users=\DB::select('SELECT * from utenti where code ="'.$code.'"');
   echo count($users);
   }
		
}
