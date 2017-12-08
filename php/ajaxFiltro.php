<?php
header('Content-Type: text/html; charset=utf-8');
//archivo php que filtra los resultados de escuelas
include('conect.php');

 header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Data-type");
 header('Access-Control-Allow-Origin: *');
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Allow-Methods: *");



$tipoFiltro = $_GET['tipo'];
$parametrosFiltro = $_GET['parametros'];
$parametros = explode("@", $parametrosFiltro);
$query = "";
$cont="";
$where = "";


//filtro por oferta educativa por actividades extra curriculares
//construir los querys para obtener la informacion filtrada
if($tipoFiltro == "ofertaExtraCurr")
{
	$query = "SELECT DISTINCT info.ES_ID, info.CLAVECCT , info.LAT , info.LON , info.TURNO FROM dbo.A_CTBA info 
	JOIN dbo.SCE008_OfertaLista lista on lista.es_id = info.ES_ID
	JOIN dbo.SCA_OfertaEducativa oferta on lista.Idact = oferta.IdAct
	WHERE ";

	$where = "";
	if(count($parametros) == 1)
	{
		$where = " desInfra.DesInfra = '".trim(utf8_decode($parametros[0]))."'";
	}
	else
	{
		for($i = 0 ; $i< count($parametros) ; $i++)
		{
			if($i == count($parametros)-1)
			{
			$where .=" oferta.Descripcion = '".trim(utf8_decode($parametros[$i]))."'";
			}
			else
			{
			$where .=" oferta.Descripcion = '".trim(utf8_decode($parametros[$i]))."' OR";
			}

		}
	}
}

//filtro por oferta educativa por actividades tecnologicas
//construir los querys para obtener la informacion filtrada
if($tipoFiltro == "ofertaTecno")
{
	$query = "SELECT DISTINCT info.ES_ID, info.CLAVECCT , info.LAT , info.LON , info.TURNO FROM dbo.A_CTBA info 
	JOIN dbo.SCE008_OfertaLista lista on lista.es_id = info.ES_ID
	JOIN dbo.SCA_OfertaEducativa oferta on lista.Idact = oferta.IdAct
	WHERE ";

	$where = "";
	if(count($parametros) == 1)
	{
		$where = " desInfra.DesInfra = '".trim(utf8_decode($parametros[0]))."'";
	}
	else
	{
		for($i = 0 ; $i< count($parametros) ; $i++)
		{
			if($i == count($parametros)-1)
			{
			$where .=" oferta.Descripcion = '".trim(utf8_decode($parametros[$i]))."'";
			}
			else
			{
			$where .=" oferta.Descripcion = '".trim(utf8_decode($parametros[$i]))."' OR";
			}

		}
	}
}


//filtro por programas
//construir los querys para obtener la informacion filtrada
if($tipoFiltro == "programas")
{
	$query = "SELECT info.ES_ID, info.CLAVECCT , info.LAT , info.LON , info.TURNO FROM dbo.A_CTBA info
	JOIN dbo.SCE008_Programas listProg on listProg.es_id = info.ES_ID
	JOIN dbo.SCA_Programas progDes on listProg.idProg = progDes.idprog
	WHERE ";

	$where = "";
	if(count($parametros) == 1)
	{
		$where = " desInfra.DesInfra = '".trim(utf8_decode($parametros[0]))."'";
	}
	else
	{
		for($i = 0 ; $i< count($parametros) ; $i++)
		{
			if($i == count($parametros)-1)
			{
			$where .=" progDes.DesProg = '".trim(utf8_decode($parametros[$i]))."'";
			}
			else
			{
			$where .=" progDes.DesProg = '".trim(utf8_decode($parametros[$i]))."' OR";
			}

		}
	}
}


//filtro por infraestructura
//construir los querys para obtener la informacion filtrada
if($tipoFiltro == "infraestructura")
{
	$query = "SELECT DISTINCT info.ES_ID, info.CLAVECCT , info.LAT , info.LON , info.TURNO FROM dbo.A_CTBA info 
	JOIN dbo.SCE008_Infraestructura infra on info.ES_ID = infra.es_id
	JOIN dbo.SCA_Infraestructura desInfra on infra.idInfra = desInfra.IdInfra
	WHERE ";

	$where = "";
	if(count($parametros) == 1)
	{
		$where = " desInfra.DesInfra = '".trim(utf8_decode($parametros[0]))."' COLLATE Latin1_General_CS_AI
";
	}
	else
	{
		for($i = 0 ; $i< count($parametros) ; $i++)
		{
			if($i == count($parametros)-1)
			{
			$where .=" desInfra.DesInfra = '".trim(utf8_decode($parametros[$i]))."' COLLATE Latin1_General_CS_AI
";
			}
			else
			{
			$where .=" desInfra.DesInfra = '".trim(utf8_decode($parametros[$i]))."' OR";
			}

		}
	}
}

$query .= $where;

$params = array();
$options = array("Scrollable" =>SQLSRV_CURSOR_KEYSET);

//ejecutar querys
$result = sqlsrv_query($con , $query , $params , $options);
$row_count = sqlsrv_num_rows($result);
$contador = 1;

//construir JSON con los resultados de la consulta
echo '[';
 while($obj = sqlsrv_fetch_object($result))
 {
 	if($row_count === false){}
 	else
 	{
	 	echo '{';
	    echo '"clave" : "'.utf8_encode($obj->CLAVECCT).'" ,';
	    echo '"lat" : "'.$obj->LAT.'" ,';
	    echo '"lng" : "'.$obj->LON.'" , ';
        echo '"turno" : "'.$obj->TURNO.'"';
	    if($contador === $row_count){
	    	echo '}';
	    }
	    else{
	    	echo '},';
	    }
 	}
 	$contador ++; 
 }
 echo "]";

?>