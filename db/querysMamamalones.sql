/* [usebeqPruebas].[dbo].[A_CTBA] */
/* usebeqPruebas.dbo.A_CTBA */



SELECT * FROM usebeqPruebas.dbo.A_CTBA WHERE ES_ID = 1310;
SELECT * FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE es_id = 1310;
SELECT descripcion FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE idact = 3;

/* test */
SELECT infoEscuela.* , ofertaLista.* FROM 
usebeqPruebas.dbo.A_CTBA infoEscuela  JOIN usebeqPruebas.dbo.SCE008_OfertaLista ofertaLista ON infoEscuela.ES_ID = ofertaLista.es_id
WHERE infoEscuela.ES_ID=1310;



/*	selects de prueba	*/

/* ofertas educativas */
SELECT ofertas.idact ,ofertas.es_id, ofertaLista.descripcion FROM
usebeqPruebas.dbo.SCE008_OfertaLista ofertas JOIN usebeqPruebas.dbo.SCA_OfertaEducativa ofertaLista ON ofertas.Idact = ofertaLista.IdAct
WHERE ofertas.es_id = 1310;

/* infraestructuras*/
SELECT infra.idInfra , infra.es_id, infraLista.DesInfra FROM
usebeqPruebas.dbo.SCE008_Infraestructura infra JOIN usebeqPruebas.dbo.SCA_Infraestructura infraLista ON infra.idInfra = infraLista.IdInfra
WHERE infra.es_id = 1310;


/*  programas */
SELECT progra.idProg , progra.es_id, prograLista.DesProg FROM
usebeqPruebas.dbo.SCE008_Programas progra JOIN usebeqPruebas.dbo.SCA_Programas prograLista ON progra.idProg = prograLista.idprog
WHERE progra.es_id = 1310;

/*	otros	*/
SELECT * FROM usebeqPruebas.dbo.SCE008_Oferta WHERE es_id = 1310;








/*	JOINS :v	*/
SELECT info.* , oferta.*, ofertaLista.* , infra.*, infraLista.*, progra.*, prograLista.*, cat.* , subcat.* , otros.* 
FROM usebeqPruebas.dbo.A_CTBA info 
	INNER JOIN usebeqPruebas.dbo.SCE008_OfertaLista oferta on info.ES_ID = oferta.es_id
	INNER JOIN usebeqPruebas.dbo.SCA_OfertaEducativa ofertaLista ON oferta.Idact = ofertaLista.IdAct
	INNER JOIN usebeqPruebas.dbo.SCA_Categoria cat on ofertaLista.IdCat = cat.IdCat
	INNER JOIN usebeqPruebas.dbo.SCA_SubCategoria subcat on ofertaLista.IdSubCat = subcat.idSubCat 
	INNER JOIN usebeqPruebas.dbo.SCE008_Infraestructura infra ON info.ES_ID = infra.es_id
	INNER JOIN usebeqPruebas.dbo.SCA_Infraestructura infraLista ON infra.idInfra = infraLista.IdInfra
	INNER JOIN usebeqPruebas.dbo.SCE008_Programas progra ON info.ES_ID = progra.es_id
	INNER JOIN usebeqPruebas.dbo.SCA_Programas prograLista ON progra.idProg = prograLista.idprog
	INNER JOIN usebeqPruebas.dbo.SCE008_Oferta otros ON info.ES_ID = otros.es_id
WHERE info.ES_ID = 1310; 



/*filtros */

/*filtrar por categoria*/
SELECT info.ES_ID --,  oferta.Idact, ofertaLista.* , cat.Descripcion
FROM usebeqPruebas.dbo.A_CTBA info 
	LEFT JOIN usebeqPruebas.dbo.SCE008_OfertaLista oferta on info.ES_ID = oferta.es_id
	LEFT JOIN usebeqPruebas.dbo.SCA_OfertaEducativa ofertaLista ON oferta.Idact = ofertaLista.IdAct
	LEFT JOIN usebeqPruebas.dbo.SCA_Categoria cat on ofertaLista.IdCat = cat.IdCat
WHERE cat.Descripcion = 'EXTRA CURRICULAR';


SELECT idAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE idCat = (
	SELECT idCat FROM usebeqPruebas.dbo.SCA_Categoria WHERE descripcion = 'EXTRA CURRICULAR'
);

SELECT DISTINCT lista.* FROM usebeqPruebas.dbo.SCE008_OfertaLista lista JOIN (SELECT idAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE idCat = (
	SELECT idCat FROM usebeqPruebas.dbo.SCA_Categoria WHERE descripcion = 'EXTRA CURRICULAR'
)) AS oferta ON lista.Idact = oferta.idact


SELECT info.* 
FROM usebeqPruebas.dbo.A_CTBA info
	JOIN usebeqPruebas.dbo.SCE008_OfertaLista oferta on info.ES_ID = oferta.es_id
WHERE oferta.idAct = 1;




/*obtener todos los id de las actividades que tienen esa categoria */
/* seleciconar los ids de las escuelas que tienen esas actividades*/
/*
SELECT idAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE IdCat = (
SELECT idCat FROM usebeqPruebas.dbo.SCA_Categoria WHERE Descripcion = 'EXTRA CURRICULAR')
*/
/*SELECT es_id FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE Idact = ();*/  

-- Declaración del cursor 
DECLARE @idActTemp varchar(8000)
DECLARE @idEscuelasTemp varchar(8000)
DECLARE idActTemp CURSOR LOCAL SCROLL FOR  
SELECT idAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE IdCat = (
SELECT idCat FROM usebeqPruebas.dbo.SCA_Categoria WHERE Descripcion = 'EXTRA CURRICULAR')
-- Apertura del cursor 
OPEN  idActTemp
-- Lectura de la primera fila del cursor 
FETCH idActTemp INTO @idActTemp 
WHILE(@@FETCH_STATUS= 0) BEGIN 
SELECT DISTINCT @idEscuelasTemp = @idEscuelasTemp + es_id FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE Idact = @idActTemp;
-- PRINT @idActTemp
--PRINT @idEscuelasTemp
--SELECT DISTINCT es_id FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE Idact = @idActTemp;
-- Lectura de la siguiente fila de un cursor 
FETCH idActTemp INTO @idActTemp
-- Fin del bucle WHILE 
END 
-- Cierra el cursor 
CLOSE idActTemp
-- Libera los recursos del cursor 
DEALLOCATE idActTemp
PRINT @idEscuelasTemp

-------------------------------------
DECLARE @idEscuelasTemp varchar(8000)
DECLARE @idActTemp varchar(8000)
DECLARE idTemp CURSOR LOCAL SCROLL FOR
SELECT idAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE IdCat = (
SELECT idCat FROM usebeqPruebas.dbo.SCA_Categoria WHERE Descripcion = 'EXTRA CURRICULAR')
OPEN idTemp
FETCH NEXT FROM idTemp
WHILE @@FETCH_STATUS = 0
   BEGIN
      --FETCH NEXT FROM idTemp INTO @idActTemp
	  --SELECT DISTINCT  @idEscuelasTemp = @idEscuelasTemp + es_id FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE Idact = @idActTemp;
	  --PRINT @idEscuelasTemp
	  FETCH idTemp INTO @idActTemp
	  SELECT DISTINCT es_id FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE Idact = @idActTemp;
   END
CLOSE idTemp
DEALLOCATE idTemp
GO















/*filtrar por subcategoria*/
SELECT info.* , oferta.*, ofertaLista.* , subcat.*
FROM usebeqPruebas.dbo.A_CTBA info 
	INNER JOIN usebeqPruebas.dbo.SCE008_OfertaLista oferta on info.ES_ID = oferta.es_id
	INNER JOIN usebeqPruebas.dbo.SCA_OfertaEducativa ofertaLista ON oferta.Idact = ofertaLista.IdAct
	INNER JOIN usebeqPruebas.dbo.SCA_SubCategoria subcat on ofertaLista.IdSubCat = subcat.idSubCat 
WHERE  subcat.Descripcion='deportivas'; 
/* obtener las actividades que tienen cierta subcategoria*/
SELECT IdAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE IdSubCat = (
	SELECT IdSubCat FROM usebeqPruebas.dbo.SCA_SubCategoria WHERE Descripcion = 'deportivas')




/*filtrar por oferta educativa*/
/*	obtener los ids de las escuelas que tienen cierta oferta educativa	*/
SELECT es_id FROM usebeqPruebas.dbo.SCE008_OfertaLista WHERE idAct = (
	SELECT idAct FROM usebeqPruebas.dbo.SCA_OfertaEducativa WHERE Descripcion = 'Atletismo'
	)

SELECT DISTINCT info.ES_ID, info.CLAVECCT , info.LAT , info.LON FROM usebeqPruebas.dbo.A_CTBA info 
	JOIN usebeqPruebas.dbo.SCE008_OfertaLista lista on lista.es_id = info.ES_ID
	JOIN usebeqPruebas.dbo.SCA_OfertaEducativa oferta on lista.Idact = oferta.IdAct
	WHERE oferta.Descripcion = 'Atletismo'


/*	filtrar por infraestructura */
--obtener los ids de las escuelas que tienen cierta infraestructura
SELECT es_id FROM usebeqPruebas.dbo.SCE008_Infraestructura WHERE idinfra = (
	SELECT IdInfra FROM usebeqPruebas.dbo.SCA_Infraestructura WHERE DesInfra = 'Arcotecho'
	)

--query chido karnal
SELECT DISTINCT info.ES_ID, info.CLAVECCT , info.LAT , info.LON FROM usebeqPruebas.dbo.A_CTBA info 
	JOIN usebeqPruebas.dbo.SCE008_Infraestructura infra on info.ES_ID = infra.es_id
	join usebeqPruebas.dbo.SCA_Infraestructura desInfra on infra.idInfra = desInfra.IdInfra
	WHERE desInfra.DesInfra = 'Arcotecho' or desInfra.Desinfra = 'Laboratorios'


/*	filtrar por programas	*/
SELECT info.ES_ID, info.CLAVECCT , info.LAT , info.LON FROM usebeqPruebas.dbo.A_CTBA info
	JOIN usebeqPruebas.dbo.SCE008_Programas listProg on listProg.es_id = info.ES_ID
	JOIN usebeqPruebas.dbo.SCA_Programas progDes on listProg.idProg = progDes.idprog
	WHERE progDes.DesProg = 'Apoyo USAER'


-- obtener informacion de la escuela segun su clave
--info escuela
--SELECT * FROM dbo.A_CTBA WHERE CLAVECCT ='22DST0025C'

SELECT * FROM dbo.A_CTBA WHERE CLAVECCT ='22DTV0168N'
--id 1470
--get oferta que tiene la escuela
SELECT listaOferta.es_id , oferta.Descripcion , cat.Descripcion AS desCat, subCat.Descripcion AS desSubCat FROM dbo.SCE008_OfertaLista listaOferta 
	JOIN dbo.SCA_OfertaEducativa oferta on listaOferta.Idact = oferta.IdAct
	JOIN dbo.SCA_Categoria cat on oferta.IdCat = cat.IdCat
	JOIN dbo.SCA_SubCategoria subCat on oferta.IdSubCat = subCat.IdSubCat
WHERE listaOferta.es_id = 1470

SELECT * FROM SCE008_OfertaLista WHERE es_id = 1470

--get infraestrcutura que tiene la escuela
SELECT listaInfra.* , infra.* FROM dbo.SCE008_Infraestructura listaInfra
	JOIN dbo.SCA_Infraestructura  infra on listaInfra.idInfra = infra.IdInfra
WHERE  listaInfra.es_id = 1313

-- getProgramas
SELECT listaProg.* , prog.* FROM dbo.SCE008_Programas listaProg
	JOIN dbo.SCA_Programas prog on listaProg.idProg = prog.idprog
WHERe listaProg.es_id = 1313

--get informacion faltante
SELECT * FROM dbo.SCE008_Oferta WHERE es_id = 1313





SELECT listaOferta.es_id , oferta.Descripcion , cat.Descripcion AS desCat, subCat.Descripcion AS desSubCat 
FROM dbo.SCE008_OfertaLista listaOferta 
JOIN dbo.SCA_OfertaEducativa oferta on listaOferta.Idact = oferta.IdAct 
JOIN dbo.SCA_Categoria cat on oferta.IdCat = cat.IdCat 
JOIN dbo.SCA_SubCategoria subCat on oferta.IdSubCat = subCat.IdSubCat 
WHERE listaOferta.es_id =1313



SELECT DISTINCT COUNT(*) AS cont FROM dbo.A_CTBA info 
JOIN dbo.SCE008_Infraestructura infra on info.ES_ID = infra.es_id 
JOIN dbo.SCA_Infraestructura desInfra on infra.idInfra = desInfra.IdInfra 
WHERE desInfra.DesInfra = 'Cancha de fútbol' COLLATE Latin1_General_CS_AI


SELECT DISTINCT COUNT(*) AS cont FROM dbo.A_CTBA info 
JOIN dbo.SCE008_Infraestructura infra on info.ES_ID = infra.es_id 
JOIN dbo.SCA_Infraestructura desInfra on infra.idInfra = desInfra.IdInfra 