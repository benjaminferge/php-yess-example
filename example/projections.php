<?php
require __DIR__.'/inc.php';
$action = $_POST['action'] ?? null;
switch ($action) {
        case 'create':
                $createType = $_POST['type'] ?? null;
                $createData = $_POST['data'];
                $client->createProjection($createData, $createType);
                break;
        case 'delete':
                $deleteId = $_POST['id'];
                $client->deleteProjection($deleteId);
                break;
}
$type = $_GET['type'] ?? null;
$projs = $client->getProjections($type);
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Projections</title>
</head>
<body>
        <h1>Projections</h1>
<p>Create projection</p>
<form method="POST">
        <input type="text" name="type" id="type">
        <textarea name="data" id="data" cols="30" rows="10"></textarea>
        <input type="submit" name="action" value="create">
</form>
<hr>
<?php if(count($projs)>0): ?>
<table border="1">
<tr>
        <?php foreach ($projs[0] as $h => $v): ?>
        <th><?=$h?></th>
        <?php endforeach ?>
        <th>&nbsp;</th>
</tr>

<?php foreach ($projs as $p): ?>
<tr>
        <?php foreach ($p as $v): ?>
        <td><?=$v?></td>
        <?php endforeach ?>
        <td>
                <form method="POST">
                        <input type="text" name="id" hidden value="<?=$p->id?>">
                        <input type="submit" name="action" value="delete">
                </form>
        </td>
</tr>
<?php endforeach ?>
</table>
<?php endif ?>
</body>
</html>