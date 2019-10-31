$(document).ready(function() {
    //------------>
    $('div.modal-body table.table').on('change', '#unidades', function() {
        __this = $(this).closest('tr');
        console.log('click');

        var cantidades = __this.find('td:nth-child(3) input').val();
        var precios = __this.find('td:nth-child(7)').text();
        var total = cantidades * precios;
        __this.find('td:nth-child(8)').text(total);
    });
    //------------>
    $('div.modal-body table.table').on('click', 'button#btn-enviar', function() {
        __this = $(this).closest('tr');

        $('#resultados table.table').prepend(__this);

        $("#calcular").trigger("click");
    });
    //------------>
    $('#calcular').on('click', function() {
        console.log($_POST_AJAX);

        $$this = $(this);
        $total = 0;
        $('#resultados table.table').find('tr').map(function() {

            $total += parseFloat($(this).find('td:nth-child(8)').text());
        });
        $('#resultados ol').remove();
        $('#resultados').append('<ol><li>total : ' + $total + '</li></ol>');

        $_POST_AJAX[6] = $total;
    });
    //------------>
    $('#resultados table.table').on('click', 'button#btn-enviar', function() {

        $this = $(this).closest('tr')
        $('div.modal-body div.table-responsive table.table').prepend($this);

        $("#calcular").trigger("click");
    });
    //------------>
    $('.herramientas input#btn-send-all').on('click', function() {
        if ($('input#btn-send-all').prop('checked')) {
            $('div.modal-body table.table button#btn-enviar').trigger('click');
            $('label[for="' + $(this).attr('id') + '"]').text('trae de regreso');
        } else {
            $('label[for="' + $(this).attr('id') + '"]').text('cancelar todo');
            $('#resultados table.table button#btn-enviar').trigger('click');
        }
    });
    //------------>
    $('#btn-guardar-pedido').on('click', function(e) {
        console.clear();
        $this = $(this);
        $tablaCancelados = $('#resultados div.table-responsive table.table');
        $fila = new Array();
        $numfila = 0;
        $tablaCancelados.find('tr').map(function() {
            $tr = $(this);
            $linea = new Array();
            $linea[0] = $tr.find('td:nth-child(2)').text();
            $linea[1] = $tr.find('td:nth-child(3) input').val();
            $linea[2] = $tr.find('td:nth-child(4)').text();
            $linea[3] = $tr.find('td:nth-child(6)').text();
            $linea[4] = $tr.find('td:nth-child(7)').text();
            $linea[5] = $tr.find('td:nth-child(8)').text();
            $fila[$numfila] = $linea
            $numfila++;
        });
        var $i = 0
        console.log($fila);


        $.ajax({
            type: "post",
            dataType: "html",
            url: "GuardarDevolucionesQuipu.php",
            data: {
                'accion': 'Guardar Devolucion',
                'datos': $fila,
                'datos_post': $_POST_AJAX
            },
            success: function(response) {
                $('body').html(response);
                console.log(response)
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });


    });

});