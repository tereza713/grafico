<?php include("conn.php"); ?>

<?php
if (isset($_POST['salvar'])){
    $id = $_POST['id'];
    $ano = $_POST['ano'];
    $cesta = $_POST['cesta'];
    $dolar = $_POST['dolar'];
    $gasolina = $_POST['gasolina'];

    if($id==""){
        $stmt = $conn->prepare("INSERT INTO precos (ano, cesta_basica, dolar, gasolina) VALUES (?, ?, ?, ?)");
        $stmt->execute([$ano, $cesta, $dolar, $gasolina]);
    } else{
        $stmt = $conn->prepare("UPDATE precos SET ano=? =, cesta_basica=?, dolar=?, gasolina=? WHERE id=?");
        $stmt = $conn->execute([$ano, $cesta, $dolar, $gasolina]);
    }

    header("Location: index.php");
    exit;
}

if (isset($_GET['excluir'])){
    $id = $_GET['excluir'];
    $stmt = $conn->prepare("DELETE FROM precos WHERE id=?");
    $stmt->execute([$id]);

    header("Location: index.php");
    exit;
}

$registros_editar = null;
if (isset($_GET['editar'])){
    $id = $_GET['editar'];
    $stmt = $conn->prepare("SELECT * FROM precos WHERE id=?");
    $stmt->execute([$id]);
    $registros_editar = $stmt->fetch(PDO::FETCH_ASSOC);
}   

if (isset($POST['atualizar'])){
    $id = $POST['id'];
    $ano = $POST['ano'];
    $cesta = $POST['cesta'];
    $dolar = $POST['dolar'];
    $id = $POST['gasolina'];

    $stmt = $conn->prepare("UPDATE precos SET ano=?, cesta_basica=?, dolar=?, gasolina=? WHERE id=?");
    $stmt->execute($ano, $cesta, $dolar, $gasolina, $id);
    
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projeto gráfico Anna e Bruna</title>
</head>
<body>
    <h2><?php echo $registros_editar ? "Editar Registro" : "Inserir Novo Registro";?></h2>
    <form action="" method="post">
        <?php if($registros_editar): ?>
        <input type="hidden" name="id" value="<?php echo $registros_editar['id'];?>">
        <?php endif; ?>

        <label for="">Ano</label>
        <input type="number" name="ano" value="<?php echo $registros_editar['ano'] ?? ''; ?>" required>

        <label for="">Cesta Básica</label>
        <input type="text" name="cesta" value="<?php echo $registros_editar['cesta_basica'] ?? ''; ?>" required>

        <label for="">Dólar</label>
        <input type="text" name="dolar" value="<?php echo $registros_editar['dolar'] ?? ''; ?>" required>

        <label for="">Gasolina</label>
        <input type="text" name="gasolina" value="<?php echo $registros_editar['gasolina'] ?? ''; ?>" required>

        <?php if ($registros_editar): ?>
        <button type="submit" name="atualizar" value="">Atualizar</button>
        <a href="index.php">Cancelar</a>
        <?php else: ?>
        <button type="submit" name="salvar" value="">Inserir</button>
        <?php endif; ?>
    </form>
    
        <hr>

        <h2>Lista de preços</h2>
        <a href="">Ver gráfico</a>
        <table border="1" cellpadding="8">
            <tr>
                <th>ID</th>
                <th>Ano</th>
                <th>Cesta Básica</th>
                <th>Dólar</th>
                <th>Gasolina</th>
                <th>Ações</th>
            </tr>

<?php
$stmt = $conn->query("SELECT * FROM precos ORDER BY ano ASC");
$registros = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($registros as $row) {
    echo "<tr>";
    echo "<td>" . $row['id'] . "</td>";
    echo "<td>" . $row['ano'] . "</td>";
    echo "<td>" . $row['cesta_basica'] . "</td>";
    echo "<td>" . $row['dolar'] . "</td>";
    echo "<td>" . $row['gasolina'] . "</td>";
    echo "<td>";
    echo '<a href="index.php?editar=' . $row['id'] . '">Editar</a> | ';
    echo '<a href="index.php?excluir=' . $row['id'] . '" onclick="return confirm(\'Tem certeza que deseja excluir?\')">Excluir</a>';
    echo "</td>";
    echo "</tr>";
}
?>
        </table>
</body>
</html>