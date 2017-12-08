<?php


require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$escuela1 = json_decode($_POST['esc1']);
$escuela2 = json_decode($_POST['esc2']);
$escuela3 = json_decode($_POST['esc3']);


//obtener informacion extra de la escuela 1 :v
//informacion de actividades extracurriculares y tecnologicas
//extracurriculares
$actsExtraEsc1 = "";
$deportivasEsc1 = "<div id='actDeportivas'>
					<div class='celda headTabla'>Deportivas</div>";
$recreativasEsc1 ="<div id='actRecreativas'>
					<div class='celda headTabla'>Recreativas</div>";
$civicasEsc1="<div id='actCivicas'>
				<div class='celda headTabla'>Civicas</div>";
$apoyoEsc1 ="<div id='actApoyoPadres'>
			<div class='celda headTabla'>Apoyo a Padres y Alumnos</div>";
$culturalesEsc1 ="<div id='actCulturales'>
					<div class='celda headTabla'>Culturales</div>";

//tecnologicas
$tecnologicasEsc1="";
$adminInfoEsc1 ="<div id='tecAdminInfo'>
					<div class='celda headTabla'>Administrativas e Informática</div>";
$indConstruEsc1 ="<div id='tecConstru'>
					<div class='celda headTabla'>Construccion</div>";
$turismoEsc1 ="<div id='tecTurismo'>
				<div class='celda headTabla'>Turismo</div>";
$aliAgroEsc1 ="<div id='tecAgroAli'>
				<div class='celda headTabla'>Agropecuarias y Alimenticias</div>";


for($i = 0 ; $i< sizeof($escuela1->ofertaEducativa) ; $i++)
{
	if($escuela1->ofertaEducativa[$i]->categoria == 'EXTRA CURRICULAR')
	{
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Cívicas')
		{
			$civicasEsc1 .="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Actividades de apoyo a los alumnos y padres de familia')
		{
			$apoyoEsc1.="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Culturales')
		{
			$culturalesEsc1.="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Recreativas')
		{
			$recreativasEsc1.="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Deportivas')
		{
			$deportivasEsc1.="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
	}
	if($escuela1->ofertaEducativa[$i]->categoria == 'TECNOLÓGICA')
	{
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Administrativas e informáticas')
		{
			$adminInfoEsc1 .="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Industria y construcción')
		{
			$indConstruEsc1 .="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Turismo')
		{
			$turismoEsc1 .="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Preparación de alimentos y actividades agropecuarias')
		{
			$aliAgroEsc1.="<div class='celda'>".$escuela1->ofertaEducativa[$i]->descripcion."</div>";
		}
	}
}
//construir tabla de actividades extra curriculares
//validar si las tablas tienen informacion
if(strpos($deportivasEsc1, "<div class='celda'>") == FALSE){$deportivasEsc1.="<div class='celda'>NA</div>";}
if(strpos($recreativasEsc1, "<div class='celda'>") == FALSE){$recreativasEsc1.="<div class='celda'>NA</div>";}
if(strpos($civicasEsc1, "<div class='celda'>") == FALSE){$civicasEsc1.="<div class='celda'>NA</div>";}
if(strpos($apoyoEsc1, "<div class='celda'>") == FALSE){$apoyoEsc1.="<div class='celda'>NA</div>";}
if(strpos($culturalesEsc1, "<div class='celda'>") == FALSE){$culturalesEsc1.="<div class='celda'>NA</div>";}
//cerrar la etiqueta contenedora
$deportivasEsc1 .="</div>";
$recreativasEsc1 .="</div>";
$civicasEsc1 .="</div>";
$apoyoEsc1 .="</div>";
$culturalesEsc1 .="</div>";
$actsExtraEsc1.= $deportivasEsc1.$recreativasEsc1.$civicasEsc1.$apoyoEsc1.$culturalesEsc1;
//construir la tabla de actividades tecnologicas
//validar si las tablas tienen informacion
if(strpos($adminInfoEsc1, "<div class='celda'>") == FALSE){$adminInfoEsc1.="<div class='celda'>NA</div>";}
if(strpos($indConstruEsc1,"<div class='celda'>") == FALSE){$indConstruEsc1.="<div class='celda'>NA</div>";}
if(strpos($turismoEsc1, "<div class='celda'>") == FALSE){$turismoEsc1.="<div class='celda'>NA</div>";}
if(strpos($aliAgroEsc1, "<div class='celda'>") == FALSE){$aliAgroEsc1.="<div class='celda'>NA</div>";}
//cerrar la etiqueta contenedora
$adminInfoEsc1.="</div>";
$indConstruEsc1.="</div>";
$turismoEsc1.="</div>";
$aliAgroEsc1.="</div>";
$tecnologicasEsc1=$adminInfoEsc1.$indConstruEsc1.$turismoEsc1.$aliAgroEsc1;
//obtener la informacion de la infraestructura
$infraestructuraEsc1 = "";
for($i = 0 ; $i< sizeof($escuela1->infra) ; $i++)
{
	if($i == sizeof($escuela1->infra)-1)
	{
		$infraestructuraEsc1.=$escuela1->infra[$i]->desInfra.". ";
	}
	else
	{
		$infraestructuraEsc1.=$escuela1->infra[$i]->desInfra.", "; 
	}
}
//obtener informacion de los programas
$programasEsc1=""; 
for($i = 0 ; $i< sizeof($escuela1->programas) ; $i++)
{
	if($i == sizeof($escuela1->programas)-1)
	{
		$programasEsc1.=$escuela1->programas[$i]->desProg.". "; 
	}
	else
	{
		$programasEsc1.=$escuela1->programas[$i]->desProg.", "; 
	}
}
//obtener informacion de otros logros de la escuela
$logro1Esc1 = "";
$logro2Esc1="";
$logro3Esc1="";
if($escuela1->otrosDatos[0]->logro1 != "")
{
	$logro1Esc1 = "<p>Primer Logro: </p>".
		"<p>El logro fue: ".$escuela1->otrosDatos[0]->logro1." y consistio en: ".$escuela1->otrosDatos[0]->descripcion1.", en el ciclo: ".$escuela1->otrosDatos[0]->ciclo1."</p>";
}
else
{
	$logro1Esc1 = "<p>No hay logro</p>";
}
if($escuela1->otrosDatos[0]->logro2 != "")
{
	$logro2Esc1 = "<p>Segundo Logro: </p>".
		"<p>El logro fue: ".$escuela1->otrosDatos[0]->logro2." y consistio en: ".$escuela1->otrosDatos[0]->descripcion2.", en el ciclo: ".$escuela1->otrosDatos[0]->ciclo2."</p>";
}
else
{
	$logro2Esc1 = "<p>No hay logro</p>";
}
if($escuela1->otrosDatos[0]->logro3 != "")
{
	$logro3Esc1 = "<p>Tercer Logro: </p>".
		"<p>El logro fue: ".$escuela1->otrosDatos[0]->logro3." y consistio en: ".$escuela1->otrosDatos[0]->descripcion3.", en el ciclo: ".$escuela1->otrosDatos[0]->ciclo3."</p>";
}
else
{
	$logro3Esc1 = "<p>No hay logro</p>";
}










//obtener informacion extra de la escuela 2:v
//informacion de actividades extracurriculares y tecnologicas
//extracurriculares
$actsExtraEsc2 = "";
$deportivasEsc2 = "<div id='actDeportivas'>
					<div class='celda headTabla'>Deportivas</div>";
$recreativasEsc2 ="<div id='actRecreativas'>
					<div class='celda headTabla'>Recreativas</div>";
$civicasEsc2="<div id='actCivicas'>
				<div class='celda headTabla'>Civicas</div>";
$apoyoEsc2 ="<div id='actApoyoPadres'>
			<div class='celda headTabla'>Apoyo a Padres y Alumnos</div>";
$culturalesEsc2 ="<div id='actCulturales'>
					<div class='celda headTabla'>Culturales</div>";

//tecnologicas
$tecnologicasEsc2="";
$adminInfoEsc2 ="<div id='tecAdminInfo'>
					<div class='celda headTabla'>Administrativas e Informática</div>";
$indConstruEsc2 ="<div id='tecConstru'>
					<div class='celda headTabla'>Construccion</div>";
$turismoEsc2 ="<div id='tecTurismo'>
				<div class='celda headTabla'>Turismo</div>";
$aliAgroEsc2 ="<div id='tecAgroAli'>
				<div class='celda headTabla'>Agropecuarias y Alimenticias</div>";


for($i = 0 ; $i< sizeof($escuela2->ofertaEducativa) ; $i++)
{
	if($escuela2->ofertaEducativa[$i]->categoria == 'EXTRA CURRICULAR')
	{
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Cívicas')
		{
			$civicasEsc2 .="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Actividades de apoyo a los alumnos y padres de familia')
		{
			$apoyoEsc2.="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Culturales')
		{
			$culturalesEsc2.="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Recreativas')
		{
			$recreativasEsc2.="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Deportivas')
		{
			$deportivasEsc2.="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
	}
	if($escuela2->ofertaEducativa[$i]->categoria == 'TECNOLÓGICA')
	{
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Administrativas e informáticas')
		{
			$adminInfoEsc2 .="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Industria y construcción')
		{
			$indConstruEsc2 .="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Turismo')
		{
			$turismoEsc2 .="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela2->ofertaEducativa[$i]->subCategoria == 'Preparación de alimentos y actividades agropecuarias')
		{
			$aliAgroEsc2.="<div class='celda'>".$escuela2->ofertaEducativa[$i]->descripcion."</div>";
		}
	}
}
//construir tabla de actividades extra curriculares
//validar si las tablas tienen informacion
if(strpos($deportivasEsc2, "<div class='celda'>") == FALSE){$deportivasEsc2.="<div class='celda'>NA</div>";}
if(strpos($recreativasEsc2, "<div class='celda'>") == FALSE){$recreativasEsc2.="<div class='celda'>NA</div>";}
if(strpos($civicasEsc2, "<div class='celda'>") == FALSE){$civicasEsc2.="<div class='celda'>NA</div>";}
if(strpos($apoyoEsc2, "<div class='celda'>") == FALSE){$apoyoEsc2.="<div class='celda'>NA</div>";}
if(strpos($culturalesEsc2, "<div class='celda'>") == FALSE){$culturalesEsc2.="<div class='celda'>NA</div>";}
//cerrar la etiqueta contenedora
$deportivasEsc2 .="</div>";
$recreativasEsc2 .="</div>";
$civicasEsc2 .="</div>";
$apoyoEsc2 .="</div>";
$culturalesEsc2 .="</div>";
$actsExtraEsc2.= $deportivasEsc2.$recreativasEsc2.$civicasEsc2.$apoyoEsc2.$culturalesEsc2;
//construir la tabla de actividades tecnologicas
//validar si las tablas tienen informacion
if(strpos($adminInfoEsc2, "<div class='celda'>") == FALSE){$adminInfoEsc2.="<div class='celda'>NA</div>";}
if(strpos($indConstruEsc2,"<div class='celda'>") == FALSE){$indConstruEsc2.="<div class='celda'>NA</div>";}
if(strpos($turismoEsc2, "<div class='celda'>") == FALSE){$turismoEsc2.="<div class='celda'>NA</div>";}
if(strpos($aliAgroEsc2, "<div class='celda'>") == FALSE){$aliAgroEsc2.="<div class='celda'>NA</div>";}
//cerrar la etiqueta contenedora
$adminInfoEsc2.="</div>";
$indConstruEsc2.="</div>";
$turismoEsc2.="</div>";
$aliAgroEsc2.="</div>";
$tecnologicasEsc2=$adminInfoEsc2.$indConstruEsc2.$turismoEsc2.$aliAgroEsc2;
//obtener la infomracion de la infraestructura
$infraestructuraEsc2 = "";
for($i = 0 ; $i< sizeof($escuela2->infra) ; $i++)
{
	if($i == sizeof($escuela2->infra)-1)
	{
		$infraestructuraEsc2.=$escuela2->infra[$i]->desInfra.". ";
	}
	else
	{
		$infraestructuraEsc2.=$escuela2->infra[$i]->desInfra.", "; 
	}
}
//obtener informacion de los programas
$programasEsc2=""; 
for($i = 0 ; $i< sizeof($escuela2->programas) ; $i++)
{
	if($i == sizeof($escuela2->programas)-1)
	{
		$programasEsc2.=$escuela2->programas[$i]->desProg.". "; 
	}
	else
	{
		$programasEsc2.=$escuela2->programas[$i]->desProg.", "; 
	}
}
//obtener informacion de otros logros de la escuela
$logro1Esc2 = "";
$logro2Esc2="";
$logro3Esc2="";
if($escuela2->otrosDatos[0]->logro1 != "")
{
	$logro1Esc2 = "<p>Primer Logro: </p>".
		"<p>El logro fue: ".$escuela2->otrosDatos[0]->logro1." y consistio en: ".$escuela2->otrosDatos[0]->descripcion1.", en el ciclo: ".$escuela2->otrosDatos[0]->ciclo1."</p>";
}
else
{
	$logro1Esc2 = "<p>No hay logro</p>";
}
if($escuela2->otrosDatos[0]->logro2 != "")
{
	$logro2Esc2 = "<p>Segundo Logro: </p>".
		"<p>El logro fue: ".$escuela2->otrosDatos[0]->logro2." y consistio en: ".$escuela2->otrosDatos[0]->descripcion2.", en el ciclo: ".$escuela2->otrosDatos[0]->ciclo2."</p>";
}
else
{
	$logro2Esc2 = "<p>No hay logro</p>";
}
if($escuela2->otrosDatos[0]->logro3 != "")
{
	$logro3Esc2 = "<p>Tercer Logro: </p>".
		"<p>El logro fue: ".$escuela2->otrosDatos[0]->logro3." y consistio en: ".$escuela2->otrosDatos[0]->descripcion3.", en el ciclo: ".$escuela2->otrosDatos[0]->ciclo3."</p>";
}
else
{
	$logro3Esc2 = "<p>No hay logro</p>";
}




//obtener informacion extra de la escuela 3 :v
//informacion de actividades extracurriculares y tecnologicas
//extracurriculares
$actsExtraEsc3 = "";
$deportivasEsc3 = "<div id='actDeportivas'>
					<div class='celda headTabla'>Deportivas</div>";
$recreativasEsc3 ="<div id='actRecreativas'>
					<div class='celda headTabla'>Recreativas</div>";
$civicasEsc3="<div id='actCivicas'>
				<div class='celda headTabla'>Civicas</div>";
$apoyoEsc3 ="<div id='actApoyoPadres'>
			<div class='celda headTabla'>Apoyo a Padres y Alumnos</div>";
$culturalesEsc3 ="<div id='actCulturales'>
					<div class='celda headTabla'>Culturales</div>";

//tecnologicas
$tecnologicasEsc3="";
$adminInfoEsc3 ="<div id='tecAdminInfo'>
					<div class='celda headTabla'>Administrativas e Informática</div>";
$indConstruEsc3 ="<div id='tecConstru'>
					<div class='celda headTabla'>Construccion</div>";
$turismoEsc3 ="<div id='tecTurismo'>
				<div class='celda headTabla'>Turismo</div>";
$aliAgroEsc3 ="<div id='tecAgroAli'>
				<div class='celda headTabla'>Agropecuarias y Alimenticias</div>";


for($i = 0 ; $i< sizeof($escuela3->ofertaEducativa) ; $i++)
{
	if($escuela3->ofertaEducativa[$i]->categoria == 'EXTRA CURRICULAR')
	{
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Cívicas')
		{
			$civicasEsc3 .="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Actividades de apoyo a los alumnos y padres de familia')
		{
			$apoyoEsc3.="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Culturales')
		{
			$culturalesEsc3.="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Recreativas')
		{
			$recreativasEsc3.="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Deportivas')
		{
			$deportivasEsc3.="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
	}
	if($escuela3->ofertaEducativa[$i]->categoria == 'TECNOLÓGICA')
	{
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Administrativas e informáticas')
		{
			$adminInfoEsc3 .="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Industria y construcción')
		{
			$indConstruEsc3 .="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Turismo')
		{
			$turismoEsc3 .="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
		if($escuela3->ofertaEducativa[$i]->subCategoria == 'Preparación de alimentos y actividades agropecuarias')
		{
			$aliAgroEsc3.="<div class='celda'>".$escuela3->ofertaEducativa[$i]->descripcion."</div>";
		}
	}
}
//construir tabla de actividades extra curriculares
//validar si las tablas tienen informacion
if(strpos($deportivasEsc3, "<div class='celda'>") == FALSE){$deportivasEsc3.="<div class='celda'>NA</div>";}
if(strpos($recreativasEsc3, "<div class='celda'>") == FALSE){$recreativasEsc3.="<div class='celda'>NA</div>";}
if(strpos($civicasEsc3, "<div class='celda'>") == FALSE){$civicasEsc3.="<div class='celda'>NA</div>";}
if(strpos($apoyoEsc3, "<div class='celda'>") == FALSE){$apoyoEsc3.="<div class='celda'>NA</div>";}
if(strpos($culturalesEsc3, "<div class='celda'>") == FALSE){$culturalesEsc3.="<div class='celda'>NA</div>";}
//cerrar la etiqueta contenedora
$deportivasEsc3 .="</div>";
$recreativasEsc3 .="</div>";
$civicasEsc3 .="</div>";
$apoyoEsc3 .="</div>";
$culturalesEsc3 .="</div>";
$actsExtraEsc3.= $deportivasEsc3.$recreativasEsc3.$civicasEsc3.$apoyoEsc3.$culturalesEsc3;
//construir la tabla de actividades tecnologicas
//validar si las tablas tienen informacion
if(strpos($adminInfoEsc3, "<div class='celda'>") == FALSE){$adminInfoEsc3.="<div class='celda'>NA</div>";}
if(strpos($indConstruEsc3,"<div class='celda'>") == FALSE){$indConstruEsc3.="<div class='celda'>NA</div>";}
if(strpos($turismoEsc3, "<div class='celda'>") == FALSE){$turismoEsc3.="<div class='celda'>NA</div>";}
if(strpos($aliAgroEsc3, "<div class='celda'>") == FALSE){$aliAgroEsc3.="<div class='celda'>NA</div>";}
//cerrar la etiqueta contenedora
$adminInfoEsc3.="</div>";
$indConstruEsc3.="</div>";
$turismoEsc3.="</div>";
$aliAgroEsc3.="</div>";
$tecnologicasEsc3=$adminInfoEsc3.$indConstruEsc3.$turismoEsc3.$aliAgroEsc3;
//obtener la infomracion de la infraestructura
$infraestructuraEsc3 = "";
for($i = 0 ; $i< sizeof($escuela3->infra) ; $i++)
{
	if($i == sizeof($escuela3->infra)-1)
	{
		$infraestructuraEsc3.=$escuela3->infra[$i]->desInfra.". ";
	}
	else
	{
		$infraestructuraEsc3.=$escuela3->infra[$i]->desInfra.", "; 
	}
}
//obtener informacion de los programas
$programasEsc3=""; 
for($i = 0 ; $i< sizeof($escuela3->programas) ; $i++)
{
	if($i == sizeof($escuela3->programas)-1)
	{
		$programasEsc3.=$escuela3->programas[$i]->desProg.". "; 
	}
	else
	{
		$programasEsc3.=$escuela3->programas[$i]->desProg.", "; 
	}
}
//obtener informacion de otros logros de la escuela
$logro1Esc3 = "";
$logro2Esc3="";
$logro3Esc3="";
if($escuela3->otrosDatos[0]->logro1 != "")
{
	$logro1Esc3 = "<p>Primer Logro: </p>".
		"<p>El logro fue: ".$escuela3->otrosDatos[0]->logro1." y consistio en: ".$escuela3->otrosDatos[0]->descripcion1.", en el ciclo: ".$escuela3->otrosDatos[0]->ciclo1."</p>";
}
else
{
	$logro1Esc3 = "<p>No hay logro</p>";
}
if($escuela3->otrosDatos[0]->logro2 != "")
{
	$logro2Esc3 = "<p>Segundo Logro: </p>".
		"<p>El logro fue: ".$escuela3->otrosDatos[0]->logro2." y consistio en: ".$escuela3->otrosDatos[0]->descripcion2.", en el ciclo: ".$escuela3->otrosDatos[0]->ciclo2."</p>";
}
else
{
	$logro2Esc3 = "<p>No hay logro</p>";
}
if($escuela3->otrosDatos[0]->logro3 != "")
{
	$logro3Esc3 = "<p>Tercer Logro: </p>".
		"<p>El logro fue: ".$escuela3->otrosDatos[0]->logro3." y consistio en: ".$escuela3->otrosDatos[0]->descripcion3.", en el ciclo: ".$escuela3->otrosDatos[0]->ciclo3."</p>";
}
else
{
	$logro3Esc3 = "<p>No hay logro</p>";
}


//construir el documento de html
$html ='
<!DOCTYPE html>
<html>
<head>
	<title>PDF</title>
	<meta charset="utf-8">
</head>
<body style="page-break-after: avoid;">

	<div class="content">
		<div class="header">
			<img src="../logos/usebeq.png">
		</div>
		<div class="contenidoEscuela">
				<div class="logoImg">
					<img src="../logos/imgEscuela.png">
				</div>
				<div class="infoPrincipal">
					<p style="float:left;">Nombre Escuela: </p> 
					<p style="float:right;"><b>'.$escuela1->infoEscuela->nombreCentroEducativo.'</b></p>
					<br><br>
					<p style="float:left;">Clave CCT: </p> 	
					<p style="float:right;"><b>'.$escuela1->infoEscuela->clavecct.'</b></p>
				</div>
				<div class="infoPrincipal2">
					<div class="infPrin2Col"  style="float:left;">
						<p>Responsable: <b>'.$escuela1->otrosDatos[0]->nombreResponsable.'</b></p> 
						<p>Oferta: <b>1000</b></p> 					
					</div>
					<div class="infPrin2Col" style="float:right;">
						<p>Turno: <b>'.$escuela1->infoEscuela->turno.'</b></p> 	
						<p>Demanda: <b>1000</b></p> 			
					</div>				
				</div>
				<div class="infoEscuelaExtras">
					<h4>Información de la Escuela</h4>
					<div class="infoExtraCurricular">
						<p><b>Actividades Extra Curriculares</b></p>
						'.$actsExtraEsc1.'
					</div>
					<div class="infoExtraTecnologicas">
						<p><b>Actividades Tecnologicas</b></p>
						'.$tecnologicasEsc1.'
					</div>
					<div class="infoExtraInfraestructura">
						<p><b>Infraestructura</b></p>
						<p>La escuela cuenta con la siguiente infraestructura: </p>
						<p>'.$infraestructuraEsc1.'</p>
					</div>
					<div class="infoExtraOtrosApoyos">
						<p><b>Programas</b></p>
						<p>La escuela cuenta con los siguientes programas:</p>
						<p>'.$programasEsc1.'</p>
					</div>
					<div class="otrosLogros">
						<p><b>Otros Logros</b></p>
						<div class="logro1">'.$logro1Esc1.'</div>
						<div class="logro2">'.$logro2Esc1.'</div>
						<div class="logro3">'.$logro3Esc1.'</div>
					</div>
				</div>
		</div>




		<div class="header" style="page-break-before: always;">
			<img src="../logos/usebeq.png">
		</div>
		<div class="contenidoEscuela">
				<div class="logoImg">
					<img src="../logos/imgEscuela.png">
				</div>
				<div class="infoPrincipal">
					<p style="float:left;">Nombre Escuela: </p> 
					<p style="float:right;"><b>'.$escuela2->infoEscuela->nombreCentroEducativo.'</b></p>
					<br><br>
					<p style="float:left;">Clave CCT: </p> 	
					<p style="float:right;"><b>'.$escuela2->infoEscuela->clavecct.'</b></p>
				</div>
				<div class="infoPrincipal2">
					<div class="infPrin2Col"  style="float:left;">
						<p>Responsable: <b>'.$escuela2->otrosDatos[0]->nombreResponsable.'</b></p> 
						<p>Oferta: <b>1000</b></p> 					
					</div>
					<div class="infPrin2Col" style="float:right;">
						<p>Turno: <b>'.$escuela2->infoEscuela->turno.'</b></p> 	
						<p>Demanda: <b>1000</b></p> 			
					</div>				
				</div>
				<div class="infoEscuelaExtras">
					<h4>Información de la Escuela</h4>
					<div class="infoExtraCurricular">
						<p><b>Actividades Extra Curriculares</b></p>
						'.$actsExtraEsc2.'
					</div>
					<div class="infoExtraTecnologicas">
						<p><b>Actividades Tecnologicas</b></p>
						'.$tecnologicasEsc2.'
					</div>
					<div class="infoExtraInfraestructura">
						<p><b>Infraestructura</b></p>
						<p>La escuela cuenta con la siguiente infraestructura: </p>
						<p>'.$infraestructuraEsc2.'</p>
					</div>
					<div class="infoExtraOtrosApoyos">
						<p><b>Programas</b></p>
						<p>La escuela cuenta con los siguientes programas:</p>
						<p>'.$programasEsc2.'</p>
					</div>
					<div class="otrosLogros">
						<p><b>Otros Logros</b></p>
						<div class="logro1">'.$logro1Esc2.'</div>
						<div class="logro2">'.$logro2Esc2.'</div>
						<div class="logro3">'.$logro3Esc2.'</div>
					</div>
				</div>
		</div>





		<div class="header" style="page-break-before: always;">
			<img src="../logos/usebeq.png">
		</div>
		<div class="contenidoEscuela">
				<div class="logoImg">
					<img src="../logos/imgEscuela.png">
				</div>
				<div class="infoPrincipal">
					<p style="float:left;">Nombre Escuela: </p> 
					<p style="float:right;"><b>'.$escuela3->infoEscuela->nombreCentroEducativo.'</b></p>
					<br><br>
					<p style="float:left;">Clave CCT: </p> 	
					<p style="float:right;"><b>'.$escuela3->infoEscuela->clavecct.'</b></p>
				</div>
				<div class="infoPrincipal2">
					<div class="infPrin2Col"  style="float:left;">
						<p>Responsable: <b>'.$escuela3->otrosDatos[0]->nombreResponsable.'</b></p> 
						<p>Oferta: <b>1000</b></p> 					
					</div>
					<div class="infPrin2Col" style="float:right;">
						<p>Turno: <b>'.$escuela3->infoEscuela->turno.'</b></p> 	
						<p>Demanda: <b>1000</b></p> 			
					</div>				
				</div>
				<div class="infoEscuelaExtras">
					<h4>Información de la Escuela</h4>
					<div class="infoExtraCurricular">
						<p><b>Actividades Extra Curriculares</b></p>
						'.$actsExtraEsc3.'
					</div>
					<div class="infoExtraTecnologicas">
						<p><b>Actividades Tecnologicas</b></p>
						'.$tecnologicasEsc3.'
					</div>
					<div class="infoExtraInfraestructura">
						<p><b>Infraestructura</b></p>
						<p>La escuela cuenta con la siguiente infraestructura: </p>
						<p>'.$infraestructuraEsc3.'</p>
					</div>
					<div class="infoExtraOtrosApoyos">
						<p><b>Programas</b></p>
						<p>La escuela cuenta con los siguientes programas:</p>
						<p>'.$programasEsc3.'</p>
					</div>
					<div class="otrosLogros">
						<p><b>Otros Logros</b></p>
						<div class="logro1">'.$logro1Esc3.'</div>
						<div class="logro2">'.$logro2Esc3.'</div>
						<div class="logro3">'.$logro3Esc3.'</div>
					</div>
				</div>
		</div>



	</div>
</body>
</html>


<style type="text/css">
.content{
	width: 98%;
	margin-left:1%;
	margin-right:1%;
	padding:1%;
}
.header{
	width: 100%;
	height: 60px;
	position:relative;
}
.header>img{
	float: right;
	max-width: 250px;
	max-height: 60px;
}
.contenidoEscuela{
	position: absolute;
	width: 96%;
	margin-left3%;
	padding-right: 1%;
	margin-bottom: 1%;
}
.logoImg{
	position:absolute;
	width: 20%;
	height: 130px;
	margin-bottom: 1%;
}
.logoImg>img{
	max-width: 120px;
	max-height: 120px;
}
.infoPrincipal{
	float: left;
	width: 80%;
	margin-left:20%;
	margin-bottom:1%;
	border-style: groove;
	height: 120px;
	position: relative;
}
.infoPrincipal>p{
	position: relative;
	margin-top:0.5%;
	height 25px;
	font-size: 16px;
}
.infoPrincipal2{
	width: 100%;
	margin-bottom:1%;
	margin-top:14%;
	border-style: groove;
	height: 100px;
	position:relative;		
}
.infPrin2Col{
	width: 50%;
	height: 100px;
	position: absolute;
}
.infPrin2Col>p{
	width: 98%;
	margin-left:1%;
	margin-right:1%;
	margin-top:0.5%;
	height: 45px;
	font-size: 14px;
	text-align:center;
}
.infoEscuelaExtras{
	width: 100%;
	margin-top: 0.5%;
}
.infoEscuelaExtras>h4{
	width: 100%;
	font-size: 18px;
	text-align: center;
}
.infoExtraCurricular,.infoExtraTecnologicas{
	width: 98%;
	margin-left:1%;
	margin-right:1%;
	margin-bottom: 0.5%;
	float:left;
	position:relative;
}
.infoExtraCurricular>p,.infoExtraTecnologicas>p,.infoExtraOtrosApoyos>p,.otrosLogros>p{
	font-size: 15px;
	text-align: center;
}
.celda{
	width:100%;
	font-size:14px;
	border: 1px solid black;
	text-align:center;
}
.headTabla{background:#3F76BA;}
#actDeportivas{float:left; width:20%;position:relative;}
#actRecreativas{float:left; width:20%;position:relative; margin-left:20%;}
#actCivicas{float:left; width:20%;position:relative; margin-left:40%;}
#actApoyoPadres{float:left; width:20%;position:relative; margin-left:60%;}
#actCulturales{float:left; width:20%;position:relative; margin-left:80%}
.infoExtraTecnologicas{page-break-before: always;}
#tecAdminInfo{float:left; width:25%;position:relative;}
#tecConstru{float:left; width:25%;position:relative; margin-left:25%;}
#tecTurismo{float:left; width:25%;position:relative; margin-left:50%;}
#tecAgroAli{float:left; width:25%;position:relative; margin-left:75%;}
.infoExtraInfraestructura{
	width: 100%;
	margin-bottom:1%;
	margin-top:35%;
	border-style: groove;
	height: 120px;
	position:relative;
}
.infoExtraInfraestructura>p{
	font-size:15px;
	text-align:center;
}
.infoExtraOtrosApoyos{
	width: 100%;
	margin-bottom:1%;
	margin-top:0.1%;
	border-style: groove;
	height: 120px;
	position:relative;
}
.infoExtraOtrosApoyos>p{
	font-size:15px;
	text-align:center;
}
.otrosLogros{
	width: 98%;
	margin-left:1%;
	margin-right:1%;
	margin-bottom:0.5%;
	margin-top:0.1%;
	border-style: groove;
	height: 365px;
	position:relative;
	padding-bottom:1%;
}
.otrosLogros>p{font-size:15px;}
.logro1 ,.logro2, .logro3{
	margin-left: 1%;
	margin-right: 1%;
	width: 98%;
	border-style: ridge;
	height:105px;
}
.logro1>p,.logro2>p,.logro3>p{
	font-size:14px;
}
</style>
';

// instantiate and use the dompdf class
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');
// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream('Reporte.pdf',array('Attachment'=>0));
?>
