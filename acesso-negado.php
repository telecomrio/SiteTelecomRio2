<?php
include "globals.inc";
include "config.inc";
$page_info = <<<EOD
# ----------------------------------------------------------------------------------------------------------------------
# SquidGuard error page generator
# (C)2006-2007 Serg Dvoriancev
# ----------------------------------------------------------------------------------------------------------------------
# This programm processed redirection to specified URL or generated error page for standart HTTP error code.
# Redirection supported http and https protocols.
# ----------------------------------------------------------------------------------------------------------------------
# Format:
#        sgerror.php?url=[http://myurl]or[https://myurl]or[error_code[space_code]output-message][incoming SquidGuard variables]
# Incoming SquidGuard variables:
#        a=client_address
#        n=client_name
#        i=client_user
#        s=client_group
#        t=target_group
#        u=client_url
# Example:
#        sgerror.php?url=http://myurl.com&a=..&n=..&i=..&s=..&t=..&u=..
#        sgerror.php?url=https://myurl.com&a=..&n=..&i=..&s=..&t=..&u=..
#        sgerror.php?url=404%20output-message&a=..&n=..&i=..&s=..&t=..&u=..
# ----------------------------------------------------------------------------------------------------------------------
# Tags:
#        myurl and output messages can include Tags
#                [a] - client address
#                [n] - client name
#                [i] - client user
#                [s] - client group
#                [t] - target group
#                [u] - client url
# Example:
#         sgerror.php?url=401 Unauthorized access to URL [u] for client [n]
#      sgerror.php?url=http://my_error_page.php?cladr=%5Ba%5D&clname=%5Bn%5D // %5b=[ %d=]
# ----------------------------------------------------------------------------------------------------------------------
# Special Tags:
#      blank     - get blank page
#        blank_img - get one-pixel transparent image (for replace banners and etc.)
# Example:
#        sgerror.php?url=blank
#        sgerror.php?url=blank_img
# ----------------------------------------------------------------------------------------------------------------------
EOD;

define('ACTION_URL', 'url');
define('ACTION_RES', 'res');
define('ACTION_MSG', 'msg');

define('TAG_BLANK',     'blank');
define('TAG_BLANK_IMG', 'blank_img');

# ----------------------------------------------------------------------------------------------------------------------
# ?url=EMPTY_IMG
#      Use this options for replace baners/ads to transparent picture. Thisbetter for viewing.
# ----------------------------------------------------------------------------------------------------------------------
# NULL GIF file
# HEX: 47 49 46 38 39 61 - - -
# SYM: G  I  F  8  9  a  01 00 | 01 00 80 00 00 FF FF FF | 00 00 00 2C 00 00 00 00 | 01 00 01 00 00 02 02 44 | 01 00 3B
# ----------------------------------------------------------------------------------------------------------------------
define(GIF_BODY, "GIF89a\x01\x00\x01\x00\x80\x00\x00\xFF\xFF\xFF\x00\x00\x00\x2C\x00\x00\x00\x00\x01\x00\x01\x00\x00\x02\x02\x44\x01\x00\x3B");

$url  = '';
$msg  = '';
$cl   = Array(); // squidGuard variables: %a %n %i %s %t %u
$err_code = array();

$err_code[301] = "301 Moved Permanently";
$err_code[302] = "302 Found";
$err_code[303] = "303 See Other";
$err_code[305] = "305 Use Proxy";

$err_code[400] = "400 Bad Request";
$err_code[401] = "401 Unauthorized";
$err_code[402] = "402 Payment Required";
$err_code[403] = "403 Forbidden";
$err_code[404] = "404 Not Found";
$err_code[405] = "405 Method Not Allowed";
$err_code[406] = "406 Not Acceptable";
$err_code[407] = "407 Proxy Authentication Required";
$err_code[408] = "408 Request Time-out";
$err_code[409] = "409 Conflict";
$err_code[410] = "410 Gone";
$err_code[411] = "411 Length Required";
$err_code[412] = "412 Precondition Failed";
$err_code[413] = "413 Request Entity Too Large";
$err_code[414] = "414 Request-URI Too Large";
$err_code[415] = "415 Unsupported Media Type";
$err_code[416] = "416 Requested range not satisfiable";
$err_code[417] = "417 Expectation Failed";

$err_code[500] = "500 Internal Server Error";
$err_code[501] = "501 Not Implemented";
$err_code[502] = "502 Bad Gateway";
$err_code[503] = "503 Service Unavailable";
$err_code[504] = "504 Gateway Time-out";
$err_code[505] = "505 HTTP Version not supported";

# ----------------------------------------------------------------------------------------------------------------------
# check arg's
# ----------------------------------------------------------------------------------------------------------------------

if (count($_POST)) {
    $url  = trim($_POST['url']);
    $msg  = $_POST['msg'];
    $cl['a'] = $_POST['a'];
    $cl['n'] = $_POST['n'];
    $cl['i'] = $_POST['i'];
    $cl['s'] = $_POST['s'];
    $cl['t'] = $_POST['t'];
    $cl['u'] = $_POST['u'];
}
elseif (count($_GET)) {
    $url  = trim($_GET['url']);
    $msg  = $_GET['msg'];
    $cl['a'] = $_GET['a'];
    $cl['n'] = $_GET['n'];
    $cl['i'] = $_GET['i'];
    $cl['s'] = $_GET['s'];
    $cl['t'] = $_GET['t'];
    $cl['u'] = $_GET['u'];
}
else {
       # Show 'About page'
        echo get_page(get_about());
        exit();
}

# ----------------------------------------------------------------------------------------------------------------------
# url's
# ----------------------------------------------------------------------------------------------------------------------
if ($url) {
    $err_id = 0;

    // check error code
    foreach ($err_code as $key => $val) {
            if (strpos(strtolower($url), strval($key)) === 0) {
               $err_id = $key;
               break;
            }
    }

    # blank page
    if ($url === TAG_BLANK) {
            echo get_page('');
    }
    # blank image
    elseif ($url === TAG_BLANK_IMG) {
           $msg = trim($msg);
           if(strpos($msg, "maxlen_") !== false) {
              $maxlen = intval(trim(str_replace("maxlen_", "", $url)));
              filter_by_image_size($cl['u'], $maxlen);
              exit();
           }
           else {
              # --------------------------------------------------------------
              # return blank image
              # --------------------------------------------------------------
              header("Content-Type: image/gif;"); //  charset=windows-1251");
              echo GIF_BODY;
           }
    }
    # error code
    elseif ($err_id !== 0) {
            $er_msg = strstr($_GET['url'], ' ');
            echo get_error_page($err_id, $er_msg);
    }
    # redirect url
    elseif ((strpos(strtolower($url), "http://") === 0) or (strpos(strtolower($url), "https://") === 0)) {
            # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            # redirect to specified url
            # ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
            header("HTTP/1.0");
            header("Location: $url", '', 302);
    }
    // error arguments
    else {
        echo get_page("sgerror: error arguments $url");
    }
}
else {
        echo get_page($_SERVER['QUERY_STRING']); //$url . implode(" ", $_GET));
#        echo get_error_page(500);
}

# ~~~~~~~~~~
# Exit
# ~~~~~~~~~~
exit();

# ----------------------------------------------------------------------------------------------------------------------
# functions
# ----------------------------------------------------------------------------------------------------------------------
function get_page($body) {
        $str = Array();
        $str[] = '<html>';
        $str[] = "<body>\n$body\n</body>";
        $str[] = '</html>';
        return implode("\n", $str);
}

# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
# IE displayed self-page, if them size > 1024
# ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
function get_error_page($er_code_id, $err_msg='') {
         global $err_code;
         global $cl;
         global $g;
         global $config;
         $str = Array();

 header("HTTP/1.1 " . $err_code[$er_code_id]);
$str[] = '<!doctype html>
<!--[if lt IE 7 ]> <html lang="pt-br" class="no-js ie6"> <![endif]-->
<!--[if IE 7 ]>    <html lang="pt-br" class="no-js ie7"> <![endif]-->
<!--[if IE 8 ]>    <html lang="pt-br" class="no-js ie8"> <![endif]-->
<!--[if IE 9 ]>    <html lang="pt-br" class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html lang="pt-br" class="no-js">
<!--<![endif]-->

<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, maximum-scale=1, initial-scale=1, user-scalable=0">
<title>Telecom Rio | Acesso Negado</title>

<!-- Favicon -->
<link rel="shortcut icon" href="images/favicon.gif">

<!-- Google fonts -->
<link href="http://fonts.googleapis.com/css?family=Raleway:400,500,600,700,800,900,300,200,100" rel="stylesheet" type="text/css">
<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">

<!--[if lt IE 9]>
<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->

<!--  CSS STYLES  -->
<link rel="stylesheet" href="http://www.telecomrio.com.br/2015/css/reset.css" type="text/css" />
<link rel="stylesheet" href="http://www.telecomrio.com.br/2015/css/style.css" type="text/css" />
<link rel="stylesheet" href="http://www.telecomrio.com.br/2015/css/font-awesome/css/font-awesome.min.css">

<!-- responsive devices styles -->
<link rel="stylesheet" media="screen" href="http://www.telecomrio.com.br/2015/css/responsive-leyouts.css" type="text/css" />

<!-- mega menu -->
<link href="http://www.telecomrio.com.br/2015/js/mainmenu/sticky.css" rel="stylesheet">
<link href="http://www.telecomrio.com.br/2015/js/mainmenu/bootstrap.min.css" rel="stylesheet">
<link href="http://www.telecomrio.com.br/2015/js/mainmenu/demo.css" rel="stylesheet">
<link href="http://www.telecomrio.com.br/2015/js/mainmenu/menu.css" rel="stylesheet">

<!-- color -->
<link rel="stylesheet" href="http://www.telecomrio.com.br/2015/css/colors/blue-telecomrio.css" />

</head>

<body>

<div class="preloader">
  <div class="status"></div>
</div>
<!-- end page loader -->
<div class="site_wrapper"> 
  
  <!-- header -->
  <header> 
  
    <!-- Top header bar -->
    <div id="topHeader">
      <div class="wrapper">
        <div class="top_nav top_nav5">
          
        </div>
      </div>
    </div>
    <!-- end top navigation -->
    
    <div class="scrollto_sticky seven">
      <div class="container"> 
        <!-- Logo -->
        <div class="logo four"><a href="#" id="logo"></a></div>
      </div>
      
       
       </div>     
	</header>
  <div class="clearfix"></div>
  
  <section class="common_section">
    <div class="container">
      <div class="error_holder">
        <h1 class="uppercase title cyan">Ops!</h1>
        <br>
        <h2 class="uppercase">Acesso Negado!</h2>
        <p class="lead">Este site foi bloqueado devido as nossas políticas de acesso web.</p>';
			 if ($cl['n'])        $str[] = "<p>Nome: {$cl['n']} | ";
			 if ($cl['a'])        $str[] = "IP: {$cl['a']} | ";
			 if ($cl['i'])        $str[] = "Usuario: {$cl['i']} | ";
			 if ($cl['s'])        $str[] = "Grupo: {$cl['s']} | ";
			 if ($cl['t'])        $str[] = "Categoria: {$cl['t']} </p> ";
 		     if ($cl['u'])        $str[] = "<p><b>URL: {$cl['u']}</b></p>";
      $str[] = '<p>Este acesso foi negado devido uma das seguintes razões:</p>
				<ul style="list-style-type:bullet; text-align:left; color:#003A63;">
					<li>- Conteúdo inapropriado.</li>
					<li>- Acesso restrito durente o horário de expediente.</li>
					<li>- A politica aplicada ao seu usuário não lhe concede este tipo de acesso.</li>
					<li>- Caso este seja um bloqueio indevido favor contactar o suporte informando os dados aqui exposto.</li>
				</ul>
	  </div>
    </div>
  </section>
  <!-- end section 1 -->
  <div class="clearfix"></div>

    

	<footer>
        <div class="footer five">
                 
        </div>
      </div>
    <!--end footer-->
  
	<div class="copyrights">
		<div class="container">
          <div class="one_half"><span>Copyright&reg; 2015 Telecom Rio.</span></div>
          <div class="one_half last"></div>
        </div>
    </div>
    <!--end copyrights--> 
	</footer>
</div>
<!--end sitewraper--> 

<a href="#" class="scrollup"></a> 
<!-- end scroll to top of the page--> 

<!-- ######### JS FILES ######### --> 

<!-- get jQuery from the google apis --> 
<script type="text/javascript" src="http://www.telecomrio.com.br/2015/js/universal/jquery.js"></script> 

<!-- page loader -->
<script>
(function($) {
  "use strict";
// makes sure the whole site is loaded
jQuery(window).load(function() {
	"use strict";
        // will first fade out the loading animation
	jQuery(".status").fadeOut();
        // will fade out the whole DIV that covers the website.
	jQuery(".preloader").delay(1000).fadeOut("slow");
})
})(jQuery);

</script>

<!-- mega menu --> 
<script src="js/mainmenu/bootstrap.min.js"></script> 
<script src="js/mainmenu/customeUI.js"></script> 

<!-- scroll up --> 
<script src="js/scrolltotop/totop.js" type="text/javascript"></script>
</body>

</html>';

return implode("\n", $str);
 }

function get_about() {
        global $err_code;
        global $page_info;
        $str = Array();

        // about info
        $s = str_replace("\n", "<br>", $page_info);
        $str[] = $s;
        $str[] = "<br>";

        $str[] = '<table>';
        $str[] = ' <b>HTTP error codes (ERROR_CODE):</th></tr>';
        foreach($err_code as $val) {
                $str []= "<tr><td>$val";
       }
        $str[] = '</table>';

        return implode("\n", $str);
}

function filter_by_image_size($url, $val_size) {

          # load url header
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $url);
          curl_setopt($ch, CURLOPT_HEADER, 1);
          curl_setopt($ch, CURLOPT_NOBODY, 1);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
          $hd = curl_exec($ch);
          curl_close($ch);

         $size = 0;
         $SKEY = "content-length:";
         $s_tmp = strtolower($hd);
         $s_tmp = str_replace("\n", " ", $s_tmp); # replace all "\n"
         if (strpos($s_tmp, $SKEY) !== false) {
             $s_tmp = trim(substr($s_tmp, strpos($s_tmp, $SKEY) + strlen($SKEY)));
             $s_tmp = trim(substr($s_tmp, 0, strpos($s_tmp, " ")));
             if (is_numeric($s_tmp))
                  $size = intval($s_tmp);
             else $size = 0;
         }

         # === check url type and content size ===
         # redirect to specified url
         if (($size !== 0) && ($size < $val_size)) {
              header("HTTP/1.0");
              header("Location: $url", '', 302);
         }
         # return blank image
         else {
              header("Content-Type: image/gif;");
              echo GIF_BODY;
         }
}
?>