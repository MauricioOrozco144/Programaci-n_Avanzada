<?php
$nota_final = null;
$mensaje = "";
$clase_mensaje = "";

if (isset($_POST['enviar'])) {
    $p1 = $_POST['parcial1'];
    $p2 = $_POST['parcial2'];
    $p3 = $_POST['parcial3'];
    $examen_final = $_POST['examen_final'];
    $trabajo_final = $_POST['trabajo_final'];

    $promedio_parciales = ($p1 + $p2 + $p3) / 3;

    // 35% promedio parciales + 35% examen final + 30% trabajo final
    $nota_final = ($promedio_parciales * 0.35) + ($examen_final * 0.35) + ($trabajo_final * 0.30);

    // 3. Determinar si aprobó (mínimo 3.0) 
    if ($nota_final >= 3) {
        $mensaje = "Aprobó";
        $clase_mensaje = "aprobado";
    } else {
        $mensaje = "No aprobó";
        $clase_mensaje = "reprobado";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Cálculo de Notas - Práctica PHP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }
        .container {
            background-color: #003d52; /* Color azul oscuro del formulario en el PDF */
            padding: 20px;
            border-radius: 8px;
            color: white;
            width: 300px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
        .form-group {
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        label {
            font-size: 14px;
            font-weight: bold;
        }
        input[type="number"] {
            width: 150px;
            padding: 5px;
            border-radius: 4px;
            border: none;
        }
        input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #e0e0e0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            margin-top: 10px;
        }
        input[type="submit"]:hover {
            background-color: #cccccc;
        }
        .resultado {
            margin-top: 20px;
            padding: 15px;
            background: white;
            color: black;
            border-radius: 4px;
            text-align: center;
        }
        .aprobado { color: green; font-weight: bold; }
        .reprobado { color: red; font-weight: bold; }
    </style>
</head>
<body>

<div class="container">
    <form method="post" action="">
        <div class="form-group">
            <label>Parcial 1:</label>
            <input type="number" step="0.1" name="parcial1" required>
        </div>
        <div class="form-group">
            <label>Parcial 2:</label>
            <input type="number" step="0.1" name="parcial2" required>
        </div>
        <div class="form-group">
            <label>Parcial 3:</label>
            <input type="number" step="0.1" name="parcial3" required>
        </div>
        <div class="form-group">
            <label>Examen final:</label>
            <input type="number" step="0.1" name="examen_final" required>
        </div>
        <div class="form-group">
            <label>Trabajo final:</label>
            <input type="number" step="0.1" name="trabajo_final" required>
        </div>
        
        <input type="submit" name="enviar" value="Enviar">
    </form>

    <?php if ($nota_final !== null): ?>
        <div class="resultado">
            <p>Nota Final: <strong><?php echo number_format($nota_final, 2); ?></strong></p>
            <p class="<?php echo $clase_mensaje; ?>"><?php echo $mensaje; ?></p>
        </div>
    <?php endif; ?>
</div>

</body>
</html>