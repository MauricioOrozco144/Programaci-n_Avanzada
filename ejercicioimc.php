<?php
// Lógica para el cálculo de peso sobre altura  
$imc = null;
$categoria = "";
$nombre_paciente = "";

if (isset($_POST['enviar'])) {
    $nombre_paciente = $_POST['nombre'];
    $peso = (float)$_POST['peso'];
    $estatura = (float)$_POST['estatura'];

    if ($estatura > 0) {
        // Fórmula de Quetelet: peso / estatura^2 
        $imc = $peso / ($estatura * $estatura);

        if ($imc < 18.5) {
            $categoria = "Por debajo del peso";
        } elseif ($imc >= 18.5 && $imc <= 24.9) {
            $categoria = "Saludable";
        } elseif ($imc >= 25.0 && $imc <= 29.9) {
            $categoria = "Con sobrepeso";
        } elseif ($imc >= 30.0 && $imc <= 39.9) {
            $categoria = "Obeso";
        } else {
            $categoria = "Obesidad mórbida";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cálculo de IMC</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            padding-top: 50px;
            background-color: #f4f4f4;
        }
        .imc-container {
            background-color: #f10f0f; 
            padding: 25px;
            border-radius: 15px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        label {
            flex: 1;
            font-weight: bold;
            color: #2c3e50;
        }
        input[type="text"], input[type="number"] {
            flex: 1.2;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #e0e0e0;
            border: 1px solid #999;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .resultado-imc {
            margin-top: 20px;
            background: white;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            border: 2px solid #2c3e50;
        }
    </style>
</head>
<body>

<div class="imc-container">
    <form method="post" action="">
        <div class="form-group">
            <label>Nombre del paciente:</label>
            <input type="text" name="nombre" required>
        </div>
        <div class="form-group">
            <label>Peso en kilogramos:</label>
            <input type="number" step="0.1" name="peso" required>
        </div>
        <div class="form-group">
            <label>Estatura en metros:</label>
            <input type="number" step="0.01" name="estatura" placeholder="Ej: 1.75" required>
        </div>
        
        <input type="submit" name="enviar" value="Enviar">
    </form>

    <?php if ($imc !== null): ?>
        <div class="resultado-imc">
            <h4>Resultado para <?php echo htmlspecialchars($nombre_paciente); ?></h4>
            <p>Su IMC es: <strong><?php echo number_format($imc, 1); ?></strong></p>
            <p>Categoría: <strong><?php echo $categoria; ?></strong></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>