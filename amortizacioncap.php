<?php
$tabla = [];
$cuota_fija = 0; 
$datos_cliente = null;
$total_intereses = 0;
$meses_ahorrados = 0;
$monto = 0;
$plazo = 0;

if (isset($_POST['enviar'])) {
    // Captura de datos del formulario
    $cedula = $_POST['cedula'];
    $nombre = $_POST['nombre'];
    $monto = (float)$_POST['monto'];
    $tasa_mensual = (float)$_POST['tasa'] / 100;
    $plazo = (int)$_POST['plazo'];
    
    $abono_extra_mensual = (float)($_POST['abono_extra'] ?? 0);
    $seguros_otros = (float)($_POST['seguros'] ?? 0);

    $datos_cliente = ['cedula' => $cedula, 'nombre' => $nombre];

    // 2. Cálculo de la cuota fija (Sistema Francés)
    if ($tasa_mensual > 0) {
        $factor = pow(1 + $tasa_mensual, $plazo);
        $cuota_fija = $monto * (($tasa_mensual * $factor) / ($factor - 1));
    } else {
        $cuota_fija = $monto / $plazo;
    }

    $saldo_actual = $monto;

    // 3. Generación de la tabla
    for ($i = 1; $i <= $plazo; $i++) {
        if ($saldo_actual <= 0.05) break; 

        $interes_mes = $saldo_actual * $tasa_mensual;
        $abono_capital_normal = $cuota_fija - $interes_mes;
        
        // Sumamos el abono extra
        $abono_total_capital = $abono_capital_normal + $abono_extra_mensual;

        if ($abono_total_capital > $saldo_actual) {
            $abono_total_capital = $saldo_actual;
        }

        $nuevo_saldo = $saldo_actual - $abono_total_capital;
        $total_intereses += $interes_mes;

        $tabla[] = [
            'n_cuota' => $i,
            'saldo_anterior' => $saldo_actual,
            'seguros' => $seguros_otros,
            'pago_total' => ($abono_total_capital + $interes_mes + $seguros_otros),
            'interes' => $interes_mes,
            'abono_capital' => $abono_total_capital,
            'nuevo_saldo' => $nuevo_saldo
        ];

        $saldo_actual = $nuevo_saldo;
    }
    $meses_ahorrados = $plazo - count($tabla);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Simulador de Crédito Profesional</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f0f2f5; padding: 20px; color: #333; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); }
        h2 { color: #2c3e50; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; margin-bottom: 25px; }
        .form-group { display: flex; flex-direction: column; }
        label { font-weight: 600; margin-bottom: 5px; font-size: 0.85em; color: #555; }
        input { padding: 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 0.95em; }
        .btn-enviar { grid-column: 1 / -1; background-color: #2c3e50; color: white; cursor: pointer; border: none; font-size: 1.1em; padding: 12px; border-radius: 6px; }
        
        table { width: 100%; border-collapse: collapse; margin-top: 25px; font-size: 0.85em; }
        th { background-color: #2c3e50; color: white; padding: 12px; }
        td { border: 1px solid #eee; padding: 10px; text-align: right; }
        tr:nth-child(even) { background-color: #f8f9fa; }
        .highlight-capital { color: #27ae60; font-weight: bold; }
        .info-cliente { background: #fdfdfd; padding: 15px; border-left: 5px solid #3498db; margin: 20px 0; }
        .resumen-card { background: #e8f4fd; padding: 20px; border-radius: 8px; display: flex; justify-content: space-around; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Simulador de Crédito - Método Francés</h2>
    <form method="post" action="">
        <div class="form-grid">
            <div class="form-group">
                <label>Cédula:</label>
                <input type="text" name="cedula" value="<?php echo $_POST['cedula'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Nombre del Cliente:</label>
                <input type="text" name="nombre" value="<?php echo $_POST['nombre'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Monto ($):</label>
                <input type="number" name="monto" value="<?php echo $_POST['monto'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Tasa Mensual (%):</label>
                <input type="number" step="0.01" name="tasa" value="<?php echo $_POST['tasa'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Plazo (Meses):</label>
                <input type="number" name="plazo" value="<?php echo $_POST['plazo'] ?? ''; ?>" required>
            </div>
            <div class="form-group">
                <label>Seguros ($):</label>
                <input type="number" name="seguros" value="<?php echo $_POST['seguros'] ?? ''; ?>">
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <label>Abono Extra Mensual a Capital ($):</label>
                <input type="number" name="abono_extra" value="<?php echo $_POST['abono_extra'] ?? '0'; ?>" style="border: 2px solid #27ae60;">
            </div>
            <input type="submit" name="enviar" value="Calcular Amortización" class="btn-enviar">
        </div>
    </form>

    <?php if ($datos_cliente): ?>
        <div class="info-cliente">
            <h3>Tabla de Amortización</h3>
            <p><strong>Cliente:</strong> <?php echo htmlspecialchars($datos_cliente['nombre']); ?></p>
            <p><strong>Cédula:</strong> <?php echo htmlspecialchars($datos_cliente['cedula']); ?></p>
            <p><strong>Monto del crédito:</strong> $<?php echo number_format($monto, 2, ',', '.'); ?></p>
            <p><strong>Cantidad de cuotas:</strong> <?php echo $plazo; ?> meses</p>
            <p><strong>Tasa de interés aplicada:</strong> <?php echo $_POST['tasa']; ?>% mensual</p>
            <p><strong>Cuota Fija Base:</strong> $<?php echo number_format($cuota_fija, 2, ',', '.'); ?></p>
            <p><strong>Seguros:</strong> $<?php echo number_format($seguros_otros, 2, ',', '.'); ?></p>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Mes</th>
                    <th>Saldo Inicial</th>
                    <th>Intereses</th>
                    <th>Abono Capital</th>
                    <th>Seguros</th>
                    <th>Pago Total</th>
                    <th>Nuevo Saldo</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tabla as $f): ?>
                <tr>
                    <td style="text-align: center;"><?php echo $f['n_cuota']; ?></td>
                    <td>$<?php echo number_format($f['saldo_anterior'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($f['interes'], 2, ',', '.'); ?></td>
                    <td class="highlight-capital">$<?php echo number_format($f['abono_capital'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($f['seguros'], 2, ',', '.'); ?></td>
                    <td style="font-weight: bold;">$<?php echo number_format($f['pago_total'], 2, ',', '.'); ?></td>
                    <td>$<?php echo number_format($f['nuevo_saldo'], 2, ',', '.'); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="resumen-card">
            <div style="text-align: center;">
                <label>Intereses Totales</label>
                <div style="font-size: 1.2em; font-weight: bold;">$<?php echo number_format($total_intereses, 2, ',', '.'); ?></div>
            </div>
            <div style="text-align: center;">
                <label>Meses Ahorrados</label>
                <div style="font-size: 1.2em; font-weight: bold; color: #27ae60;"><?php echo $meses_ahorrados; ?></div>
            </div>
        </div>
    <?php endif; ?>
</div>

</body>
</html>