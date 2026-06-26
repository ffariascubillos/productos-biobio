-- =====================================================
-- Base de Datos: Sistema de Registro de Productos
-- PostgreSQL
-- =====================================================

-- ==========================================
-- TABLA: BODEGAS
-- ==========================================
CREATE TABLE bodegas (
    id_bodega SERIAL PRIMARY KEY,
    nombre_bodega VARCHAR(100) NOT NULL
);

-- ==========================================
-- TABLA: SUCURSALES
-- ==========================================
CREATE TABLE sucursales (
    id_sucursal SERIAL PRIMARY KEY,
    id_bodega INTEGER NOT NULL,
    nombre_sucursal VARCHAR(100) NOT NULL,
    direccion VARCHAR(200),

    CONSTRAINT fk_sucursal_bodega
        FOREIGN KEY (id_bodega)
        REFERENCES bodegas(id_bodega)
);

-- ==========================================
-- TABLA: MONEDAS
-- ==========================================
CREATE TABLE monedas (
    id_moneda SERIAL PRIMARY KEY,
    nombre_moneda VARCHAR(50) NOT NULL
);

-- ==========================================
-- TABLA: MATERIALES
-- ==========================================
CREATE TABLE materiales (
    id_material SERIAL PRIMARY KEY,
    nombre_material VARCHAR(100) NOT NULL
);

-- ==========================================
-- TABLA: PRODUCTOS
-- ==========================================
CREATE TABLE productos (
    id_producto SERIAL PRIMARY KEY,
    codigo_producto VARCHAR(15) NOT NULL UNIQUE,
    nombre_producto VARCHAR(50) NOT NULL,
    descripcion TEXT NOT NULL,
    precio DECIMAL(10,2) NOT NULL,

    id_bodega INTEGER NOT NULL,
    id_sucursal INTEGER NOT NULL,
    id_moneda INTEGER NOT NULL,

    fecha_creacion TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_producto_bodega
        FOREIGN KEY (id_bodega)
        REFERENCES bodegas(id_bodega),

    CONSTRAINT fk_producto_sucursal
        FOREIGN KEY (id_sucursal)
        REFERENCES sucursales(id_sucursal),

    CONSTRAINT fk_producto_moneda
        FOREIGN KEY (id_moneda)
        REFERENCES monedas(id_moneda)
);

-- ==========================================
-- TABLA: PRODUCTO_MATERIAL
-- Relación muchos a muchos
-- ==========================================
CREATE TABLE producto_material (
    id_producto INTEGER NOT NULL,
    id_material INTEGER NOT NULL,

    PRIMARY KEY (id_producto, id_material),

    CONSTRAINT fk_pm_producto
        FOREIGN KEY (id_producto)
        REFERENCES productos(id_producto)
        ON DELETE CASCADE,

    CONSTRAINT fk_pm_material
        FOREIGN KEY (id_material)
        REFERENCES materiales(id_material)
);