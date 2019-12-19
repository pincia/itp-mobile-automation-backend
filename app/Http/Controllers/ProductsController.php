<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductsController extends Controller
{
     function getProducts(){
        
        $products =\DB::select('SELECT * FROM  prodotti');
        
        return $products;
        
        
    }

    function verifyProductInTank($idproduct, $idtank){
       
        $products =\DB::select('SELECT * FROM  quadri_pompe_prodotti where id_prodotto = '.$idproduct.' and id_deposito = '.$idtank);
        if  (count($products)>0) return 1;
        else return 0;
         
    }

     function getProductsCodes(){
        
        $products =\DB::select('SELECT * FROM  prodotti inner join prodotti_fornitore using (id_prodotto)');
        
        return $products;
        
        
    }
    
}
