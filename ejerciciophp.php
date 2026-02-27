<?php
$salario_total = null;
$nombre_vendedor = "";

if (isset($_POST['enviar'])) {

    $SALARIO_BASICO = 737000;
    $COMISION_POR_AUTO = 50000;
    $PORCENTAJE_VENTA = 0.05;
 
    $nombre_vendedor = $_POST['nombre'];
    $cantidad_vendida = (int)$_POST['cantidad'];
    $valor_total_ventas = (float)$_POST['valor_ventas'];

    $total_comision_fija = $cantidad_vendida * $COMISION_POR_AUTO;
    $total_comision_variable = $valor_total_ventas * $PORCENTAJE_VENTA;
    
    $salario_total = $SALARIO_BASICO + $total_comision_fija + $total_comision_variable;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cálculo Salario Vendedor</title>
    <style>

        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 50px;
            background-color: #f0f0f0;
        }
        .form-container {
            background-color: #0019d3; 
            padding: 25px;
            border-radius: 15px;
            color: black;
            width: 450px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        }
        .form-group {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        label {
            flex: 1;
            font-weight: bold;
            font-size: 0.9em;
        }
        input[type="text"], input[type="number"] {
            flex: 1;
            padding: 6px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #e0e0e0;
            border: 1px solid #999;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
        }
        .resultado-caja {
            margin-top: 20px;
            background-color: white;
            padding: 15px;
            border-radius: 10px;
            color: #333;
        }
        hr { border: 0; border-top: 1px solid #ccc; }
    </style>
</head>
<body>

<div class="form-container">
    <form method="post" action="">
        <div class="form-group">
            <label>Nombre del vendedor:</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label>Cantidad automóviles vendidos:</label>
            <input type="number" name="cantidad" min="0" required>
        </div>
        <div class="form-group">
            <label>Precio total automóviles vendidos:</label>
            <input type="number" name="valor_ventas" min="0" required>
        </div>
        
        <input type="submit" name="enviar" value="Enviar">
    </form>

    <?php if ($salario_total !== null): ?>
        <div class="resultado-caja">
            <h3>Resumen de Pago</h3>
            <p>Vendedor: <strong><?php echo htmlspecialchars($nombre_vendedor); ?></strong></p>
            <hr>
            <p>Salario Básico: $737.000</p>
            <p>Comisión por unidades: $<?php echo number_format($cantidad_vendida * 50000, 0, ',', '.'); ?></p>
            <p>5% de ventas totales: $<?php echo number_format($valor_total_ventas * 0.05, 0, ',', '.'); ?></p>
            <hr>
            <h4>Total a Pagar: $<?php echo number_format($salario_total, 0, ',', '.'); ?></h4>
        </div>
    <?php endif; ?>
</div>

</body>
</html>