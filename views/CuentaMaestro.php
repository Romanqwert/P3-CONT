<div class="maestro-container">
    <!-- ENCABEZADO -->
    <div class="maestro-header">
        <h2>Maestro de Cuentas</h2>

        <div class="header-botones">
            <button type="button" onclick="abrirModal()">🔍 Buscar</button>
            <button type="button" onclick="guardar()">💾 Guardar</button>
            <button type="button" onclick="borrar()">🗑️ Borrar</button>
            <button type="button" onclick="location.reload()">🆕 Nuevo</button>
        </div>
    </div>

    <form id="formCuentas">
    <!-- DATOS PRINCIPALES -->


        <div class="form-grid">

            <label>Número de Cuenta</label>
            <input type="text" id="cuenta" onblur="fetchDatos(this.value)">


	    <label>Nivel de Cuenta</label>
            <input type="text" id="nivel" readonly>


            <label>Descripción</label>
            <input type="text" id="descripcion">

            <label>Origen de la Cuenta</label>
            <select id="origen">
                <option value="D">Débito</option>
                <option value="C">Crédito</option>
            </select>

            <label>Módulo Auxiliar</label>
            <select id="auxiliar">
                <option value="C">CXC</option>
                <option value="P">CXP</option>
                <option value="N">   </option>
            </select>

            <label>Cuenta Control</label>
            <input type="checkbox" id="control">

            <label>Estados Financieros</label>
            <input type="checkbox" id="estados">

            <label>Cuenta de Impuestos</label>
            <input type="checkbox" id="impuestos">
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

    </form>
</div>

<div id="modalBusqueda" class="modal" style="display:none">
    <div class="modal-content">
        <input type="text" placeholder="Filtrar catálogo..." onkeyup="filtrar(this.value)">
        <table id="tablaCuentas"><tbody></tbody></table>
        <button onclick="cerrarModal()">Cerrar</button>
    </div>
</div>