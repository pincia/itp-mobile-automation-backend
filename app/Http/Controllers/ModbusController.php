<?php

namespace App\Http\Controllers;
#require_once dirname(__FILE__) . '/../../Libraries/Phpmodbus/ModbusMaster.php';
use Illuminate\Http\Request;
use App\Libraries\Phpmodbus\ModbusMaster;

class ModbusController extends Controller
{

    function doAction($ip,$action,$area,$address,$data){
    $ip = substr($ip,0,3).".".substr($ip,3,3).".".substr($ip,6,3).".".substr($ip,9,3);
    $ip =  str_replace(".0",".",$ip);
    // Create Modbus object
    $modbus = new \ModbusMaster($ip, "TCP");
    switch($action){
        case 'R':
            switch($area){
                case 'D':
                    $recData = $modbus->readMultipleRegisters(0, $address, 1);
                    
                    return $recData[0]*256+$recData[1];
                    break;
                case 'R':
                    $addr = (int)substr($address,0,strlen($address)-1)*16 + hexdec(substr($address,strlen($address)-1,strlen($address))) ;
                    $recData = $modbus->readCoils(0, $addr, 1);
                     return ($recData[0] == 'true') ? 1 : 0;
                    break;
                default:
                    return "KO" ;
                    break;
                }
            break;
        case 'W':
            switch($area){
                case 'D':
                    $modbus->writeMultipleRegister(0, $address, [$data], "INT");
                    return "OK" ;
                    break;
                case 'R':
                    $addr = (int)substr($address,0,strlen($address)-1)*16 + hexdec(substr($address,strlen($address)-1,strlen($address))) ;
                    $modbus->writeMultipleCoils(0, $addr,[$data]);
                    return "OK" ;
                    break;

                default:
                    return "KO" ;
                    break;
                }
            break;
        default:
             return "KO" ;
    }
try {
    // FC 3
    $recData = $modbus->readMultipleRegisters(0, 2116, 1);
}
catch (Exception $e) {
    // Print error information if any
    echo $modbus;
    echo $e;
    exit;
}

// Print status information
return 'pippo';
    }





    function getDrumsData(){   
        
        $query = 'SELECT * from bottali where abilitato="1"';
        $drums =\DB::select($query);
        $address = 500;
        $data = [];
        $i = 0;
      //  var_dump( $drums);
      /*
        foreach ($drums as $drum) {
    
            
            $ip = $drum->PLC_IPADDR;
            $modbus = new \ModbusMaster($ip, "TCP");           
            $regs = $modbus->readMultipleRegisters(1, $address, 120);

           $drum_data['NOME']=$drum->NOME;
           $drum_data['ID_ODP'] =  $regs[2]*256+$regs[3];
           $drum_data['PASSO'] =  $regs[4]*256+$regs[5];
           $drum_data['TEMP'] =  $regs[12]*256+$regs[13];
           $drum_data['TEMP_SET'] =  $regs[18]*256+$regs[19];
           $drum_data['RPM'] =  $regs[10]*256+$regs[11];
           $infostato1 = decbin( $regs[38]*256+$regs[39]);
           $infostato2=  decbin( $regs[40]*256+$regs[41]);
           $drum_data['RUNNING'] = (int)substr( $infostato1,-3,-2);
           $drum_data['HEATING'] = (int)substr( $infostato1,-7,-6);
           $data[$i]= $drum_data;
           $i = $i+1;
           /*
           echo $infostato1;
           echo "<br>";
           echo $drum_data['RUNNING'];*/


        //PROVAAAAAAAAAAAAAAAAAAAAAAAAAAAA
           for($i=0;$i<9;$i++){
           $drum_data['ID']=($i+1);
           $drum_data['NOME']="D0".($i+1);
           $drum_data['ID_ODP'] =  1900000+rand(0,100);
           $drum_data['PASSO'] =  rand(0,60);
           $drum_data['TEMP'] = rand(0,550)/10;
           $drum_data['TEMP_SET'] = rand(0,65);
           $drum_data['RPM'] =  rand(0,100)/10;
           $drum_data['DIRECTION'] = rand(0,1);
           $drum_data['RUNNING'] = rand(0,1);
           $drum_data['HEATING'] = rand(0,1);
           $drum_data['RED'] = rand(0,1);
           $drum_data['YELLOW'] = rand(0,1);
           $drum_data['GREEN'] = rand(0,1);
           $data[$i]= $drum_data;
          }

                return $data;
}
    
    function getTanksData(){
        $tanks =\DB::select('SELECT d.ID_DEPOSITO, d.NOME, d.SIGLA,q.PLC_IPADDR FROM  depositi d inner join quadri_pompe_prodotti using (id_deposito) inner join quadri_pompe q using (id_quadro)');

        $address = 500;
        $data = [];
        $i = 0;
        foreach ($tanks as $tank) {
            $ip = $tank->PLC_IPADDR; 
            $tank_data['ID']=$tank->ID_DEPOSITO;
            $tank_data['NOME']=$tank->NOME;
            $tank_data['SIGLA']=$tank->SIGLA;
            $tank_data['RECYCLE']=rand(0,1);
            $tank_data['FILLING']=rand(0,1);
            $tank_data['MIXING']=rand(0,1);
            $tank_data['PERCENTAGE']=rand(0,100);
            
             $data[$i]= $tank_data;
           $i = $i +1;
           /*
           echo $infostato1;
           echo "<br>";
           echo $drum_data['RUNNING'];*/
}
        return $data;
        
        
    }
     function getTankData(Request $request, $id){

        
        $tank =\DB::select('SELECT d.ID_DEPOSITO, d.NOME, q.PLC_IPADDR FROM  depositi d inner join quadri_pompe_prodotti using (id_deposito) inner join quadri_pompe q using (id_quadro) where d.ID_DEPOSITO = "'.$id.'"');
        $address = 500;
        $data = [];
        $i = 0;
       
        $ip = $tank[0]->PLC_IPADDR; 
        $tank_data['ID']=$tank[0]->ID_DEPOSITO;
        $tank_data['NOME']=$tank[0]->NOME;
        //var_dump( $tanks);
        /*
        foreach ($tanks as $tank) {
            $ip = $tank->PLC_IPADDR; 
            $tank_data['ID']=$tank->ID_DEPOSITO;
            $tank_data['NOME']=$tank->NOME;
            
             $data[$i]= $tank_data;
           $i = $i +1;
           /*
           echo $infostato1;
           echo "<br>";
           echo $drum_data['RUNNING      
        }*/
        return $tank_data;
        
        
    }

    function wordToInt($addr){
        $recData[a]*256+$recData[1];
    }
    
}
