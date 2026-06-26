function obtenerValor(formulario, nombreCampo) {
  return formulario.elements[nombreCampo].value.trim();
}

function contarMaterialesSeleccionados(formulario) {
  return formulario.querySelectorAll('input[name="materiales[]"]:checked').length;
}

function validarCodigoProducto(codigo) {
  const formatoAlfanumerico = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]+$/;

  if (codigo === '') {
    alert('El código del producto no puede estar en blanco.');
    return false;
  }

  if (!formatoAlfanumerico.test(codigo)) {
    alert('El código del producto debe contener letras y números');
    return false;
  }

  if (codigo.length < 5 || codigo.length > 15) {
    alert('El código del producto debe tener entre 5 y 15 caracteres.');
    return false;
  }

  return true;
}

function validarNombreProducto(nombre) {
  if (nombre === '') {
    alert('El nombre del producto no puede estar en blanco.');
    return false;
  }

  if (nombre.length < 2 || nombre.length > 50) {
    alert('El nombre del producto debe tener entre 2 y 50 caracteres.');
    return false;
  }

  return true;
}

function validarSelect(valor, mensaje) {
  if (valor === '') {
    alert(mensaje);
    return false;
  }

  return true;
}

function validarPrecio(precio) {
  const formatoPrecio = /^(?!0+(?:\.0{1,2})?$)\d+(?:\.\d{1,2})?$/;

  if (precio === '') {
    alert('El precio del producto no puede estar en blanco.');
    return false;
  }

  if (!formatoPrecio.test(precio)) {
    alert('El precio del producto debe ser un número positivo con hasta dos decimales.');
    return false;
  }

  return true;
}

function validarMateriales(totalMateriales) {
  if (totalMateriales < 2) {
    alert('Debe seleccionar al menos dos materiales para el producto.');
    return false;
  }

  return true;
}

function validarDescripcion(descripcion) {
  if (descripcion === '') {
    alert('La descripción del producto no puede estar en blanco.');
    return false;
  }

  if (descripcion.length < 10 || descripcion.length > 1000) {
    alert('La descripción del producto debe tener entre 10 y 1000 caracteres.');
    return false;
  }

  return true;
}

function validarFormulario(formulario) {
  const codigo = obtenerValor(formulario, 'cod_pro');
  const nombre = obtenerValor(formulario, 'nombre_pro');
  const bodega = obtenerValor(formulario, 'bodega');
  const sucursal = obtenerValor(formulario, 'sucursal');
  const moneda = obtenerValor(formulario, 'moneda');
  const precio = obtenerValor(formulario, 'precio');
  const descripcion = obtenerValor(formulario, 'descripcion');
  const totalMateriales = contarMaterialesSeleccionados(formulario);

  return (
    validarCodigoProducto(codigo) &&
    validarNombreProducto(nombre) &&
    validarSelect(bodega, 'Debe seleccionar una bodega.') &&
    validarSelect(sucursal, 'Debe seleccionar una sucursal para la bodega seleccionada.') &&
    validarSelect(moneda, 'Debe seleccionar una moneda para el producto.') &&
    validarPrecio(precio) &&
    validarMateriales(totalMateriales) &&
    validarDescripcion(descripcion)
  );
}
