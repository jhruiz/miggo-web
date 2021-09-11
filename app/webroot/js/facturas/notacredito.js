
var opcNotaCredito = {
    autoOpen: false,
    modal: true,
    width: 700,
    height: 650,
    position: [400, 50],
    show: {
        duration: 400    
    },
    title: 'Métodos de devolución de dinero'    
};

var dialogNotaCredito;

function generarNotaCredito(facturaId){
        $("#div_notacredito").load(
            $('#url-proyecto').val() + "facturas/datosnotacredito",
            {facturaId: facturaId},
            function(){                                                            
                dialogNotaCredito=$("#div_notacredito").dialog(opcNotaCredito);
                dialogNotaCredito.dialog('open');
            }
        );
}