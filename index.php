<?php
date_default_timezone_set("asia/Tashkent");
$token = 'TOKEN';
$admin = ["id"];

function bot($method,$datas=[]){
global $token;
        $url = "https://api.telegram.org/bot".$token."/".$method;
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$datas);
        $res = curl_exec($ch);
        if(curl_error($ch)){
            var_dump(curl_error($ch));
        }else{
            return json_decode($res);
        }
    }
function sendmessage($cid,$text,$mode,$reply)
{
    bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>$text,
    'parse_mode'=>$mode,
    'reply_markup'=>$reply,
    ]);
}

$connect = mysqli_connect('localhost','NAME','PASSWORD','NAME');
if ($connect) {
    echo "ulandi!";
}else{
    echo "ulanmadi!";
}
$update = json_decode(file_get_contents('php://input'));
//inline_uchun_methodlar
$data = $update->callback_query->data;
$cid2 = $update->callback_query->message->chat->id;
$mid2 = $update->callback_query->message->message_id;
$uid2 = $update->callback_query->message->from->id;
$call = $update->callback_query;
$qid = $call->id;
//oddiy_knopka_uchun_methodlar
$xabar = $update->message;
$xabar_id = $xabar->message_id;
$chat_id = $xabar->chat->id;
$cid = $xabar->chat->id;
$mid = $xabar->message_id;
$text = $xabar->text;
$uid = $xabar->from->id;
$fname = $xabar->from->first_name;
$fuser = $xabar->from->username;
$sana = date('d.m.Y');
$soat = date('H:i:s');
$call = $update->callback_query;
$qid = $call->id;
$type = $xabar->chat->type;

$user = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM users WHERE user_id = '$uid' LIMIT 1"));
$user1 = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM users WHERE user_id = '$uid2' LIMIT 1"));
$login32 = mysqli_fetch_assoc(mysqli_query($connect,"SELECT * FROM logins WHERE user_id = '$uid' LIMIT 1"));
$login1 = $login32['login'];
if(isset($text)){
if($type == "private"){
$result = mysqli_query($connect,"SELECT * FROM `users` WHERE `user_id` = $cid");
$rew = mysqli_fetch_assoc($result);
if($rew){
}else{
mysqli_query($connect,"INSERT INTO `users`(`user_id`,`login`,`parol`) VALUES ('$cid','0','0')");
}
}
}
//Bismlillah
$keys = json_encode([
'inline_keyboard'=>[
[['text'=>"Ro'yxatdan o'tish",'callback_data'=>"reg"],['text'=>"Kirish",'callback_data'=>"kirish"]],
]
]);
if ($text=="/start") {
    if ($res = mysqli_query($connect,"SELECT `login` from users where user_id = '$cid'")) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row1 = $row['login'];
        }
    }
    if ($row1=="0") {
        bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"<b>Menyulardan birini tanlang</b>",
    'parse_mode'=>html,
    'reply_markup'=>$keys
    ]);
    }else{
    bot('sendmessage',[
        'chat_id'=>$cid,
        'text'=>"<b>Salom</b>",
        'parse_mode'=>html,
        ]);mysqli_query($connect,"UPDATE users set step = '0' where user_id = $cid");
    }
}

if ($data=="reg") {
    if ($res = mysqli_query($connect,"SELECT `login` from users where user_id = '$cid2'")) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row1 = $row['login'];
        }
    }
    if ($row1) {
        bot('deleteMessage',[
        'chat_id'=>$cid2,
        'message_id'=>$mid2
        ]);
        bot('sendmessage',[
        'chat_id'=>$cid2,
        'text'=>"<b>Salom</b>",
        'parse_mode'=>html,
        ]);
    }else{
bot('deleteMessage',[
    'chat_id'=>$cid2,
    'message_id'=>$mid2
    ]);
    bot('sendmessage',[
    'chat_id'=>$cid2,
    'text'=>"<b>Login yarating</b>",
    'parse_mode'=>html,
    ]);mysqli_query($connect,"UPDATE users set step = 'reg' where user_id = $cid2");
    }
}
if ($user['step'] == "reg") {
    mysqli_query($connect,"UPDATE users set login = '$text' where user_id = $cid");
    mysqli_query($connect,"INSERT INTO `logins`(`login`,`user_id`) VALUES ('$text','$cid')");
    bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"Parol yarating...",
    ]);
    mysqli_query($connect,"UPDATE users set step = 'parol' where user_id = '$cid'");
}
if ($user['step'] == "parol") {
    mysqli_query($connect,"UPDATE users set parol = '$text' where user_id = '$cid'");
    mysqli_query($connect,"UPDATE logins set parol = '$text' where login = '$login1'");
    bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"Akkaunt yaratildi!",
    ]);
    mysqli_query($connect,"UPDATE users set step = '0' where user_id = $cid");
}
if ($data == "kirish") {
    bot('deleteMessage',[
    'chat_id'=>$cid2,
    'message_id'=>$mid2,
    ]);
    bot('sendmessage',[
    'chat_id'=>$cid2,
    'text'=>"Loginni kiriting",
    ]);mysqli_query($connect,"UPDATE users set step = 'kirish' where user_id = $cid2");

}
if ($user['step'] == "kirish") {
    if ($res = mysqli_query($connect,"SELECT `login` from logins where login = '$text'")) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row1 = $row['login'];
        }
    }
    if ($row1) {
        bot('sendmessage',[
        'chat_id'=>$cid,
        'text'=>"Parolni kiriting",
        ]);
        mysqli_query($connect,"UPDATE users set step = 'pass'");
    }else{
        bot('sendmessage',[
        'chat_id'=>$cid,
        'text'=>"Bunday Login mavjud emas!",
        ]);
    }
}
if ($user['step'] == "pass") {
    if ($res = mysqli_query($connect,"SELECT `parol` from logins where login = '$login1'")) {
        while ($row = mysqli_fetch_assoc($res)) {
            $row1 = $row['parol'];
        }
    }
 if ($row1) {
     bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"Akkauntga kirdingiz",
     ]);mysqli_query($connect,"UPDATE users set step = '0' where user_id = $cid");
 }else{
    bot('sendmessage',[
    'chat_id'=>$cid,
    'text'=>"Parol xato\nQaytadan urinib ko'ring",
    ]);mysqli_query($connect,"UPDATE users set step = 'pass' where user_id = $cid");
 }
}
