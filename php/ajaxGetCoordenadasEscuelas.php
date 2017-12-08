<?php
include('conect.php');


 header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Data-type");
 header('Access-Control-Allow-Origin: *');
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Allow-Methods: *");




//query para obtener la informacion de la escuela
$query = "SELECT * FROM dbo.A_CTBA";
$contador = "  SELECT count(*) AS contador FROM dbo.A_CTBA";
//echo $query;
$result = sqlsrv_query($con , $query);
$cont = sqlsrv_fetch_object(sqlsrv_query($con , $contador));
$i =0;


//contruir el JSON con los resultados de la consulta
	echo '[';
     while($obj = sqlsrv_fetch_object($result))
     {
     	echo '{';
        echo '"clave" : "'.utf8_encode($obj->CLAVECCT).'" ,';
        //echo '"nombre" : "'.utf8_encode($obj->NOMBRECT).'" ,';
        echo '"lat" : "'.$obj->LAT.'" ,';
        echo '"lng" : "'.$obj->LON.'" ,';
        echo '"turno" : "'.$obj->TURNO.'"';
        //echo '},';
 		$i++;
        if($i >= $cont->contador){
        	echo '}';
        }
        else{
        	echo '},';
        }
       
     }
     echo "]";
?>