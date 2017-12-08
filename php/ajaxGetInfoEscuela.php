<?php
//php que obtiene la informacionde la escuela segun su clave

include('conect.php');
//obtener la clave


 header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Data-type");
 header('Access-Control-Allow-Origin: *');
 header("Access-Control-Allow-Credentials: true");
 header("Access-Control-Allow-Methods: *");





$clave = $_POST['clave'];
$turno = $_POST['turno'];

//$clave = '22DST0025C';
//$turno = 'MAT';


$params = array();
$options = array("Scrollable" =>SQLSRV_CURSOR_KEYSET);

//query para obtener la informacion de la escuela
$queryInfoEscuela = "SELECT * FROM dbo.A_CTBA WHERE CLAVECCT = '{$clave}' AND TURNO = '{$turno}'";
$infoEscuela = sqlsrv_fetch_object(sqlsrv_query($con , $queryInfoEscuela));

$claveEscuela = $infoEscuela->ES_ID;

//query para obtener la oferta educativa que tiene la escuela
$queryOfertaEducativa = "SELECT listaOferta.es_id , oferta.Descripcion , cat.Descripcion AS desCat, subCat.Descripcion AS desSubCat FROM dbo.SCE008_OfertaLista listaOferta 
    JOIN dbo.SCA_OfertaEducativa oferta on listaOferta.Idact = oferta.IdAct
    JOIN dbo.SCA_Categoria cat on oferta.IdCat = cat.IdCat
    JOIN dbo.SCA_SubCategoria subCat on oferta.IdSubCat = subCat.IdSubCat
WHERE listaOferta.es_id =".$claveEscuela;
$ofertaEducativa = sqlsrv_query($con , $queryOfertaEducativa , $params , $options);

//query para obtener la infraestructura que tiene la escuela
$queryInfraestructura = "SELECT listaInfra.* , infra.* FROM dbo.SCE008_Infraestructura listaInfra
    JOIN dbo.SCA_Infraestructura  infra on listaInfra.idInfra = infra.IdInfra
WHERE  listaInfra.es_id =".$claveEscuela;
$infraestructura = sqlsrv_query($con , $queryInfraestructura , $params , $options);

//query para obtener los programas que tiene la escuela
$queryProgramas = "SELECT listaProg.* , prog.* FROM dbo.SCE008_Programas listaProg
    JOIN dbo.SCA_Programas prog on listaProg.idProg = prog.idprog
WHERe listaProg.es_id =".$claveEscuela;
$programas = sqlsrv_query($con , $queryProgramas , $params , $options);

//query para obtener la informacion faltante de la escuela
$queryInfoFaltante = "SELECT * FROM dbo.SCE008_Oferta WHERE es_id =".$claveEscuela;
$infoFaltante = sqlsrv_query($con , $queryInfoFaltante , $params , $options);


$arrayOfertaEducativa = "";
$arrayInfraestructura = "";
$arrayProgramas = "";
$arrayInfoFaltante = "";


//ejecutar query y guardar los resultados en un JSON
$row_count = sqlsrv_num_rows($ofertaEducativa);
$contador = 1;
while($obj = sqlsrv_fetch_object($ofertaEducativa))
{
    if($row_count === false){}
    else
    { 
        //echo $row_count."<br>";
        if($contador === $row_count)
        {
            $json = '{
             "categoria" :"'.trim(utf8_encode($obj->desCat)).'",
             "subCategoria" :"'.trim(utf8_encode($obj->desSubCat)).'",
             "descripcion" :"'.trim(utf8_encode($obj->Descripcion)).'"
            }';
            $arrayOfertaEducativa =  $arrayOfertaEducativa.$json;
        }
        else
        {
            $json = '{
             "categoria" :"'.trim(utf8_encode($obj->desCat)).'",
             "subCategoria" :"'.trim(utf8_encode($obj->desSubCat)).'",
             "descripcion" :"'.trim(utf8_encode($obj->Descripcion)).'"
            },';
            $arrayOfertaEducativa =  $arrayOfertaEducativa.$json;   
        }
    }
    $contador++;
}

//ejecutar query y guardar los resultados en un JSON
$row_count = sqlsrv_num_rows($infraestructura);
$contador = 1;
 while($obj = sqlsrv_fetch_object($infraestructura))
 {
    if($row_count === false){}
    else
    { 
        if($contador === $row_count)
        {
           $json = '{
            "es_id" : "'.$obj->es_id.'",
            "desInfra" : "'.trim(utf8_encode($obj->DesInfra)).'"
           }';
            $arrayInfraestructura =  $arrayInfraestructura.$json;
        }
        else
        {
           $json = '{
                "es_id" : "'.$obj->es_id.'",
                "desInfra" : "'.trim(utf8_encode($obj->DesInfra)).'"
                  },';
                $arrayInfraestructura =  $arrayInfraestructura.$json;
        }
    }
    $contador++;
 }



//ejecutar query y guardar los resultados en un JSON
$row_count = sqlsrv_num_rows($programas);
$contador = 1;
 while($obj = sqlsrv_fetch_object($programas))
 {
    if($row_count === false){}
    else
    { 
        if($contador === $row_count)
        {
           $json = '{
            "es_id" : "'.trim(utf8_encode($obj->es_id)).'",
            "desProg" : "'.trim(utf8_encode($obj->DesProg)).'"
             }';
             $arrayProgramas = $arrayProgramas.$json;
        }
        else
        {
            $json = '{
            "es_id" : "'.trim(utf8_encode($obj->es_id)).'",
            "desProg" : "'.trim(utf8_encode($obj->DesProg)).'"
             },';
             $arrayProgramas = $arrayProgramas.$json;          
        }
    }
    $contador++;
 }


//ejecutar query y guardar los resultados en un JSON
 while($obj =sqlsrv_fetch_object($infoFaltante))
 {
    $json = '{
        "es_id" : "'.$obj->es_id.'",
        "actividades" : "'.utf8_encode($obj->Actividades).'",
        "otrosProg" : "'.utf8_encode($obj->OtrosProg).'",
        "otrasInfra" : "'.utf8_encode($obj->OtrasInfra).'",
        "otrosApoyos" : "'.utf8_encode($obj->OtrosApoyos).'",
        "nombreResponsable" : "'.utf8_encode($obj->RespondeNombre).'",
        "cargoResponsable" : "'.utf8_encode($obj->RespondeCargo).'",
        "nombreResponsable2" : "'.utf8_encode($obj->RespondeNombre2).'",
        "cargoResponsable2" : "'.utf8_encode($obj->RespondeCargo2).'",
        "nombreResponsable3" : "'.utf8_encode($obj->RespondeNombre3).'",
        "cargoResponsable3" : "'.utf8_encode($obj->RespondeCargo3).'",
        "nombreResponsable3" : "'.utf8_encode($obj->RespondeNombre4).'",
        "cargoResponsable3" : "'.utf8_encode($obj->RespondeCargo4).'",

        "logro1" : "'.utf8_encode($obj->Logro1).'",
        "ciclo1" : "'.$obj->ciclo1.'",
        "descripcion1" : "'.utf8_encode($obj->Descrip1).'",

        "logro2" : "'.utf8_encode($obj->Logro2).'",
        "ciclo2" : "'.$obj->ciclo2.'",
        "descripcion2" : "'.utf8_encode($obj->Descrip2).'",

        "logro3" : "'.utf8_encode($obj->Logro3).'",
        "ciclo3" : "'.$obj->ciclo3.'",
        "descripcion3" : "'.utf8_encode($obj->Descrip3).'",

        "otras1" : "'.$obj->otras1.'",
        "otras2" : "'.$obj->otras2.'",
        "otras3" : "'.$obj->otras3.'",
        "otras4" : "'.$obj->otras4.'",
        "otras5" : "'.$obj->otras5.'",
        "otras6" : "'.$obj->otras6.'",
        "otras7" : "'.$obj->otras7.'",
        "otras8" : "'.$obj->otras8.'",
        "otras9" : "'.$obj->otras9.'",
    
        "fecha" : "'.$obj->FechaActualizacion->format('d/m/Y').'"
    }';
    $arrayInfoFaltante = $arrayInfoFaltante."".$json."";
 }

//construir un JSON con la informacion obtenida
$datosJson = '{

    "infoEscuela" : {
        "clavecct": "'.$infoEscuela->CLAVECCT.'",
        "nombreCentroEducativo" : "'.utf8_encode($infoEscuela->NOMBRECT).'",
        "turno" :"'.$infoEscuela->TURNO.'"
    },

    "ofertaEducativa" : ['.$arrayOfertaEducativa.'] ,

    "infra" : ['.$arrayInfraestructura.'] , 

    "programas" :  ['.$arrayProgramas.'] ,

    "otrosDatos" : ['.$arrayInfoFaltante.'] 
}';

echo $datosJson;

?>


