<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class UploadController extends Controller
{
    
     function uploadControlImage(Request $request){
      	  \Log::info($request);
      	  \Log::info($request->has('file'));
      	  $blob = Input::get('images[]');
      	  $name = $request->file('file')->getClientOriginalName();
      	  $explode = explode("_",$name);
      	  $name = $explode[0]."/".$explode[1]."/".$explode[2]."/";
      	  //$name= str_replace("_","/",$name);
      	  $blob = $request->file('file')->storeAs($name,"foto_".$explode[3].".jpg");
      	    \Log::info($blob);
		//return $blob;
			//file_put_contents(public_path('a.jpg'), $blob);
     	  $data = ['success' => true, 'message' => 'Upload and move success'];
   		  echo json_encode( $data );
        
    }
}
