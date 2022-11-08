function printDiv(tabla) {
    // Obtenemos el numero del arete de un campo con id "arete"
    var numero_arete = document.getElementById("arete").textContent;
    // Obtenemos el html de la tabla
    var contenido_tabla = document.getElementById(tabla).innerHTML;

    // Damos formato al html que imprime el historial
    var contenido_html = `
    <!DOCTYPE html>
    <html>
        <head> 
            <link rel="stylesheet" href="../css/bootstrap.min.css"/>
        </head>
        <body> 
            <h4 class="mb-5 text-center">Vacunas aplicadas al bovino: ` + numero_arete + `</h4>
            ` + contenido_tabla + `
            <script>
                // Script para borrar la columna de acciones (botones)
                document.getElementById("thOpciones").remove();
                
                function borrarBotones(numFilas) {
                    if (numFilas == 0) return
                    else {
                        document.getElementsByClassName("tdOpciones")[numFilas - 1].remove();
                        borrarBotones(numFilas - 1);
                    }
                }

                borrarBotones(document.getElementsByClassName("tdOpciones").length);
            </script>
        </body>
    </html>`;



    // Abrimos una nueva ventana y agregamos el contenido html que formateamos previamente
    var a = window.open('', '', 'height=500, width=500');
    a.document.write(contenido_html);
    a.document.close();

    // Mandamos a imprimir la ventana generada con el historial
    a.print();

    // Cerramos la ventana
    a.close();
}