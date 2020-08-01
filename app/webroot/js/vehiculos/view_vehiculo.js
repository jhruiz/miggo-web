var printQR = function(){
    var mywindow = window.open('', 'PRINT', 'height=400,width=600');
    mywindow.document.write('<html><head>');
    mywindow.document.write('<style media=screen>body { font-family: Lucidatypewriter, monospace; font-size: 20px; } } </style>');
    mywindow.document.write('<style media=print>@page {margin: 5mm;} @page footer {page-break-after: always;} @page rotated {size: portrait} #tinfop {background-color:#FFF; font-family: Lucidatypewriter, monospace; font-size: 10px; } </style>');    
    mywindow.document.write('</head>');
    mywindow.document.write('<body>');
    mywindow.document.write('<div style="font-family:sans-serif; font-size:15px;">');
    mywindow.document.write($('#qr').html());  
    mywindow.document.write('</div>');          
    mywindow.document.write('</body></html>');
    mywindow.document.title = "Codigos QR";
    mywindow.document.close(); 
    mywindow.focus();
    mywindow.print();
    mywindow.close();    
};


function crearHtml(cliente){
    $('#qr').append('<div><img src="/app/img/cabecera_qr/torque8.png" alt="Brownies" width="155px" height="100px"><br>');
    generateQR();
    $('#qr').append('<br><div align="left" style="font: 120% sans-serif"><b style="color:#FCFC03;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' 
            + $("#placacodificado").val() + '</b></div></div>'); 
}


function generateQR(){
    
    var placacodificado = $("#placacodificado").val();

    $('#qr').qrcode({
        render: 'image',
        minVersion: 1,
        maxVersion: 40,
        ecLevel: 'L',
        left: 0,
        top: 0,
        size: 150,
        fill: '#000',
        background: null,
        text: placacodificado,
        radius: 0,
        quiet: 0,
        mode: 0,
        mSize: 0.1,
        mPosX: 0.5,
        mPosY: 0.5,
        label: 'no label',
        fontname: 'sans',
        fontcolor: '#000',
        image: null,
        download: 1
    });  
};

$(document).ready(function() {
    
    crearHtml();
    
    $('#print_qr').click(printQR);
});


