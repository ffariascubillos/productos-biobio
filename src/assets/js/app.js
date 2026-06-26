document.addEventListener('DOMContentLoaded', () => {
  const formulario = document.getElementById('form-producto');
  const selectBodega = document.getElementById('bodega');
  const selectSucursal = document.getElementById('sucursal');
  const selectMoneda = document.getElementById('moneda');

  const endpoints = {
    bodegas: './src/api/obtener-bodegas.php',
    monedas: './src/api/obtener-monedas.php',
    sucursales: './src/api/obtener-sucursales.php',
    guardarProducto: './src/api/guardar-producto.php',
  };

  // Deja un select solo con la primera opcion vacia.
  function limpiarSelect(select) {
    select.innerHTML = '';

    const opcionVacia = document.createElement('option');
    opcionVacia.value = '';
    opcionVacia.textContent = '';
    select.appendChild(opcionVacia);
  }

  function limpiarFormulario() {
    formulario.reset();
    limpiarSelect(selectSucursal);
  }

  // Agrega opciones al select usando los campos recibidos desde el endpoint.
  function agregarOpciones(select, datos, campoValor, campoTexto) {
    datos.forEach((item) => {
      const opcion = document.createElement('option');
      opcion.value = item[campoValor];
      opcion.textContent = item[campoTexto];
      select.appendChild(opcion);
    });
  }

  // Carga datos JSON en un select y conserva la opcion vacia inicial.
  async function cargarSelect(select, url, campoValor, campoTexto) {
    limpiarSelect(select);

    const respuesta = await fetch(url);
    const resultado = await respuesta.json();

    if (!resultado.success) {
      alert(resultado.message);
      return;
    }

    agregarOpciones(select, resultado.data, campoValor, campoTexto);
  }

  // Carga las sucursales dependientes de la bodega seleccionada.
  async function cargarSucursales() {
    const idBodega = selectBodega.value;
    limpiarSelect(selectSucursal);

    if (idBodega === '') {
      return;
    }

    const url = `${endpoints.sucursales}?id_bodega=${encodeURIComponent(idBodega)}`;

    await cargarSelect(selectSucursal, url, 'id_sucursal', 'nombre_sucursal');
  }

  async function iniciarCargaInicial() {
    await cargarSelect(
      selectBodega,
      endpoints.bodegas,
      'id_bodega',
      'nombre_bodega',
    );
    await cargarSelect(
      selectMoneda,
      endpoints.monedas,
      'id_moneda',
      'nombre_moneda',
    );
    limpiarSelect(selectSucursal);
  }

  selectBodega.addEventListener('change', cargarSucursales);

  // Envia el formulario por AJAX despues de ejecutar validaciones, si existen.
  formulario.addEventListener('submit', async (event) => {
    event.preventDefault();

    if (
      typeof validarFormulario === 'function' &&
      validarFormulario(formulario) === false
    ) {
      return;
    }

    try {
      const datosFormulario = new FormData(formulario);
      const respuesta = await fetch(endpoints.guardarProducto, {
        method: 'POST',
        body: datosFormulario,
      });

      const resultado = await respuesta.json();
      alert(resultado.message);

      if (resultado.success) {
        limpiarFormulario();
      }
    } catch (error) {
      alert('No se pudo procesar la solicitud.');
    }
  });

  iniciarCargaInicial();
});
