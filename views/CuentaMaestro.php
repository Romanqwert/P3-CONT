
<?php
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="../public/css/maestro.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>

    <div class="maestro-container">
    <!-- ENCABEZADO -->
    <div class="maestro-header">
        <h2>Maestro de Cuentas</h2>
    </div>

    <form id="formCuentas">
    <!-- DATOS PRINCIPALES -->


        <div class="form-grid">

            <div class=div-form>
                <label>Número de Cuenta</label>
            <input type="text" id="cuenta" onblur="fetchDatos(this.value)">


	        <label>Nivel de Cuenta</label>
            <input type="text" id="nivel" readonly>


            <label>Descripción</label>
            <input type="text" id="descripcion">
            
                <div class="div-cuenta">
                   
                <div class="div-select">
                     <label class="label-input label-cuenta">Origen de la Cuenta</label>
                    <select id="origen">
                        <option value="D">Débito</option>
                        <option value="C">Crédito</option>
                    </select>
                </div>

                     <div class="div-select">
                        <label class="label-input label-auxiliar">Módulo Auxiliar</label>
                        <select id="auxiliar">
                        <option value="C">CXC</option>
                        <option value="P">CXP</option>
                        <option value="N">   </option>
                    </select>
                     </div>
                </div>
            </div>


                <div class="div-check">
                    <label>Cuenta Control</label>
                    <input type="checkbox" id="control">

                    <label>Estados Financieros</label>
                    <input type="checkbox" id="estados">

                    <label>Cuenta de Impuestos</label>
                    <input type="checkbox" id="impuestos">
                </div>
           
        </div>

        <!-- BALANCES -->
        <div class="form-grid balances">
            <label>Balance Anterior</label>
            <input type="text" id="balAnt" readonly>

            <label>Débito</label>
            <input type="text" id="debMes" readonly>

            <label>Crédito</label>
            <input type="text" id="creMes" readonly>

            <label>Balance Actual</label>
            <input type="text" id="balAct" readonly>
        </div>


        <div class="header-botones">
            <button type="button" onclick="abrirModal()">🔍 Buscar</button>
            <button type="button" onclick="guardar()">💾 Guardar</button>
            <button type="button" onclick="borrar()">🗑️ Borrar</button>
            <button type="button" onclick="location.reload()">🆕 Nuevo</button>
        </div>

    </form>

</div>

<div id="modalBusqueda" class="modal" style="display:none">
    <div class="modal-content">
        <input type="text" placeholder="Filtrar catálogo..." onkeyup="filtrar(this.value)">
        <table id="tablaCuentas"><tbody></tbody></table>
        <button onclick="cerrarModal()">Cerrar</button>
    </div>
</div>
    
</body>
</html>
