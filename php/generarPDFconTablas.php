<?php


require_once '../dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$escuela1 = json_decode($_POST['esc1']);
$escuela2 = json_decode($_POST['esc2']);
$escuela3 = json_decode($_POST['esc3']);

//print_r($escuela1);


//obtener informacion extra de la escuela :v

//informacion de actividades extracurriculares y tecnologicas

//extracurriculares
$actsExtra = "";
$deportivas = "<div id='actDeportivas'><table >
	<tr>
		<th>Deportivas</th>
	</tr>";
$recreativas ="<div id='actRecreativas'><table >
	<tr>
		<th>Recreativas</th>
	</tr>";
$civicas ="<div id='actCivicas'><table >
	<tr>
		<th>Civicas</th>
	</tr>";
$apoyo ="<div id='actApoyoPadres'><table >
	<tr>
		<th>Apoyo a Padres y Alumnos</th>
	</tr>";
$culturales ="<div id='actCulturales'><table >
	<tr>
		<th>Culturales</th>
	</tr>";


//tecnologicas
$tecnologicas="";
$adminInfo ="<div id='tecAdminInfo'><table>
	<tr>
		<th>Administrativas e Informática</th>
	</tr>";
$indConstru ="<div id='tecConstru'><table>
	<tr>
		<th>Construcción</th>
	</tr>";
$turismo ="<div id='tecTurismo'><table>
	<tr>
		<th>Turismo</th>
	</tr>";
$aliAgro ="<div id='tecAgroAli'><table>
	<tr>
		<th>Agropecuarias y Alimenticias</th>
	</tr>";


for($i = 0 ; $i< sizeof($escuela1->ofertaEducativa) ; $i++)
{
	if($escuela1->ofertaEducativa[$i]->categoria == 'EXTRA CURRICULAR')
	{
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Cívicas')
		{
			$civicas .="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Actividades de apoyo a los alumnos y padres de familia')
		{
			$apoyo.="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Culturales')
		{
			$culturales.="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Recreativas')
		{
			$recreativas.="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Deportivas')
		{
			$deportivas.="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
	}


	if($escuela1->ofertaEducativa[$i]->categoria == 'TECNOLÓGICA')
	{
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Administrativas e informáticas')
		{
			$adminInfo .="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Industria y construcción')
		{
			$indConstru .="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Turismo')
		{
			$turismo .="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
		if($escuela1->ofertaEducativa[$i]->subCategoria == 'Preparación de alimentos y actividades agropecuarias')
		{
			$aliAgro.="<tr><td>".$escuela1->ofertaEducativa[$i]->descripcion."</td></tr>";
		}
	}
}
//construir tabla de actividades extra curriculares
//validar si las tablas tienen informacion
if(strpos($deportivas, '<td>') == FALSE){$deportivas.="<tr><td>NA</td></tr>";}
if(strpos($recreativas, '<td>') == FALSE){$recreativas.="<tr><td>NA</td></tr>";}
if(strpos($civicas, '<td>') == FALSE){$civicas.="<tr><td>NA</td></tr>";}
if(strpos($apoyo, '<td>') == FALSE){$apoyo.="<tr><td>NA</td></tr>";}
if(strpos($culturales, '<td>') == FALSE){$culturales.="<tr><td>NA</td></tr>";}
//cerrar la etiqueta table
$deportivas .="</table></div>";
$recreativas .="</table></div>";
$civicas .="</table></div>";
$apoyo .="</table></div>";
$culturales .="</table></div>";
$actsExtra.= $deportivas.$recreativas.$civicas.$apoyo.$culturales;


//construir la tabla de actividades tecnologicas
//validar si las tablas tienen informacion
if(strpos($adminInfo, '<td>') == FALSE){$adminInfo.="<tr><td>NA</td></tr>";}
if(strpos($indConstru, '<td>') == FALSE){$indConstru.="<tr><td>NA</td></tr>";}
if(strpos($turismo, '<td>') == FALSE){$turismo.="<tr><td>NA</td></tr>";}
if(strpos($aliAgro, '<td>') == FALSE){$aliAgro.="<tr><td>NA</td></tr>";}
//cerrar la etiqueta table
$adminInfo.="</table></div>";
$indConstru.="</table></div>";
$turismo.="</table></div>";
$aliAgro.="</table></div>";
$tecnologicas=$adminInfo.$indConstru.$turismo.$aliAgro;


//obtener la infomracion de la infraestructura
$infraestructura = "";
for($i = 0 ; $i< sizeof($escuela1->infra) ; $i++)
{
	if($i == sizeof($escuela1->infra)-1)
	{
		$infraestructura.=$escuela1->infra[$i]->desInfra.". ";
	}
	else
	{
		$infraestructura.=$escuela1->infra[$i]->desInfra.", "; 
	}
}

//obtener informacion de los programas
$programas=""; 
for($i = 0 ; $i< sizeof($escuela1->programas) ; $i++)
{
	if($i == sizeof($escuela1->programas)-1)
	{
		$programas.=$escuela1->programas[$i]->desProg.". "; 
	}
	else
	{
		$programas.=$escuela1->programas[$i]->desProg.", "; 
	}
}


//obtener informacionde otros logros de la escuela
$logro1 = "";
$logro2="";
$logro3="";
if($escuela1->otrosDatos[0]->logro1 != "")
{
	$logro1 = "<p>Primer Logro: </p>".
		"<p>El logro fue: ".$escuela1->otrosDatos[0]->logro1." y consistio en: ".$escuela1->otrosDatos[0]->descripcion1.", en el ciclo: ".$escuela1->otrosDatos[0]->ciclo1."</p>";
}

if($escuela1->otrosDatos[0]->logro2 != "")
{
	$logro2 = "<p>Segundo Logro: </p>".
		"<p>El logro fue: ".$escuela1->otrosDatos[0]->logro2." y consistio en: ".$escuela1->otrosDatos[0]->descripcion2.", en el ciclo: ".$escuela1->otrosDatos[0]->ciclo2."</p>";
}
if($escuela1->otrosDatos[0]->logro3 != "")
{
	$logro3 = "<p>Tercer Logro: </p>".
		"<p>El logro fue: ".$escuela1->otrosDatos[0]->logro3." y consistio en: ".$escuela1->otrosDatos[0]->descripcion3.", en el ciclo: ".$escuela1->otrosDatos[0]->ciclo3."</p>";
}

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
						'.$actsExtra.'
					</div>

					<div class="infoExtraTecnologicas">
						<p><b>Actividades Tecnologicas</b></p>
						'.$tecnologicas.'
					</div>

					<div class="infoExtraInfraestructura">
						<p><b>Infraestructura</b></p>
						<p>La escuela cuenta con la siguiente infraestructura: </p>
						<p>'.$infraestructura.'</p>
					</div>

					<div class="infoExtraOtrosApoyos">
						<p><b>Programas</b></p>
						<p>La escuela cuenta con los siguientes programas:</p>
						<p>'.$programas.'</p>
					</div>

					<div class="otrosLogros">
						<p><b>Otros Logros</b></p>
						<div class="logro1">'.$logro1.'</div>
						<div class="logro2">'.$logro2.'</div>
						<div class="logro3">'.$logro3.'</div>
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

/*los de arriba ya estan chidos :v*/


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

#actDeportivas{float:left; width:20%;position:relative;}
#actRecreativas{float:left; width:20%;position:relative; margin-left:20%;}
#actCivicas{float:left; width:20%;position:relative; margin-left:40%;}
#actApoyoPadres{float:left; width:20%;position:relative; margin-left:60%;}
#actCulturales{float:left; width:20%;position:relative; margin-left:80%}
#actDeportivas>table,#actRecreativas>table,#actCivicas>table,#actApoyoPadres>table,#actCulturales>table{
	font-size: 14px;
	
	text-align:center;
	width:100%;
}
.infoExtraCurricular>table>td,th,tr{border: 1px solid black;}
.infoExtraCurricular>table>tr,th{background: #3F76BA;}



.infoExtraTecnologicas{
	page-break-before: always;
}
#tecAdminInfo{float:left; width:25%;position:relative;}
#tecConstru{float:left; width:25%;position:relative; margin-left:25%;}
#tecTurismo{float:left; width:25%;position:relative; margin-left:50%;}
#tecAgroAli{float:left; width:25%;position:relative; margin-left:75%;}
#tecAdminInfo>table,#tecConstru>table,#tecTurismo>table,#tecAgroAli>table{
	font-size: 14px;
	text-align:center;
	width:100%;
}
#tecAdminInfo>table,#tecConstru>table,#tecTurismo>table,#tecAgroAli>table{
	font-size: 14px;
	text-align:center;
	width:100%;
}
.infoExtraTecnologicas>table>td,th,td,tr{border: 1px solid black;}
.infoExtraTecnologicas>table>tr,th{background: #3F76BA;}


/*los de arriba hay que ajustar tal vez :v*/

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


tr:nth-child(odd) {
    background-color:#C1C1C1;
}
tr:nth-child(even) {
    background-color:#FFFFFF;
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
