

CREATE PROCEDURE [dbo].[SPI_VOUCHERCONTABLE_PHP]
@idTitDiario INT = 0 OUTPUT, -- en lugar de : @idDocumento int = 0 OUTPUT,
@Descripcion varchar(100) = '',
@idDiario CHAR(2) = '89',
@Correlativo int = 2 output,
@periodo int = 2018,
@mes int = 10
AS
DECLARE @diarioCierreApertura as varchar(5)

DECLARE @FechaEmision DATETIME

SET @FechaEmision = (SELECT GETDATE())

if @Correlativo <= 0
begin
set @Correlativo = isnull((SELECT DBO.F_NUMERO_DIARIO_GRAL (@Periodo, @Mes, @idDiario)),0)
end
INSERT INTO TBTITDIARIO(
Descripcion,			fechaEmision,			idDiario,			Correlativo,  Periodo, Mes
) VALUES(
@Descripcion,			@fechaEmision,			@idDiario,			@Correlativo, @Periodo, @mes
)
set @idTitDiario  = (SELECT @@identity)
GO
--
CREATE PROCEDURE SPI_DOC_CLIE_PROV_GRIFO_PHP
@idDocumento int = 0 OUTPUT,
@idDiario char(2) = '40',
@idTipoDocu	int	= 1,
@SeriDocu varchar(20) = '000',
@NumeDocu varchar(50) = '0000011',
@Correlativo int = 0 OUTPUT,
@RUC varchar (50) = '10278504',
@NombreClieProv	varchar	(150) = 'RICARDO ALEJOS',
@Descripcion varchar (100) = 'XXXX',
@idFormaPago INT  = 0,
@Moneda	char (1) = 'S',
@idTica	int = 1,
@BaseI decimal (18,2) = 0,
@Inafecto decimal (18,2) = 0,
@Igv decimal (18,2)= 0,
@Total decimal (18,2)= 0,
@Observaciones varchar (50) = 'OBS',
--@FechaEmision datetime = '10/10/2010',
--@FechaVencimiento datetime = '10/10/2010',
@idDocumentoAnterior int = 0,
@idTitDiario int = 12,
@TC	decimal	(18,4) = 1,
@TotalME decimal(18,2) = 0,
@idTOperacion varchar(5) = 'OVG',
@Detraccion char(1) = 'N',
@idTipoDocuReferencia int = 0,
@SeriDocuReferencia varchar(20) = '',
@NumeDocuReferencia varchar(50) = '',
--@FechaDocuReferencia datetime = '10/10/2010',
@Otros decimal(18,2) = 0,
@TDET varchar(6) = 0,
@IDET DECIMAL(18,2) = 0,
@DIRECCIONCLIEPROV VARCHAR(150) = '',
@idvendedor int=1
--@DireccionLlegada varchar(200)=''
AS
DECLARE @FechaEmision datetime 
declare @FechaServer datetime
declare @Periodo int
declare @Mes int
declare @idClieProv int
declare @idDocReferencia int

set @FechaServer = (select getdate())
set @periodo = year (@FechaServer)
set @Mes = month(@FechaServer)
set @Correlativo = isnull((SELECT DBO.F_NUMERO_DIARIO_GRAL (@Periodo, @Mes, @idDiario)),0)
set @idClieProv = isnull((select idclieprov from tbclieprov where ruc = @RUC ),1)
set @idDocReferencia = isnull((select idDocumento
from tbdocumentos
where idtipodocu = @idtipodocu
and SeriDocu = @SeriDocu
and numedocu = @numedocu),
0)

if @idvendedor=0
begin
set @idvendedor=1
end
--set @Correlativo = (SELECT DBO.F_COMPROBANTE_VENTAS (@idDiario, @periodo, @Mes))
set @Correlativo  = isnull((select correlativo from tbtitdiario where idtitdiario = @idTitDiario),-1)
-- ver si solo se usa para nota de credito

SET @idDocumento = -1

INSERT INTO TBDOCUMENTOS (
idDiario,					idTipoDocu,					SeriDocu,					NumeDocu,
Correlativo,				idClieProv,					RUC,						NombreClieProv,
Descripcion,				idFormaPago,				Moneda,						idTica,
BaseI,						Inafecto,					Igv,						Total,
Observaciones,				FechaServer,				FechaEmision,				FechaVencimiento,
Estado,						idDocumentoAnterior,		Periodo,					Mes,
idTitDiario,				TC,							TotalME,					idTOperacion,
Detraccion,					idTipoDocuReferencia,		SeriDocuReferencia,			NumeDocuReferencia,
FechaDocuReferencia,		Otros,						FechaRegistro,				TDET,
IDET,						DireccionClieProv,			idvendedor,					DireccionLlegada
)VALUES (
@idDiario,					@idTipoDocu,				@SeriDocu,					@NumeDocu,
@Correlativo,				@idClieProv,				@RUC,						@NombreClieProv,
@Descripcion,				@idFormaPago,				@Moneda,					@idTica,
@BaseI,						@Inafecto,					@Igv,						@Total,
@Observaciones,				@FechaServer,				@FechaServer,				@FechaServer,
'R',						@idDocumentoAnterior,		@Periodo,					@Mes,
@idTitDiario,				@TC,						@TotalME,					@idTOperacion,
@Detraccion,				@idTipoDocuReferencia,		@SeriDocuReferencia,		@NumeDocuReferencia,
@FechaServer,				@Otros,						@FechaServer,				@TDET,
@IDET,						@DIRECCIONCLIEPROV,			@idvendedor,				@DIRECCIONCLIEPROV
)

SET @idDocumento = @@identity

GO



CREATE procedure SPD_DEVOLUCIONES 
@IDDOCUMENTO INT = 63530
AS
BEGIN 
	delete from tbcajas where idDocumentoOperacion = @IDDOCUMENTO
	delete from tbdetcomercial where iddocumento = @IDDOCUMENTO
	delete from  tbdocumentos where idDocumento = @IDDOCUMENTO

END



