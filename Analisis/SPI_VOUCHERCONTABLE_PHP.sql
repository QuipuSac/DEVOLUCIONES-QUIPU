
CREATE PROCEDURE [dbo].[SPI_VOUCHERCONTABLE_PHP]
@idTitDiario INT = 0 OUTPUT, -- en lugar de : @idDocumento int = 0 OUTPUT,
@Descripcion varchar(100) = '',
@FechaEmision datetime = '10/17/2018',
@idDiario CHAR(2) = '89',
@Correlativo int = 2 output,
@periodo int = 2018,
@mes int = 10
AS
DECLARE @diarioCierreApertura as varchar(5)

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
