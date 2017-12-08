localStorage.setItem('contador', 1)

var marker
var circle
var map
var radio = 3000
markers = [];
var markerCluster;

//funcion para buscar una ciudad
function buscarCP()
{
	cp = document.getElementById('cp');
	if (cp.value!="") {
		cp.disabled = "true";
		cp.style.opacity = ".5";
		cp.innerHTML = "<img src='gps.svg'>";
		document.getElementById('bcp').innerHTML = "<img src='gps.svg'>";
		document.getElementById('bcp').style.opacity=".5";

		document.getElementById('r1').classList.add('ver');
		document.getElementById('r2').classList.add('ver');
		document.getElementById('r3').classList.add('ver');

	}else{
		cp.style.border="2px solid #da3b42";
		msjError('Por favor introduce un valor.')
	}
}

//funcion que muestra un mensaje de error cuando no introduce ningun valor el user
function msjError(men)
{
	mensaje = document.getElementById('mensaje');
	mensaje.style.opacity=".5"
	mensaje.style.marginTop="10px"
	mensaje.style.visibility="visible"
	mensaje.innerHTML = "Por favor introduce un valor."

	setTimeout(function(){
		mensaje.style.opacity="0"
		mensaje.style.marginTop="60px"
		mensaje.style.visibility="hidden"
		cp.style.border="2px solid transparent";
	},2000)
}

// funcion para cargar mapa en la interfaz
function cargarMapa(marker)
{
	document.getElementById('nav').style.height="50px";

	document.querySelector('.bienvenida').classList.add('desaparecer')
	setTimeout(function(){document.querySelector('.nav-mini').classList.add('verNM')},800);
	setTimeout(function(){document.querySelector('.bienvenida').style.display="none"},2000);
	
	localStorage.setItem('escuela1' , '')
	localStorage.setItem('escuela2' , '')
	localStorage.setItem('escuela3' , '')

	setTimeout(function(){cargarMarcadores(marker)},1000);
}

//funcion que muestra o quita las escuelas seleccionadas
function ver(pin)
{
	if (pin==2) 
	{
		document.getElementById('p1').onclick = "";

		localStorage.setItem('contador', 2)
		document.getElementById('o1').classList.add('half');
		document.getElementById('o2').classList.add('half');
		document.getElementById('p1').src="pin2.png"      
		document.getElementById('p1').style.webkitFilter="saturate(1) !important"

		setTimeout(function(){
			document.getElementById('o2').style.visibility="visible"
			document.getElementById('o2').style.opacity="1"
		},1000)
	}
	if (pin==3) 
	{
		if(document.getElementById('p2').getAttribute('src') == "pin2.png")
		{
			document.getElementById('p1').addEventListener('click',function(){
          		ver(2)
          })

			localStorage.setItem('contador', 1)
			localStorage.setItem('escuela2', '')

			document.getElementById('p2').src="pin.png"
			document.getElementById('p2').style.webkitFilter="saturate(0) !important"
			document.getElementById('o2').style.visibility="hide"
			document.getElementById('o2').style.opacity="0"
			document.getElementById('o1').classList.remove('half');
			document.getElementById('o2').classList.remove('half');
			document.getElementById('p1').src="pin.png"
			document.getElementById('p1').style.webkitFilter="saturate(0) !important"


			document.getElementById('o1').innerHTML = "";
			document.getElementById('o2').innerHTML = "";
		}
		else
		{
			document.getElementById('p2').onclick = "";

			localStorage.setItem('contador', 3)
			document.getElementById('o1').classList.add('third');
			document.getElementById('o2').classList.add('third');
			document.getElementById('o3').classList.add('third');
			document.getElementById('p2').src="pin2.png"
			document.getElementById('p2').style.webkitFilter="saturate(1) !important"
			setTimeout(function(){
				document.getElementById('o3').style.visibility="visible"
				document.getElementById('o3').style.opacity="1"
			},1000)
		}

	}
	if(pin == 4)
	{
		if(document.getElementById('p3').getAttribute('src') == "pin2.png")
		{
			document.getElementById('p2').addEventListener('click',function(){
          		ver(3)
          })

			localStorage.setItem('contador', 2)
			localStorage.setItem('escuela3', '')

			document.getElementById('p3').src="pin.png"
			document.getElementById('p3').style.webkitFilter="saturate(0) !important"
			document.getElementById('o3').style.visibility="hide"
			document.getElementById('o3').style.opacity="0"
			document.getElementById('o1').classList.remove('third');
			document.getElementById('o2').classList.remove('third');

			document.getElementById('o3').classList.remove('fourth');
			document.getElementById('o4').classList.remove('fourth');
			document.getElementById('o4').style.visibility="hide"	

			document.getElementById('o3').innerHTML = "";
		}
		else
		{
			localStorage.setItem('contador', 4)
			document.getElementById('p3').src="pin2.png"
			document.getElementById('p3').style.webkitFilter="saturate(1) !important"
			
			document.getElementById('o3').classList.add('fourth');
			document.getElementById('o4').classList.add('fourth');
			document.getElementById('o4').style.visibility="visible"
		}
	}
}

//funcion que obtiene por ajax las coordenadas de todas las escuelas
function ajaxGetCoordenadasEscuelas()
{
	ajax = new XMLHttpRequest();
	ajax.open('GET','http://148.220.52.120/usebeq/php/ajaxGetCoordenadasEscuelas.php',true);
	//ajax.open('GET','php/ajaxGetCoordenadasEscuelas.php',true);
	ajax.setRequestHeader("Content-type", "application/json; charset=utf-8");
	ajax.setRequestHeader("Data-type", "jsonp");
	ajax.send();
	ajax.onreadystatechange = function()
	{
		if(ajax.readyState == 4 && ajax.status==200)
		{
			escuelas = JSON.parse(ajax.responseText)
			return escuelas
		}
	}
}


// =================================== MAPA ====================================

//funcion para cargar el mapa de la API de google maps
function initMap() 
{
    escuelas = ajaxGetCoordenadasEscuelas()
    map = new google.maps.Map(document.getElementById('map'), {
    	zoom: 12,
		//center: {lat: 20.592252, lng: -100.392337}
	});
	var geocoder = new google.maps.Geocoder();
    document.getElementById('bcp').addEventListener('click', function() {
		if (cp.value!="") {
			geocodeAddress(geocoder, map);
		}
	});

	initAutocomplete()
}

//funcion que muestra el marcador de la direccion previamente ingresada
function geocodeAddress(geocoder, resultsMap) 
{
	var address = document.getElementById('cp').value + ', Qro.';
	geocoder.geocode({'address': address}, function(results, status) {
		if (status === 'OK') {
			resultsMap.setCenter(results[0].geometry.location);
			marker = new google.maps.Marker({
				map: resultsMap,
				position: results[0].geometry.location,
				icon : 'house.png',
			});

			circle = new google.maps.Circle({
				strokeColor:'#FF0000',
				strokeOpacity: 0.4,
				strokeWeight: 2,
				fillColor: '#FF0000',
				fillOpacity : 0.1,
				map:resultsMap,
				center : marker.center,
				radius: radio,
			});
			circle.bindTo('center' , marker , 'position')
			
			setTimeout(function(){cargarMapa(marker)},1500)
		}else {
			cp.removeAttribute('disabled');
			cp.style.opacity = "1";
			document.getElementById('bcp').innerHTML = "<img src='arrow.png' class='invertir'>";
			document.getElementById('bcp').style.opacity="1";
			cp.value="";
			document.getElementById('r1').classList.remove('ver');
			document.getElementById('r2').classList.remove('ver');
			document.getElementById('r3').classList.remove('ver');
			msjError('Escribe una dirección o valor válido');
		}
	});
}

//funcion que borra el circulo del mapa
function borrarCirculo()
{
	circle.setMap(null)
	return
}



//funcion que carga los marcadores dentro de un radio
function loadMarkersInRadium()
{
	cargarMarcadores(marker)
	circle = new google.maps.Circle({
		strokeColor:'#FF0000',
		strokeOpacity: 0.4,
		strokeWeight: 2,
		fillColor: '#FF0000',
		fillOpacity : 0.1,
		map:map,
		center : marker.center,
		radius: radio,
	});
	circle.bindTo('center' , marker , 'position')
}

//funcion que carga todos los marcadores
function loadAllMarkers()
{
	for (var i = 0; i < escuelas.length; i++) 
	{
		escuelas[i].lat = parseFloat(escuelas[i].lat)
		escuelas[i].lng = parseFloat(escuelas[i].lng)
		//si el turno de la escuela es vespertino, mover el marcador un pococ para que se vea en el mapa
		if(escuelas[i].turno == "VES")
		{
			escuelas[i].lat = parseFloat(escuelas[i].lat) + 0.00009
			escuelas[i].lng = parseFloat(escuelas[i].lng) + 0.00009	
		}

        addMarkerWithTimeout(escuelas[i], i * 12);

    }
}
//funcion que carga todos los marcadores de las escuelas en la interfaz
//dentro de un radio determinado
function cargarMarcadores(marker)
{
	//obtener las coordenadas de la direccion ingresada
	var latCasa = marker.getPosition().lat()
	var longCasa = marker.getPosition().lng()
	var casa = new google.maps.LatLng(latCasa, longCasa);

	for (var i = 0; i < escuelas.length; i++) 
	{
		escuelas[i].lat = parseFloat(escuelas[i].lat)
		escuelas[i].lng = parseFloat(escuelas[i].lng)

		distancia = calcDistance(latCasa , longCasa , escuelas[i].lat , escuelas[i].lng)
		if(distancia < radio)
		{
			//si el truno de la escuela es vespertino, mover el marcador un pococ para que se vea en el mapa
			if(escuelas[i].turno == "VES")
			{
				escuelas[i].lat = parseFloat(escuelas[i].lat) + 0.00004
				escuelas[i].lng = parseFloat(escuelas[i].lng) + 0.00004	
			}
			addMarkerWithTimeout(escuelas[i], i * 12);
		}
    }
}


//funcion que calcula la distancia entre dos puntos del mapa
function calcDistance (fromLat, fromLng, toLat, toLng)
{
      return google.maps.geometry.spherical.computeDistanceBetween(
        new google.maps.LatLng(fromLat, fromLng), new google.maps.LatLng(toLat, toLng));
}

//funcion que muestra cada marcador en la interfaz
function addMarkerWithTimeout(position, timeout) 
{
        window.setTimeout(function() {
        	markerSchool = new google.maps.Marker({
            position: position,
            map: map,
            animation: google.maps.Animation.DROP,
           	icon: 'school.png',
          })
          markerSchool.addListener('click',function(){
          	getInfoEscuela(position.clave , position.turno)
          })
          markers.push(markerSchool);
       	  //markerCluster = new MarkerClusterer(map, markers,{imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
        }, timeout);

}


//funcion que obtiene la informacionde la escuela segun su clave
function getInfoEscuela(clave , turno)
{
	//ajax para obtener la informacionde la escuela por el id 
	ajax = new XMLHttpRequest();
	//ajax.open('POST','php/ajaxGetInfoEscuela.php',true);
	ajax.open('POST','http://148.220.52.120/usebeq/php/ajaxGetInfoEscuela.php',true);
	ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	ajax.setRequestHeader("Content-type", "application/json; charset=utf-8");
	ajax.setRequestHeader("Data-type", "jsonp");

	ajax.send('clave='+clave+'&turno='+turno);
	ajax.onreadystatechange = function()
	{
		if(ajax.readyState == 4 && ajax.status==200)
		{
			loadDatos(ajax.responseText)
		}
	}
}

//funcion que verifica en que parte de la interfaz se van a mostrar los datos
function loadDatos(json)
{
	contador = parseInt(localStorage.getItem('contador'))
	if(contador == 1)
	{	
		mostrarDatos('o1' , json )
		localStorage.setItem('contador', 1)

		setTimeout(function(){
			document.getElementById('o1').style.visibility="visible"
			document.getElementById('o1').style.opacity="1"
		},1000)
	}
	if(contador == 2)
	{
		mostrarDatos('o2' , json )
	}
	if(contador == 3)
	{
		mostrarDatos('o3' , json)
	}
	if(contador > 3)
	{
		alert('solo se pueden seleccionar tres escuelas')
		localStorage.setItem('contador' , 4)
	}
}


//funcion que muestra los datos de la escuela en la interfaz
function mostrarDatos(idCont , json)
{
	data = JSON.parse(json)
	document.getElementById(idCont).innerHTML = ""
	infoEscuela = ""
	info = ""
	ofertaEducativacat1 = ""
	ofertaEducativacat2 = ""
	ofertaEducativacat3 = ""
	programas = ""
	infraestructura = ""
	otros = ""
	
	if(idCont == 'o1')
	{
		localStorage.setItem('escuela1' , JSON.stringify(data))
		infoEscuela ='<h1>'+data.infoEscuela.nombreCentroEducativo+'</h1>'+
				 '<img src="pin.png" onclick="ver(2)" id="p1">'+
				 '<p>Clave CCT: '+data.infoEscuela.clavecct+'</p>'+
				 '<p>Turno: '+data.infoEscuela.turno+'</p>'+
			     '<p>Oferta: 200</p>'+
			     '<p>Demanda: 1000</p>'
	}
	if(idCont == 'o2')
	{
		localStorage.setItem('escuela2' , JSON.stringify(data))
		infoEscuela ='<h1>'+data.infoEscuela.nombreCentroEducativo+'</h1>'+
				 '<img src="pin.png" onclick="ver(3)" id="p2">'+
				 '<p>Turno: '+data.infoEscuela.turno+'</p>'+
			     '<p>Oferta: 200</p>'+
			     '<p>Demanda: 1000</p>'
	}
	if(idCont == 'o3')
	{
		localStorage.setItem('escuela3' , JSON.stringify(data))
		infoEscuela ='<h1>'+data.infoEscuela.nombreCentroEducativo+'</h1>'+
				 '<img src="pin.png"  onclick="ver(4)" id="p3">'+
				 '<p>Turno: '+data.infoEscuela.turno+'</p>'+
			     '<p>Oferta: 200</p>'+
			     '<p>Demanda: 1000</p>'
	}

	if(data.otrosDatos.length !=0)
	{
	info = '<tr>'+'<th colspan="2">Información de la Escuela</th>'+'</tr>'+
			'<tr>'+
				'<td>'+'Responsable'+'</td>'+
				'<td>'+data.otrosDatos[0].nombreResponsable+'</td>'+
			'</tr>'	
	}
	else
	{
	info = '<tr>'+'<th colspan="2">No cuenta con información</th>'+'</tr>'
	}


//oferta educativa
	if(data.ofertaEducativa.length != 0)
	{
		ofertaEducativacat1 ='<tr>'+'<th colspan="2">EXTRA CURRICULAR</th>'+'</tr>'
		ofertaEducativacat2 = '<tr>'+'<th colspan="2">TECNOLÓGICA</th>'+'</tr>'
		ofertaEducativacat3 = '<tr>'+'<th colspan="2">OTROS APOYOS Y RECURSOS</th>'+'</tr>'

		for(i =0 ; i<data.ofertaEducativa.length ; i++)
		{
			if(data.ofertaEducativa[i].categoria == 'EXTRA CURRICULAR')
			{
				ofertaEducativacat1 +=
					'<tr>'+
						'<td>'+data.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+data.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(data.ofertaEducativa[i].categoria == 'TECNOLÓGICA')
			{
				ofertaEducativacat2 +=
					'<tr>'+
						'<td>'+data.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+data.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(data.ofertaEducativa[i].categoria == 'OTROS APOYOS Y RECURSOS')
			{
				ofertaEducativacat3 +=
					'<tr>'+
						'<td>'+data.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+data.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
		}
	}
	else
	{
		ofertaEducativacat1 ='<tr>'+'<th colspan="2">No tiene actividades extra curriculares</th>'+'</tr>'
		ofertaEducativacat2 = '<tr>'+'<th colspan="2">No cuenta con actividades tecnologicas</th>'+'</tr>'
		ofertaEducativacat3 = '<tr>'+'<th colspan="2">No cuenta con otros apoyos</th>'+'</tr>'
	}

//programas
	if(data.programas.length !=0)
	{
		programas ='<tr>'+'<th colspan="2">Programas en la Escuela</th>'+'</tr>'
		for(i =0 ; i<data.programas.length ; i++)
		{
		programas +=
			'<tr>'+
				'<td>'+'Programa: '+'</td>'+
				'<td>'+data.programas[i].desProg+'</td>'+
			'</tr>'
		}
	}
	else
	{
		programas ='<tr>'+'<th colspan="2">No cuenta con ningun programa</th>'+'</tr>'
	}

//infraestructura
	if(data.infra.length !=0)
	{
		infraestructura ='<tr>'+'<th colspan="2">Infraestructura Destacable</th>'+'</tr>'
		for(i =0 ; i<data.infra.length ; i++)
		{
		infraestructura +=
			'<tr>'+
				'<td>'+'Infraestructura: '+'</td>'+
				'<td>'+data.infra[i].desInfra+'</td>'+
			'</tr>'
		}
	}
	else
	{
		infraestructura ='<tr>'+'<th colspan="2">No cuenta con infraestructura</th>'+'</tr>'
	}


//otros logros
	if(data.otrosDatos.length !=0)
	{
		otros ='<tr>'+'<th colspan="2">Otros Logros</th>'+'</tr>'
		logro1 = ""
		logro2 = ""
		logro3 = ""
		if(data.otrosDatos[0].logro1 != "")
		{
			logro1 +=
				'<tr>'+
					'<td>'+'Logro 1'+'</td>'+
					'<td>'+
						'Logro: '+data.otrosDatos[0].logro1+"<br>"+
						'Descripción: '+data.otrosDatos[0].descripcion1+"<br>"+
						'Ciclo: '+data.otrosDatos[0].ciclo1+"<br>"+
					'</td>'+
				'</tr>'			
		}
		if(data.otrosDatos[0].logro2 != "")
		{
			logro2 +='<tr>'+
						'<td>'+'Logro 2'+'</td>'+
						'<td>'+
							'Logro: '+data.otrosDatos[0].logro2+"<br>"+
							'Descripción: '+data.otrosDatos[0].descripcion2+"<br>"+
							'Ciclo: '+data.otrosDatos[0].ciclo2+"<br>"+
						'</td>'+
					  '</tr>'	
		}
		if(data.otrosDatos[0].logro3 != "")
		{
			logro3 +='<tr>'+
						'<td>'+'Logro 2'+'</td>'+
						'<td>'+
							'Logro: '+data.otrosDatos[0].logro3+"<br>"+
							'Descripción: '+data.otrosDatos[0].descripcion3+"<br>"+
							'Ciclo: '+data.otrosDatos[0].ciclo3+"<br>"+
						'</td>'+
					  '</tr>'		
		}

		if(logro1 == "" && logro2 == "" && logro3 == "" )
		{
			otros ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
		}
		else
		{
			otros += logro1 + logro2 + logro3
		}
	}
	else
	{
		otros ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
	}

	document.getElementById(idCont).innerHTML = infoEscuela + 
	"<table>"+
	info + ofertaEducativacat1 + ofertaEducativacat2 + ofertaEducativacat3 +programas +infraestructura+ otros +
	"</table>"
}

//funcion que controla el comportamiento de los selects de los filtros
function mostrarFiltro()
{
	if(document.getElementById("menuFiltro").style.display == "block")
	{
		document.getElementById("menuFiltro").style.display = "none"
	}
	else
	{
		document.getElementById("menuFiltro").style.display = "block"
	}
}


//funcion que borra los marcadores del mapa
function borrarMarcadores()
{
	setMapOnAll(null);
	markers = []
}

// Sets the map on all markers in the array.
function setMapOnAll(map) 
{
	for (var i = 0; i < markers.length; i++) {
	  markers[i].setMap(map);
	}

}

//funcion que filtra las escuelas
function filtrar(filtroCriterio)
{
	borrarMarcadores()
	borrarCirculo()
	//document.getElementById('checkRango').checked = false
	//obtener criterios de filtrado
	checkboxes = []
	checkboxesChecked = []

	if(filtroCriterio == "")
	{
		alert('no se ha seleccionado ningun criterio de busqueda')
		return
	}
	if(filtroCriterio == "sinFiltros")
	{
		document.getElementById("menuFiltro").style.display = "none"
		//document.getElementById("checkRango").checked = false
		borrarCirculo()
		loadAllMarkers()
		return
	}
	if(filtroCriterio == "ofertaExtraCurr")
	{
		checkboxes = document.getElementsByName('extraCurr')
		for (var i=0; i<checkboxes.length; i++) 
		{
		    if(checkboxes[i].checked) 
		    {
		        checkboxesChecked.push(checkboxes[i]);
			}
		}

	}
	if(filtroCriterio == "ofertaTecno")
	{
		checkboxes = document.getElementsByName('tecno')
		for (var i=0; i<checkboxes.length; i++) 
		{
		    if(checkboxes[i].checked) 
		    {
		        checkboxesChecked.push(checkboxes[i]);
			}
		}
	}
	if(filtroCriterio == "programas")
	{
		checkboxes = document.getElementsByName('programas')
		for (var i=0; i<checkboxes.length; i++) 
		{
		    if(checkboxes[i].checked) 
		    {
		        checkboxesChecked.push(checkboxes[i]);
			}
		}
	}
	if(filtroCriterio == "infraestructura")
	{
		checkboxes = document.getElementsByName('infra')
		for (var i=0; i<checkboxes.length; i++) 
		{
		    if(checkboxes[i].checked) 
		    {
		        checkboxesChecked.push(checkboxes[i]);
			}
		
		}	
	}

	opcionesFiltro = ""
	if(checkboxesChecked.length == 1)
	{
		opcionesFiltro += "@"+checkboxesChecked[0].value
	}
	else
	{
		for(var i =0 ; i<checkboxesChecked.length ; i++)
		{
			if(i == checkboxesChecked.length -1)
			{
				opcionesFiltro += checkboxesChecked[i].value
			}
			else
			{
				opcionesFiltro += checkboxesChecked[i].value+"@"				
			}
		}		
	}

	//ajax para obtener los datos de las escuelas filtradas
	php = 'http://148.220.52.120/usebeq/php/ajaxFiltro.php?tipo='+filtroCriterio+"&parametros="+opcionesFiltro
	//php = 'php/ajaxFiltro.php?tipo='+filtroCriterio+"&parametros="+opcionesFiltro
	//console.log(php)
	ajax = new XMLHttpRequest();
	ajax.open('GET',php,true);
	ajax.setRequestHeader("Content-type", "application/json; charset=utf-8");
	ajax.setRequestHeader("Data-type", "jsonp");
	ajax.send()
	ajax.onreadystatechange = function()
	{
		if(ajax.readyState == 4 && ajax.status==200)
		{
			data = JSON.parse(ajax.responseText)
			if(data.length == 0)
			{
				alert('no hay ninguna escuela con estas caracterizticas')
			}
			else
			{
				//deseleccionar checkboxes
				unCheckOpciones()
				//quitar menu de filtro
				mostrarFiltro()
				//mostrar marcadores filtrados
				for(i = 0; i<data.length ; i++)
				{
					data[i].lat = parseFloat(data[i].lat)
					data[i].lng = parseFloat(data[i].lng)
					addMarkerWithTimeout(data[i], i * 12);
				}

			}
		}
	}
}

//funcion que deselecciona todos los checkboxes
function unCheckOpciones()
{
	check1 = []
	check2 = []
	check3 = []
	check4 = []
	check1 = document.getElementsByName('extraCurr')
	check2 = document.getElementsByName('tecno')
	check3 = document.getElementsByName('programas')
	check4 = document.getElementsByName('infra')

	for(i = 0; i<check1.length ; i++)
	{
		check1[i].checked = false
	}	
	for(i = 0; i<check2.length ; i++)
	{
		check2[i].checked = false
	}	
	for(i = 0; i<check3.length ; i++)
	{
		check3[i].checked = false
	}	
	for(i = 0; i<check4.length ; i++)
	{
		check4[i].checked = false
	}	
}

//redirige a la pagina de comparar
function comparar()
{ 
	if(document.getElementById('p1').getAttribute('src') == "pin2.png" && document.getElementById('p2').getAttribute('src') == "pin2.png" && document.getElementById('p3').getAttribute('src') == "pin2.png")
	{
		window.location.assign("comparar.html")
	}
	else
	{
		alert('debes de seleccionar todas las escuelas')
	}
}

//redirige al index
function regresar(){ window.location.assign("index.html") }



//esta funcion oculta o muestra el encabezado dependiendo si hace scroll o no
//esta funcion se ejecuta solo enla página de comparar
var previousPosition = window.pageYOffset || document.documentElement.scrollTop;
window.onscroll = function (e) {  
	var currentPosition = window.pageYOffset || document.documentElement.scrollTop;
	if (previousPosition > currentPosition) 
	{
		document.getElementById('header').style.visibility="visible";
		document.getElementById('header').style.opacity="1";
	} 
	else 
	{
		document.getElementById('header').style.opacity="0";
		document.getElementById('header').style.visibility="hidden";
	}
	previousPosition = currentPosition;
}

//esta funcion se ejecut al cargar la pagina de comparar
window.onload = function(){
	llenarComparar()
}


//esta funcion obtiene los datos de las escuelas seleccionadas 
// y las muestra en la interfaz
function llenarComparar()
{
	if(!document.getElementById('comparar1') && !document.getElementById('comparar2') && !document.getElementById('comparar3'))
	{
		return
	}
	escuela1 = JSON.parse(localStorage.getItem('escuela1'))
	escuela2 = JSON.parse(localStorage.getItem('escuela2'))
	escuela3 = JSON.parse(localStorage.getItem('escuela3'))
	esc1 = document.getElementById('comparar1')
	esc2 = document.getElementById('comparar2')
	esc3 = document.getElementById('comparar3')


	esc1.innerHTML = ""
	esc2.innerHTML = ""
	esc3.innerHTML = ""

//escuela 1
	//info escuela
	infoGenEsc1 = '<h1>'+escuela1.infoEscuela.nombreCentroEducativo+'</h1>'+
				 '<p>Turno: '+escuela1.infoEscuela.turno+'</p>'+
			     '<p>Oferta: 200</p>'+
			     '<p>Demanda: 1000</p>'

	if(escuela1.otrosDatos.length !=0)
	{
	infoEsc1 = '<tr>'+'<th colspan="2">Información de la Escuela</th>'+'</tr>'+
			'<tr>'+
				'<td>'+'Responsable'+'</td>'+
				'<td>'+escuela1.otrosDatos[0].nombreResponsable+'</td>'+
			'</tr>'	
	}
	else
	{
	infoEsc1 = '<tr>'+'<th colspan="2">No cuenta con información</th>'+'</tr>'
	}

	//oferta educativa
	if(escuela1.ofertaEducativa.length != 0)
	{
		ofertaEsc1 ='<tr>'+'<th colspan="2">EXTRA CURRICULAR</th>'+'</tr>'
		ofertaEsc2 = '<tr>'+'<th colspan="2">TECNOLÓGICA</th>'+'</tr>'
		ofertaEsc3 = '<tr>'+'<th colspan="2">OTROS APOYOS Y RECURSOS</th>'+'</tr>'

		for(i =0 ; i<escuela1.ofertaEducativa.length ; i++)
		{
			if(escuela1.ofertaEducativa[i].categoria == 'EXTRA CURRICULAR')
			{
				ofertaEsc1 +=
					'<tr>'+
						'<td>'+escuela1.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela1.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(escuela1.ofertaEducativa[i].categoria == 'TECNOLÓGICA')
			{
				ofertaEsc2 +=
					'<tr>'+
						'<td>'+escuela1.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela1.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(escuela1.ofertaEducativa[i].categoria == 'OTROS APOYOS Y RECURSOS')
			{
				ofertaEsc3 +=
					'<tr>'+
						'<td>'+escuela1.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela1.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
		}
	}
	else
	{
		ofertaEsc1 ='<tr>'+'<th colspan="2">No tiene actividades extra curriculares</th>'+'</tr>'
		ofertaEsc2 = '<tr>'+'<th colspan="2">No cuenta con actividades tecnologicas</th>'+'</tr>'
		ofertaEsc3 = '<tr>'+'<th colspan="2">No cuenta con otros apoyos</th>'+'</tr>'
	}

//programas
	if(escuela1.programas.length !=0)
	{
		programasEsc1 ='<tr>'+'<th colspan="2">Programas en la Escuela</th>'+'</tr>'
		for(i =0 ; i<escuela1.programas.length ; i++)
		{
		programasEsc1 +=
			'<tr>'+
				'<td>'+'Programa: '+'</td>'+
				'<td>'+escuela1.programas[i].desProg+'</td>'+
			'</tr>'
		}
	}
	else
	{
		programasEsc1 ='<tr>'+'<th colspan="2">No cuenta con ningun programa</th>'+'</tr>'
	}

//infraestructura
	if(escuela1.infra.length !=0)
	{
		infraestructuraEsc1 ='<tr>'+'<th colspan="2">Infraestructura Destacable</th>'+'</tr>'
		for(i =0 ; i<escuela1.infra.length ; i++)
		{
		infraestructuraEsc1 +=
			'<tr>'+
				'<td>'+'Infraestructura: '+'</td>'+
				'<td>'+escuela1.infra[i].desInfra+'</td>'+
			'</tr>'
		}
	}
	else
	{
		infraestructuraEsc1 ='<tr>'+'<th colspan="2">No cuenta con infraestructura</th>'+'</tr>'
	}


//otros logros
	if(escuela1.otrosDatos.length !=0)
	{
		otrosEsc1 ='<tr>'+'<th colspan="2">Otros Logros</th>'+'</tr>'
		logro1Esc1 = ""
		logro2Esc1 = ""
		logro3Esc1 = ""
		if(escuela1.otrosDatos[0].logro1 != "")
		{
			logro1Esc1 +=
				'<tr>'+
					'<td>'+'Logro 1'+'</td>'+
					'<td>'+
						'Logro: '+escuela1.otrosDatos[0].logro1+"<br>"+
						'Descripción: '+escuela1.otrosDatos[0].descripcion1+"<br>"+
						'Ciclo: '+escuela1.otrosDatos[0].ciclo1+"<br>"+
					'</td>'+
				'</tr>'			
		}
		if(escuela1.otrosDatos[0].logro2 != "")
		{
			logro2Esc1 +='<tr>'+
						'<td>'+'Logro 2'+'</td>'+
						'<td>'+
							'Logro: '+escuela1.otrosDatos[0].logro2+"<br>"+
							'Descripción: '+escuela1.otrosDatos[0].descripcion2+"<br>"+
							'Ciclo: '+escuela1.otrosDatos[0].ciclo2+"<br>"+
						'</td>'+
					  '</tr>'	
		}
		if(escuela1.otrosDatos[0].logro3 != "")
		{
			logro3Esc1 +='<tr>'+
						'<td>'+'Logro 3'+'</td>'+
						'<td>'+
							'Logro: '+escuela1.otrosDatos[0].logro3+"<br>"+
							'Descripción: '+escuela1.otrosDatos[0].descripcion3+"<br>"+
							'Ciclo: '+escuela1.otrosDatos[0].ciclo3+"<br>"+
						'</td>'+
					  '</tr>'		
		}

		if(logro1Esc1 == "" && logro2Esc1 == "" && logro3Esc1 == "" )
		{
			otrosEsc1 ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
		}
		else
		{
			otrosEsc1 += logro1Esc1 + logro2Esc1 + logro3Esc1
		}
	}
	else
	{
		otrosEsc1 ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
	}

	esc1.innerHTML = infoGenEsc1 + 
	"<table>"+
	infoEsc1 + ofertaEsc1 + ofertaEsc2 + ofertaEsc3 +programasEsc1 +infraestructuraEsc1+ otrosEsc1 +
	"</table>"

//escuela 2
	//info escuela
	infoGenEsc2 = '<h1>'+escuela2.infoEscuela.nombreCentroEducativo+'</h1>'+
				 '<p>Turno: '+escuela2.infoEscuela.turno+'</p>'+
			     '<p>Oferta: 200</p>'+
			     '<p>Demanda: 1000</p>'

	if(escuela2.otrosDatos.length !=0)
	{
	infoEsc2 = '<tr>'+'<th colspan="2">Información de la Escuela</th>'+'</tr>'+
			'<tr>'+
				'<td>'+'Responsable'+'</td>'+
				'<td>'+escuela2.otrosDatos[0].nombreResponsable+'</td>'+
			'</tr>'	
	}
	else
	{
	infoEsc2 = '<tr>'+'<th colspan="2">No cuenta con información</th>'+'</tr>'
	}

	//oferta educativa
	if(escuela2.ofertaEducativa.length != 0)
	{
		ofertaEsc1 ='<tr>'+'<th colspan="2">EXTRA CURRICULAR</th>'+'</tr>'
		ofertaEsc2 = '<tr>'+'<th colspan="2">TECNOLÓGICA</th>'+'</tr>'
		ofertaEsc3 = '<tr>'+'<th colspan="2">OTROS APOYOS Y RECURSOS</th>'+'</tr>'

		for(i =0 ; i<escuela2.ofertaEducativa.length ; i++)
		{
			if(escuela2.ofertaEducativa[i].categoria == 'EXTRA CURRICULAR')
			{
				ofertaEsc1 +=
					'<tr>'+
						'<td>'+escuela2.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela2.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(escuela2.ofertaEducativa[i].categoria == 'TECNOLÓGICA')
			{
				ofertaEsc2 +=
					'<tr>'+
						'<td>'+escuela2.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela2.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(escuela2.ofertaEducativa[i].categoria == 'OTROS APOYOS Y RECURSOS')
			{
				ofertaEsc3 +=
					'<tr>'+
						'<td>'+escuela2.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela2.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
		}
	}
	else
	{
		ofertaEsc1 ='<tr>'+'<th colspan="2">No tiene actividades extra curriculares</th>'+'</tr>'
		ofertaEsc2 = '<tr>'+'<th colspan="2">No cuenta con actividades tecnologicas</th>'+'</tr>'
		ofertaEsc3 = '<tr>'+'<th colspan="2">No cuenta con otros apoyos</th>'+'</tr>'
	}

//programas
	if(escuela2.programas.length !=0)
	{
		programasEsc2 ='<tr>'+'<th colspan="2">Programas en la Escuela</th>'+'</tr>'
		for(i =0 ; i<escuela2.programas.length ; i++)
		{
		programasEsc2 +=
			'<tr>'+
				'<td>'+'Programa: '+'</td>'+
				'<td>'+escuela2.programas[i].desProg+'</td>'+
			'</tr>'
		}
	}
	else
	{
		programasEsc2 ='<tr>'+'<th colspan="2">No cuenta con ningun programa</th>'+'</tr>'
	}

//infraestructura
	if(escuela2.infra.length !=0)
	{
		infraestructuraEsc2 ='<tr>'+'<th colspan="2">Infraestructura Destacable</th>'+'</tr>'
		for(i =0 ; i<escuela2.infra.length ; i++)
		{
		infraestructuraEsc2 +=
			'<tr>'+
				'<td>'+'Infraestructura: '+'</td>'+
				'<td>'+escuela2.infra[i].desInfra+'</td>'+
			'</tr>'
		}
	}
	else
	{
		infraestructuraEsc2 ='<tr>'+'<th colspan="2">No cuenta con infraestructura</th>'+'</tr>'
	}


//otros logros
	if(escuela2.otrosDatos.length !=0)
	{
		otrosEsc2 ='<tr>'+'<th colspan="2">Otros Logros</th>'+'</tr>'
		logro1Esc2 = ""
		logro2Esc2 = ""
		logro3Esc2 = ""
		if(escuela2.otrosDatos[0].logro1 != "")
		{
			logro1Esc2 +=
				'<tr>'+
					'<td>'+'Logro 1'+'</td>'+
					'<td>'+
						'Logro: '+escuela2.otrosDatos[0].logro1+"<br>"+
						'Descripción: '+escuela2.otrosDatos[0].descripcion1+"<br>"+
						'Ciclo: '+escuela2.otrosDatos[0].ciclo1+"<br>"+
					'</td>'+
				'</tr>'			
		}
		if(escuela2.otrosDatos[0].logro2 != "")
		{
			logro2Esc2 +='<tr>'+
						'<td>'+'Logro 2'+'</td>'+
						'<td>'+
							'Logro: '+escuela2.otrosDatos[0].logro2+"<br>"+
							'Descripción: '+escuela2.otrosDatos[0].descripcion2+"<br>"+
							'Ciclo: '+escuela2.otrosDatos[0].ciclo2+"<br>"+
						'</td>'+
					  '</tr>'	
		}
		if(escuela2.otrosDatos[0].logro3 != "")
		{
			logro3Esc2 +='<tr>'+
						'<td>'+'Logro 3'+'</td>'+
						'<td>'+
							'Logro: '+escuela2.otrosDatos[0].logro3+"<br>"+
							'Descripción: '+escuela2.otrosDatos[0].descripcion3+"<br>"+
							'Ciclo: '+escuela2.otrosDatos[0].ciclo3+"<br>"+
						'</td>'+
					  '</tr>'		
		}

		if(logro1Esc2 == "" && logro2Esc2 == "" && logro3Esc2 == "" )
		{
			otrosEsc2 ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
		}
		else
		{
			otrosEsc2 += logro1Esc2 + logro2Esc2 + logro3Esc2
		}
	}
	else
	{
		otrosEsc2 ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
	}

	esc2.innerHTML = infoGenEsc2 + 
	"<table>"+
	infoEsc2 + ofertaEsc1 + ofertaEsc2 + ofertaEsc3 +programasEsc2 +infraestructuraEsc2+ otrosEsc2 +
	"</table>"

//escuela 3
	//info escuela
	infoGenEsc3 = '<h1>'+escuela3.infoEscuela.nombreCentroEducativo+'</h1>'+
				 '<p>Turno: '+escuela3.infoEscuela.turno+'</p>'+
			     '<p>Oferta: 200</p>'+
			     '<p>Demanda: 1000</p>'

	if(escuela3.otrosDatos.length !=0)
	{
	infoEsc3 = '<tr>'+'<th colspan="2">Información de la Escuela</th>'+'</tr>'+
			'<tr>'+
				'<td>'+'Responsable'+'</td>'+
				'<td>'+escuela3.otrosDatos[0].nombreResponsable+'</td>'+
			'</tr>'	
	}
	else
	{
	infoEsc3 = '<tr>'+'<th colspan="2">No cuenta con información</th>'+'</tr>'
	}

	//oferta educativa
	if(escuela3.ofertaEducativa.length != 0)
	{
		ofertaEsc1 ='<tr>'+'<th colspan="2">EXTRA CURRICULAR</th>'+'</tr>'
		ofertaEsc2 = '<tr>'+'<th colspan="2">TECNOLÓGICA</th>'+'</tr>'
		ofertaEsc3 = '<tr>'+'<th colspan="2">OTROS APOYOS Y RECURSOS</th>'+'</tr>'

		for(i =0 ; i<escuela3.ofertaEducativa.length ; i++)
		{
			if(escuela3.ofertaEducativa[i].categoria == 'EXTRA CURRICULAR')
			{
				ofertaEsc1 +=
					'<tr>'+
						'<td>'+escuela3.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela3.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(escuela3.ofertaEducativa[i].categoria == 'TECNOLÓGICA')
			{
				ofertaEsc2 +=
					'<tr>'+
						'<td>'+escuela3.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela3.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
			if(escuela3.ofertaEducativa[i].categoria == 'OTROS APOYOS Y RECURSOS')
			{
				ofertaEsc3 +=
					'<tr>'+
						'<td>'+escuela3.ofertaEducativa[i].subCategoria+'</td>'+
						'<td>'+escuela3.ofertaEducativa[i].descripcion+'</td>'+
					'</tr>'
			}
		}
	}
	else
	{
		ofertaEsc1 ='<tr>'+'<th colspan="2">No tiene actividades extra curriculares</th>'+'</tr>'
		ofertaEsc2 = '<tr>'+'<th colspan="2">No cuenta con actividades tecnologicas</th>'+'</tr>'
		ofertaEsc3 = '<tr>'+'<th colspan="2">No cuenta con otros apoyos</th>'+'</tr>'
	}

//programas
	if(escuela3.programas.length !=0)
	{
		programasEsc3 ='<tr>'+'<th colspan="2">Programas en la Escuela</th>'+'</tr>'
		for(i =0 ; i<escuela3.programas.length ; i++)
		{
		programasEsc3 +=
			'<tr>'+
				'<td>'+'Programa: '+'</td>'+
				'<td>'+escuela3.programas[i].desProg+'</td>'+
			'</tr>'
		}
	}
	else
	{
		programasEsc3 ='<tr>'+'<th colspan="2">No cuenta con ningun programa</th>'+'</tr>'
	}

//infraestructura
	if(escuela3.infra.length !=0)
	{
		infraestructuraEsc3 ='<tr>'+'<th colspan="2">Infraestructura Destacable</th>'+'</tr>'
		for(i =0 ; i<escuela3.infra.length ; i++)
		{
		infraestructuraEsc3 +=
			'<tr>'+
				'<td>'+'Infraestructura: '+'</td>'+
				'<td>'+escuela3.infra[i].desInfra+'</td>'+
			'</tr>'
		}
	}
	else
	{
		infraestructuraEsc3 ='<tr>'+'<th colspan="2">No cuenta con infraestructura</th>'+'</tr>'
	}


//otros logros
	if(escuela3.otrosDatos.length !=0)
	{
		otrosEsc3 ='<tr>'+'<th colspan="2">Otros Logros</th>'+'</tr>'
		logro1Esc3 = ""
		logro2Esc3 = ""
		logro3Esc3 = ""
		if(escuela3.otrosDatos[0].logro1 != "")
		{
			logro1Esc3 +=
				'<tr>'+
					'<td>'+'Logro 1'+'</td>'+
					'<td>'+
						'Logro: '+escuela3.otrosDatos[0].logro1+"<br>"+
						'Descripción: '+escuela3.otrosDatos[0].descripcion1+"<br>"+
						'Ciclo: '+escuela3.otrosDatos[0].ciclo1+"<br>"+
					'</td>'+
				'</tr>'			
		}
		if(escuela3.otrosDatos[0].logro2 != "")
		{
			logro2Esc3 +='<tr>'+
						'<td>'+'Logro 2'+'</td>'+
						'<td>'+
							'Logro: '+escuela3.otrosDatos[0].logro2+"<br>"+
							'Descripción: '+escuela3.otrosDatos[0].descripcion2+"<br>"+
							'Ciclo: '+escuela3.otrosDatos[0].ciclo2+"<br>"+
						'</td>'+
					  '</tr>'	
		}
		if(escuela3.otrosDatos[0].logro3 != "")
		{
			logro3Esc3 +='<tr>'+
						'<td>'+'Logro 2'+'</td>'+
						'<td>'+
							'Logro: '+escuela3.otrosDatos[0].logro3+"<br>"+
							'Descripción: '+escuela3.otrosDatos[0].descripcion3+"<br>"+
							'Ciclo: '+escuela3.otrosDatos[0].ciclo3+"<br>"+
						'</td>'+
					  '</tr>'		
		}

		if(logro1Esc3 == "" && logro2Esc3 == "" && logro3Esc3 == "" )
		{
			otrosEsc3 ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
		}
		else
		{
			otrosEsc3 += logro1Esc3 + logro2Esc3 + logro3Esc3
		}
	}
	else
	{
		otrosEsc3 ='<tr>'+'<th colspan="2">No cuenta con otros logros</th>'+'</tr>'
	}

	esc3.innerHTML = infoGenEsc3 + 
	"<table>"+
	infoEsc3 + ofertaEsc1 + ofertaEsc2 + ofertaEsc3 +programasEsc3 +infraestructuraEsc3+ otrosEsc3 +
	"</table>"
}

//funciones para autocompletado de direccion

function initAutocomplete() 
{
	// Create the autocomplete object, restricting the search to geographical
	// location types.
	autocomplete = new google.maps.places.Autocomplete(
	    /** @type {!HTMLInputElement} */(document.getElementById('cp')),
	    {types: ['geocode'],
		componentRestrictions: {country: "mx"}
		});
	return
}

function geolocate() 
{
        if (navigator.geolocation) {
          navigator.geolocation.getCurrentPosition(function(position) {
            var geolocation = {
              lat: position.coords.latitude,
              lng: position.coords.longitude
            };
            var circle = new google.maps.Circle({
              center: geolocation,
              radius: position.coords.accuracy
            });
            autocomplete.setBounds(circle.getBounds());
          });
        }
}


function getValorRadio()
{
	radio = document.getElementById('rangoRadio').value
	document.getElementById('pRadio').innerHTML = radio
	radio = radio *1000

	cambiarRadioEscuela()
}

function cambiarRadioEscuela()
{
	borrarMarcadores()
	borrarCirculo()
	loadMarkersInRadium()
}



$(document).ready(function(){

	if($('[data-toggle="tooltip"]').length >0)
	{
	$('[data-toggle="tooltip"]').tooltip();
  		//$('#tooltipAtletismo').tooltip();
	}

	  	$('#btnImprimir').click(function(){
	  		escuela1 = localStorage.getItem('escuela1')
			escuela2 = localStorage.getItem('escuela2')
			escuela3 = localStorage.getItem('escuela3')

			$('#escuela1Input').val(escuela1)
			$('#escuela2Input').val(escuela2)
			$('#escuela3Input').val(escuela3)

			$('#myForm').attr('target','_blank')
			$('#myForm').submit()
  });

})

