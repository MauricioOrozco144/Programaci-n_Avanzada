<?php
$tabla = [];
$cuota_fija = 0;
$datos_cliente = null;

if (isset($_POST['enviar'])) {
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $monto = (float)$_POST['monto'];
    $tasa_mensual = (float)$_POST['tasa'] / 100; 
    $plazo = (int)$_POST['plazo'];

    // Formula: Monto * (i * (1+i)^n) / ((1+i)^n - 1)
    $factor = pow(1 + $tasa_mensual, $plazo);
    $cuota_fija = $monto * (($tasa_mensual * $factor) / ($factor - 1));

    $datos_cliente = ['cedula' => $cedula, 'nombre' => $nombre];
    $saldo_anterior = $monto;

    // Generación de la tabla de amortización 
    for ($i = 1; $i <= $plazo; $i++) {
        $interes_mes = $saldo_anterior * $tasa_mensual;
        $abono_capital = $cuota_fija - $interes_mes;
        $nuevo_saldo = $saldo_anterior - $abono_capital;

        $tabla[] = [
            'n_cuota' => $i,
            'saldo_anterior' => $saldo_anterior,
            'valor_cuota' => $cuota_fija,
            'interes' => $interes_mes,
            'abono_capital' => $abono_capital,
            'nuevo_saldo' => abs($nuevo_saldo) < 0.01 ? 0 : $nuevo_saldo // Limpiar residuo decimal
        ];

        $saldo_anterior = $nuevo_saldo;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Tabla de Amortización</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-bottom: 20px; }
        .form-group { display: flex; flex-direction: column; }
        label { font-weight: bold; margin-bottom: 5px; font-size: 0.9em; }
        input { padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        input[type="submit"] { grid-column: span 2; background-color: #2c3e50; color: white; cursor: pointer; border: none; font-size: 1em; margin-top: 10px; }
        input[type="submit"]:hover { background-color: #34495e; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 0.85em; }
        th { background-color: #2c3e50; color: white; padding: 10px; }
        td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .info-cliente { margin-bottom: 15px; border-left: 5px solid #2c3e50; padding-left: 10px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Crédito - Método Francés</h2>
    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label>Cédula del cliente:</label>
                <input type="text" name="cedula" required>
            </div>
            <div class="form-group">
                <label>Nombre del cliente:</label>
                <input type="text" name="nombre" required>
            </div>
            <div class="form-group">
                <label>Monto del crédito:</label>
                <input type="number" name="monto" required>
            </div>
            <div class="form-group">
                <label>Tasa de interés mensual (%):</label>
                <input type="number" step="0.01" name="tasa" required>
            </div>
            <div class="form-group">
                <label>Plazo en meses:</label>
                <input type="number" name="plazo" required>
            </div>
        </div>
        <input type="submit" name="enviar" value="Generar Tabla de Amortización">
    </form>

    <?php if ($datos_cliente): ?>
        <hr>
        <div class="info-cliente">
            <h3>Tabla de Amortización</h3>
            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($datos_cliente['cedula']); ?></p>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($datos_cliente['nombre']); ?></p>
            <p><strong>Cantidad de cuotas:</strong> <?php echo $plazo; ?> meses</p>
            <p><strong>Monto del crédito:</strong> $<?php echo number_format($monto, 2, ',', '.'); ?></p>
            <p><strong>Tasa de interés aplicada:</strong> <?php echo $_POST['tasa']; ?>% mensual</p>
            <p><strong>Valor Cuota Fija:</strong> $<?php echo number_format($cuota_fija, 2, ',', '.'); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>No. Cuota</th>
                    <th>Saldo Anterior</th>
                    <th>Valor Cuota Fija</th>
                    <th>Abono Interés</th>
                    <th>Abono Capital</th>
                    <th>Nuevo Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tabla as $fila): ?>
                <tr>
                    <td><?php echo $fila['n_cuota']; ?></td>
                    <td>$<?php echo number_format($fila['saldo_anterior'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($fila['valor_cuota'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($fila['interes'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($fila['abono_capital'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($fila['nuevo_saldo'], 2, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

</body>
</html>
