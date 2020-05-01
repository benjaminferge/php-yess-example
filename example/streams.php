<?php
require __DIR__.'/inc.php';
$type = $_GET['type'] ?? null;
$id = $_GET['id'] ?? null;
$create = $_POST['create'] ?? null;
$streams = [];

if ($create) {
        $client->createStream($create);
}
if ($id) {
        $stream = $client->getStream($id);
        if ($stream) {
            $streams[] = $stream;
        }
} else {
    if ($type) {
        $streams = $client->getStreamsByType($type);
    } else {
        $streams = $client->getAllStreams();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Streams</title>
</head>
<body>
<h1>Streams</h1>
<form>
<label for="type">Type</label>
<input type="text" name="type" id="type" value="<?=htmlspecialchars($type)?>">
<input type="submit">
</form>
<hr>
<form>
<label for="id">ID</label>
<input type="text" name="id" id="id" value="<?=htmlspecialchars($id)?>">
<input type="submit">
</form>
<hr>
<p>Create a stream</p>
<form method="POST">
<label for="create">Type</label>
<input type="text" name="create" id="create">
<input type="submit">
</form>
<hr>
<?php if(count($streams)>0): ?>
<table border="1">
<tr>
        <?php foreach ($streams[0] as $h => $v): ?>
        <th><?=$h?></th>
        <?php endforeach ?>
        <th>&nbsp;</th>
</tr>

<?php foreach ($streams as $s): ?>
<tr>
        <?php foreach ($s as $v): ?>
        <td><?=$v?></td>
        <?php endforeach ?>
        <td><a href="events.php?stream-id=<?=$s->id?>"><button>events</button></a></td>
</tr>
<?php endforeach ?>
</table>
<?php else: ?>
<?php if($type): ?>
<p>Stream type of <em>'<?=$type?>'</em> not found!</p>
<?php else: ?>
<p>Stream ID <em>#<?=$id?></em> not found!</p>
<?php endif ?>
<?php endif ?>
</body>
</html>