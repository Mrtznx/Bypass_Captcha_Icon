<?php
error_reporting(0);

#Code Warna
const
a = "\033[1;38m",
//biru
b = "\033[1;34m",
//cyan
c = "\033[1;36m",
//default/hitam
d = "\033[0m",
//hijau
h = "\033[1;32m",
//kuning
k = "\033[1;33m",
//merah
m = "\033[1;31m",
n = "\n",
//putih
p = "\033[1;37m",
//ungu
u = "\033[1;35m";

//MODUL
function Run($url, $head = 0, $post = 0){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
	curl_setopt($ch, CURLOPT_COOKIE,TRUE);
	if($post){
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}
	if($head){
		curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	}
	curl_setopt($ch, CURLOPT_HEADER, true);
	$r = curl_exec($ch);
	$c = curl_getinfo($ch);
	if(!$c) return "Curl Error : ".curl_error($ch); else{
		$hd = substr($r, 0, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		$bd = substr($r, curl_getinfo($ch, CURLINFO_HEADER_SIZE));
		curl_close($ch);
		return array($hd,$bd)[1];
	}
}

function h($xml=0){
	if($xml){
		$h[] = "x-requested-with: XMLHttpRequest";
	}
	$h[] = "cookie: uuid=P9720acd2-1889-49ec-bb5e-3515ef7343a8; _gid=GA1.2.1540950725.1650469545; PHPSESSID=idu82es4bbg7n53kh11nuo771m; bitmedia_fid=eyJmaWQiOiI2ZDJkZGRkNDAxOGFmYmRmNThmNTA5MDkxNjEzN2I0MyIsImZpZG5vdWEiOiJhZjA3MGM0YmMxOWIwNjEwNDMxN2U0NzdhYzNmODVkYyJ9; SesHashKey=9my8gg711q26ywr2; SesToken=ses_id%3D113126%26ses_key%3D9my8gg711q26ywr2; AccExist=113126; __cf_bm=xugP36RvihZjln1Z2uvJQdv2j_4OOvD_qVj0WJoM5Fg-1650553363-0-AbeGSFttmszH9aTXH0YxgCKvfkagNE1Wg6mAF8elHkVmGzfBkOqysF6c3Hsqx0C6X4YW6FhCqPKq7rzK6kEKvVYaCiIcTkJp7L55/cf/XN0aL7TVO3t4QyhQcRmeNEcNYQ==; cf_clearance=xSmL2dXD7mZfRJzEMQ9nU9TqvlGSAULw0._m0R_TFpk-1650554582-0-150; _ga_J2L7YD89W2=GS1.1.1650551428.8.1.1650554601.0; _ga=GA1.2.1908897154.1649118303; _gat_gtag_UA_133726835_1=1";
	$h[] = "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36";
	return $h;
}

$line = u.str_repeat('~',50)."\n";

$r = Run('https://claimbits.net/faucet.html', h());

//<font class="text-success">iewilmaestro</font>
$user = explode('</font>',explode('<font class="text-success">',$r)[1])[0];
//<div class="text-primary"><b>945.58 Bits</b>
$balance = explode('</b>',explode('<div class="text-primary"><b>',$r)[1])[0];

print h."Login as : ".k.$user."\n";
print h."Balance  : ".k.$balance."\n";
print $line;

ptc:
//halaman ptc
$r = Run('https://claimbits.net/ptc.html',h());
//<div class="website_block" id="584">
$sid = explode('">',explode('<div class="website_block" id="',$r)[1])[0];
//childWindow = open(base + '/surf.php?sid=' + a + '&key=dd50333255e11580407d36d42d8c7a2e', b);
$key = explode("'",explode('&key=',$r)[1])[0];

if($sid){
	//halaman ads
	$r = Run('https://claimbits.net/surf.php?sid='.$sid.'&key='.$key,h());
	//var token = 'e182f69331ccbc63334cfdec1e6a0a1a6c9ea7e3695edc0f308fc4c038a3e0c5';
	$token = explode("';",explode("var token = '",$r)[1])[0];
	$timer = explode(';',explode('var secs = ',$r)[1])[0];
	for($i=$timer;$i>0;$i--){
		print "\r                            \r";
		print b."Claim After ".p.$i.b." Second";;
		sleep(1);
		print "\r                            \r";
	}

	while(true){
		$data = "cID=0&rT=1&tM=light";
		$res = Run('https://claimbits.net/system/libs/captcha/request.php',h(1),$data);
		$cap = json_decode($res)[3];

		$data = "cID=0&pC=".$cap."&rT=2";
		Run('https://claimbits.net/system/libs/captcha/request.php',h(1),$data);

		$data = "a=proccessPTC&data=".$sid."&token=".$token."&captcha-idhf=0&captcha-hf=".$cap;
		$r = Run('https://claimbits.net/system/ajax.php',h(),$data);
		/*
		$r = json_decode($r);
		stdClass Object
		(
			[message] => <div class="alert alert-success" role="alert"><i class="fa fa-check-circle fa-fw"></i> <b>SUCCESS</b> You received 1.10 Bits!</div>
			[redirect] => https://business.clubshop.com/cs-super-fast2-subid-123-gps-services
			[status] => 200
		)
		*/
		$r = json_decode($r,1);
		/*
		Sukses
		array
		(
			[message] => <div class="alert alert-success" role="alert"><i class="fa fa-check-circle fa-fw"></i> <b>SUCCESS</b> You received 1.10 Bits!</div>
			[redirect] => https://business.clubshop.com/cs-super-fast2-subid-123-gps-services
			[status] => 200
		)

		Gagal
		Array
		(
			[message] => <div class="alert alert-danger" role="alert"><i class="fa fa-exclamation-triangle fa-fw"></i> Captcha error, please try again!</div>
			[status] => 600
		)
		*/
		$sukses = $r["message"];
		#<div class="alert alert-success" role="alert"><i class="fa fa-check-circle fa-fw"></i> <b>SUCCESS</b> You received 1.10 Bits!</div>

		$status = $r["status"];
		if($status == 200){
			print trim(h.strip_tags($sukses))."\n";
			print $line;
			goto ptc;
		}else{
			print m."Capctcha salah";
			sleep(2);
			print "\r               \r";
		}
	}
}else{
	print m."ptc habis\n";
	print $line;
	$r = Run('https://claimbits.net/faucet.html', h());
	//<div class="text-primary"><b>945.58 Bits</b>
	$balance = explode('</b>',explode('<div class="text-primary"><b>',$r)[1])[0];
	print h."New Balance  : ".k.$balance."\n";
	print $line;
	exit;
}


