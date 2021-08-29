<?php
require("whoisClass.php");

$domain = $_GET['domain'];
$domain = strtolower(trim($domain));
$domain = preg_replace('/ /i', '', $domain);
$domain = preg_replace('/^http:\/\//i', '', $domain);
$domain = preg_replace('/^https:\/\//i', '', $domain);
$domain = explode('/', $domain);
$domain = trim($domain[0]);
if(substr_count($domain,".")==2){
$dotpos=strpos($domain,".");
$domtld=strtolower(substr($domain,$dotpos+1));
$whoisserver = $whoisservers[$domtld];
if(!$whoisserver) {if(strpos($domain,"www")===false){}else{$domain = preg_replace('/^www\./i', '', $domain);}}
}

function LookupDomain($domain){
global $whoisservers;
$whoisserver = "";

$dotpos=strpos($domain,".");
$domtld=strtolower(substr($domain,$dotpos+1));
$whoisserver = $whoisservers[$domtld];

if(!$whoisserver) {
return "Error: No appropriate Whois server found for <b>$domain</b> domain!";
}
//if($whoisserver == "whois.verisign-grs.com") $domain = "=".$domain; // whois.verisign-grs.com requires the equals sign ("=") or it returns any result containing the searched string.
$result = QueryWhoisServer($whoisserver, $domain);
if(!$result) {
return "Error: No results retrieved $domain !";
}

preg_match("/Whois Server: (.*)/", $result, $matches);
$secondary = $matches[1];
if($secondary) {
$result = QueryWhoisServer($secondary, $domain);
}
return  $result;
}

function QueryWhoisServer($whoisserver, $domain) {
$port = 43;
$timeout = 10;
$fp = @fsockopen($whoisserver, $port, $errno, $errstr, $timeout) or die("<pre>\nSocket Error " . $errno . " - " . $errstr);
fputs($fp, $domain . "\r\n");
$out = "";
while(!feof($fp)){
$out .= fgets($fp);
}
fclose($fp);
return $out;
}
?>
<!DOCTYPE html>
<html>
<head>
<title>Domains/IP Whois Search | 域名或IP查询系统 - <?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?></title>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
<meta name="keywords" content="<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>,Musta.ng,whois,whois Good Domains,domain whois,ip whois,Domain/IP Whois Search,Whois Search,域名,域名查询,域名whois查询,ip whois查询,ip查询">
<meta name="description" content="<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>,最好的域名/IP查询网站 - The best website for search whois or IP">  
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp"/>  
<link rel="bookmark" href="https://<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>/images/favicon.png"  />
<link rel="icon" href="https://<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>/images/favicon.png" type="image/x-icon" />
<link rel="shortcut icon" href="https://<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>/images/favicon.png" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="https://<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>/css/style.css">
<script src="https://<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>/js/jquery-3.4.1.min.js"></script>
 <script type="text/javascript">
       function aa() {
            var domain = document.getElementById('domain').value;
            if(!domain) {
                alert('请输入域名');
            } else{
                window.location = 'https://<?php $url = $_SERVER['SERVER_NAME']; $murl = str_replace('www.','',$url);echo $murl;?>/' + domain;
            }
        }
  </script>
</head>
<body>
<div class="main">
<form action="<?php $_SERVER['PHP_SELF'];?>" id="form" class="form">
<div id="bg">
<div id="in">
<div class="search">
<input type="text" name="domain" id="domain" autocomplete="on" placeholder="域名或IP | Domain Name or IP" value="<?php echo $domain;?>">
<button id="submit" onclick="aa(); return false;" value="whois">OK</button>
</div>
  		</div>
                 </div>
</form>
<?php
if($domain) {
if(preg_match("/^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$/",$domain)){
$result = QueryWhoisServer("whois.lacnic.net",$domain);
//whois.apnic.net whois.lacnic.net whois.afrinic.net whois.arin.net whois.ripe.net
echo "<pre>\n <h1><a target='_blank' title='$domain' href='http://$domain'><b>$domain</b></a><h9>@ <a title='Mustang/野马' href='//musta.ng' target='_blank'>Mustang/野马</a></h9></h1>" . $result . "\n</pre>\n";
}elseif(!preg_match("/^([-a-zA-Z0-9]{1,100})\.([a-z\.]{2,})$/i", $domain)) 
{
die("<pre>\n <h1><!--<a target='_blank' title='$domain' href='http://$domain'><b>$domain</b></a>--><h9>@ <a title='CAPITAL' href='//musta.ng' target='_blank'>Mustang/野马</a><a title='Contact Us' href='mailto:mustang@musta.ng'>Contact Us</a></h9></h1>Error:Wrong format! | 错误：格式有误</br> \n</pre>\n");
}else{
$result = LookupDomain($domain);
echo "<pre>\n <h1><a target='_blank' title='$domain' href='http://$domain'><b>$domain</b></a><h9>@ <a title='Mustang/野马' href='//musta.ng' target='_blank'>Mustang/野马</a></h9></h1>" . $result . "\n</pre>\n";
}
}
?>
</div>
<div style=' font-size: 12px; text-align:center; margin-bottom:50px; padding-top: 50px;'>
    <span>&copy; <a title='CAPITAL | 资本' href='//capit.al' target='_blank'>CAPITAL | 旗下</a></span>
</div>
<div>
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?05766af2d658f050c5a2e05b6d805c2e";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</div>
</body>
</html>