


SELECT * 
FROM TBDOCUMENTOS
WHERE IDTIPODOCU = 215 
ORDER BY 1 DESC


SELECT * FROM TBDOCUMENTOS WHERE SERIDOCU = '20198858' AND NUMEDOCU = '314'
--55611


SELECT * FROM TBCAJAS WHERE IDDOCUMENTOCAJA = 55611

SELECT * FROM TBDOCUMENTOS WHERE SERIDOCU = '20198858' AND NUMEDOCU = '314'


SELECT TBDOCUMENTOS.* 
FROM TBDOCUMENTOS 
INNER JOIN TBCAJAS ON TBCAJAS.IDDOCUMENTOOPERACION = TBDOCUMENTOS.IDDOCUMENTO
WHERE TBCAJAS.IDDOCUMENTOCAJA IN 
(SELECT IDDOCUMENTO FROM TBDOCUMENTOS WHERE SERIDOCU = '20198858' AND NUMEDOCU = '314')


SELECT * FROM TBDETCOMERCIAL

--55613

SELECT * FROM TBDETCOMERCIAL WHERE IDDOCUMENTO = 55613



SELECT TBDETCOMERCIAL.* 
FROM TBDETCOMERCIAL 
INNER JOIN TBDOCUMENTOS ON TBDOCUMENTOS.IDDOCUMENTO = TBDETCOMERCIAL.IDDOCUMENTO
WHERE TBDOCUMENTOS.NUMEDOCU = '41349'

--modal consulta



SELECT 
tbItems.Codigo				,		tbDetComercial.Cantidad ,
tbDetComercial.Descripcion	,		tbunidades.descripcion , 
tbDetComercial.precio		,		tbdetcomercial.totalmn
FROM TBDETCOMERCIAL 
INNER JOIN TBDOCUMENTOS ON TBDOCUMENTOS.IDDOCUMENTO = TBDETCOMERCIAL.IDDOCUMENTO
INNER JOIN tbItems on tbItems.idItem = TBDETCOMERCIAL.idItem
INNER JOIN tbunidades on tbunidades.idunidad = TBDETCOMERCIAL.idunidad
WHERE TBDOCUMENTOS.NUMEDOCU = '41323'  and TBDOCUMENTOS.nombreClieProv = 'CARLOS HUARINGA' -- > id numdoc 

--- movidas de script
  /*var registro = String;
        var linea = __this.find('td').map(function() {
            $this = $(this);
            if ($this.find('input').length)
                return $this.val();
            else
                return $this.text();
        }).get();
        registro += '<tr>';
        jQuery.each(linea, function(i, val) {
            if (i == 0) {

                registro += '<td><button id="devolver-' + i + '">' + val + '</button></td>';
            } else {

                registro += '<td>' + val + '</td>';
            }
        });
        registro += '</tr>';*/








