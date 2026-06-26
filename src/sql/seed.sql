-- Bodegas
INSERT INTO bodegas (nombre_bodega) VALUES
('Bodega Central'),
('Bodega Norte'),
('Bodega Sur');

-- Sucursales
INSERT INTO sucursales (id_bodega, nombre_sucursal, direccion) VALUES
(1, 'Sucursal Santiago Centro', 'Av. Libertador Bernardo O’Higgins 1234'),
(1, 'Sucursal Providencia', 'Av. Providencia 1000'),
(2, 'Sucursal La Serena', 'Av. Francisco de Aguirre 500'),
(3, 'Sucursal Puerto Montt', 'Av. Diego Portales 700');

-- Monedas
INSERT INTO monedas (nombre_moneda) VALUES
('Peso Chileno'),
('Peso Argentino'),
('Dólar'),
('Euro');

-- Materiales
INSERT INTO materiales (nombre_material) VALUES
('Plástico'),
('Metal'),
('Madera'),
('Vidrio'),
('Textil');