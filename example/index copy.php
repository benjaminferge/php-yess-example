<?php

require __DIR__.'/inc.php';

use GPBMetadata\Yess;
use Yess\Client;
use Yess\CreateStreamReq;
use Yess\GetAllStreamsReq;
use Yess\GetAllStreamsResp;
use Yess\YessServiceClient;

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>yess example</title>
</head>
<body>
<h1>yess example</h1>
<pre>
<?php

$x = $client->getStream(1);
var_dump($x);

/*
$credentials = new Grpc\ChannelCredentials();
$cred = $credentials->createDefault();
object(Grpc\ChannelCredentials)#2 (0) {
}
array(7) {
  [0]=>
  string(18) "setDefaultRootsPem"
  [1]=>
  string(20) "isDefaultRootsPemSet"
  [2]=>
  string(25) "invalidateDefaultRootsPem"
  [3]=>
  string(13) "createDefault"
  [4]=>
  string(9) "createSsl"
  [5]=>
  string(15) "createComposite"
  [6]=>
  string(14) "createInsecure"
}

*/
/*
// $credentials = Grpc\ChannelCredentials::createDefault();
$credentials = Grpc\ChannelCredentials::createInsecure();
$client = new YessServiceClient($addr, compact('credentials'));
$waitSec = 2;
$waitMicro = $waitSec * 1000000;
$client->waitForReady($waitMicro);
// var_dump(get_class_methods($client));

$createStreamReq = new CreateStreamReq();
$createStreamReq->setType("User");
// $result = $client->CreateStream($createStreamReq);
// $resres = $result->wait();
// var_dump($resres);

$streamsReq = new GetAllStreamsReq();
$result = $client->GetAllStreams($streamsReq);
$resres = $result->wait();
$getAllStreamResp = $resres[0];
var_dump(get_class_methods($getAllStreamResp));
var_dump($getAllStreamResp->serializeToJsonString());
$streams = $getAllStreamResp->getStreams();
var_dump(get_class_methods($streams));
var_dump($streams->count());
*/
?>
</pre>
</body>
</html>