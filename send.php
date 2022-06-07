<?php
error_reporting(0);
@$db = json_decode(file_get_contents('send.json'), true);

if($db){
	include "index.php";
	$get = mysqli_query($MySQLi, "SELECT * FROM `user`");
	while($row = $get->fetch_assoc()) {
		@$users[] = $row['id'];
	}
	$chunk = array_chunk($users, 200);
	$list = $chunk[$db['count']];
}
if ($db['type'] == 's2u') {
	if (empty($list) === false) {
		foreach($list as $value) {
		    WSBot('sendmessage', [
		        'chat_id' => $value,
			    'text' => $db['text']
			]);
		}
		$msg = 1 + $db['count'].'/'.count($chunk);
		WSBot('sendmessage', [
		    'chat_id' => $db['chat_id'],
		    'text' => "■ تا کنون به $msg ارسال شده است !",
		    'reply_to_message_id' => $message_id,
		    'reply_markup' => $back_panel
		]);
		$db['count']++;
		file_put_contents('send.json', json_encode($db));
	} else {
	    WSBot('sendmessage', [
			    'chat_id' => $db['chat_id'],
			    'text' => "■ ارسال با موفقیت به پایان رسید .",
			    'reply_to_message_id' => $message_id,
			    'reply_markup' => $back_panel
		]);
		unlink('send.json');
	}
}
elseif($db['type'] == 'f2u'){
	if (empty($list) === false) {
		foreach($list as $value) {
		    WSBot('ForwardMessage', [
		         'chat_id' => $value,
		         'from_chat_id' => $db['chat_id'],
		         'message_id'=>$db['msgid']
		    ]);
		}
		$msg = 1 + $db['count'].'/'.count($chunk);
		WSBot('sendmessage', [
		    'chat_id' => $db['chat_id'],
		    'text' => "■ تا کنون به $msg فروارد شده است !",
		    'reply_to_message_id' => $message_id,
		    'reply_markup' => $back_panel
		]);
		$db['count']++;
		file_put_contents('send.json', json_encode($db));
	} else {
	    WSBot('sendmessage', [
		    'chat_id' => $db['chat_id'],
		    'text' => "■ ارسال با موفقیت به پایان رسید .",
		    'reply_to_message_id' => $message_id,
		    'reply_markup' => $back_panel
		]);
		unlink('send.json');
	}
}
?>

