<?php
require __DIR__.'/inc.php';
$projections = $client->getProjections();
$stream_id = $_GET['stream-id'] ?? null;
if (!$stream_id) {
        $location = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $location .= '/streams.php';
        header("Location: $location");
        die(); 
}
$type = $_GET['type'] ?? '';
$action = $_POST['action'] ?? null;
$events = [];
if ($stream_id) {
        $events = $client->getEventsByStreamId($stream_id);
} else {
        $events = $client->getEventsByStreamType($type);
}

switch ($action) {
        case 'Push':
                $type = $_POST['type'] ?? 0;
                $payload = $_POST['payload'] ?? '';
                $version = $_POST['version'] ?? 0;
                $client->push($stream_id, $type, $payload, $version);
                break;
        case 'Play':
                $proj_id = $_POST['projection'];
                $result_state = $client->play($proj_id, $stream_id, null, []);
                break;
        
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Events</title>
</head>
<body>
<h1>Events of stream ID <?=$stream_id?></h1>
<p>Push an event into stream</p>
<form method="POST">
<label for="stream_id">Stream ID</label>
<br>
<input type="text" value="<?=$stream_id?>" disabled>
<br>
<label for="type">Type</label>
<br>
<input type="text" name="type" id="type">
<br>
<label for="version">Version</label>
<br>
<input type="text" name="version" id="version">
<br>
<label for="payload">Payload (JSON)</label>
<br>
<textarea name="payload" id="payload" cols="30" rows="10"></textarea>
<br>
<input type="submit" name="action" value="Push">
</form>
<hr>
<p>Play a projection</p>
<form method="POST">
<label for="projection">Projection</label>
<select name="projection" id="projection">
<?php foreach($projections as $proj): ?>
<option value="<?=$proj->id?>">ID #<?=$proj->id?></option>
<?php endforeach ?>
</select>
<input type="submit" name="action" value="Play">
</form>
<?php if(isset($result_state)): ?>
<p>Result</p>
<pre>
<?=json_encode($result_state, JSON_PRETTY_PRINT)?>
</pre>
<?php endif ?>
<hr>
<?php if(count($events)>0): ?>
<table border="1">
<tr>
        <?php foreach ($events[0] as $h => $v): ?>
        <th><?=$h?></th>
        <?php endforeach ?>
</tr>

<?php foreach ($events as $e): ?>
<tr>
        <?php foreach ($e as $v): ?>
        <td><?=$v?></td>
        <?php endforeach ?>
</tr>
<?php endforeach ?>
</table>
<?php else: ?>
<?php if($type): ?>
<p>Events by stream type of <em>'<?=$type?>'</em> not found!</p>
<?php else: ?>
<p>Events by stream ID <em>#<?=$stream_id?></em> not found!</p>
<?php endif ?>
<?php endif ?>
</body>
</html>