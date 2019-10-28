$(document).ready(function() {

    $('div.modal-body table.table').on('change', '.precio', function() {
        __this = $(this).closest('tr');
        var cantidades = __this.find('td:nth-child(3) input').val();
        var precios = __this.find('td:nth-child(6)').text();
        var total = cantidades * precios;
        __this.find('td:nth-child(7)').text(total);
    });
    $('div.modal-body table.table').on('click', 'button#btn-enviar', function() {
        __this = $(this).closest('tr');

        $('#resultados table.table').prepend(__this);

        $("#calcular").trigger("click");
    });
    $('#calcular').on('click', function() {
        $$this = $(this);
        $total = 0;
        $('#resultados table.table').find('tr').map(function() {

            $total += parseInt(String($(this).find('td:nth-child(7)').text()));
        });
        $('#resultados ol').remove();
        $('#resultados').append('<ol><li>total : ' + $total + '</li></ol>');
    });
    $('#resultados table.table').on('click', 'button#btn-enviar', function() {

        $this = $(this).closest('tr')
        $('div.modal-body div.table-responsive table.table').prepend($this);

        $("#calcular").trigger("click");
    });
    $('.herramientas input#btn-send-all').on('click', function() {
        if ($('input#btn-send-all').prop('checked')) {
            $('div.modal-body table.table button#btn-enviar').trigger('click');
            $('label[for="' + $(this).attr('id') + '"]').text('trae de regreso');
        } else {
            $('label[for="' + $(this).attr('id') + '"]').text('cancelar todo');
            $('#resultados table.table button#btn-enviar').trigger('click');
        }
    })

});