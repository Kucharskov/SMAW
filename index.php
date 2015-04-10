<?php
/***************************************************************************
 *
 *	SMAW v1.1 beta (So Minimize thAt Width)
 *	with love by M. Kucharskov & P. Kowalczyk (http://kucharskov.pl)
 *
 *	This is free software and it's distributed under Creative Commons BY-NC-SA License.
 *
 *	SMAW is written in the hopes that it can be useful to people.
 *	It has NO WARRANTY and when you use it, the author is not responsible
 *	for how it works (or doesn't).
 *
 ***************************************************************************/

/*
 *	Configuration of SMAW
 */
$SMAW_CONFIG["SiteName"]	= "Page powered by SMAW";
$SMAW_CONFIG["Language"]	= "en";
$SMAW_CONFIG["BaseFile"]	= "SMAWurls.txt";
$SMAW_CONFIG["RewriteMod"]	= 0;
$SMAW_CONFIG["RegEx"] = "^(https?|ftp)\:\/\/([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?[a-z0-9+\$_-]+(\.[a-z0-9+\$_-]+)*(\:[0-9]{2,5})?(\/([a-z0-9+\$_-]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@/&%=+\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?\$";

//Translations of SMAW
//English by P. Kowalczyk
$SMAW_TRANS["en"] = array(
	"SMAWinfo"		=> "Paste link, which you want make shorter.",
	"SMAWthat"		=> "Make shorter",
	"SMAWurl"		=> "Link:",
	"BadURL"		=> "Entered link is incorect!",
	"ShortenURL"	=> "Shortened link: ",
	"LoadingURL"	=> "Redirecting...",
	"DeletedURL"	=> "Choosen redirect does not exist lub was deleted",
	"BaseProblem"	=> "File with base does not exist or have assigned wrong CHMOD (777) permissions."
);
//Polish by M. Kucharskov
$SMAW_TRANS["pl"] = array(
	"SMAWinfo"		=> "Wklej link, który chcesz skrócić.",
	"SMAWthat"		=> "Skróć link",
	"SMAWurl"		=> "Adres:",
	"BadURL"		=> "Wprowadzony link jest niepoprawny!",
	"ShortenURL"	=> "Skrócony adres: ",
	"LoadingURL"	=> "Przekierowywanie...",
	"DeletedURL"	=> "Wybrane przekierowanie nie istnieje lub zostało usunięte",
	"BaseProblem"	=> "Plik z bazą nie istnieje lub ma nadane niepoprawne prawa CHMOD (777)."
);

//Function ShowText with multilang text security
function ShowText($string) {
	global $SMAW_CONFIG;
	global $SMAW_TRANS;

	if(!$SMAW_TRANS[$SMAW_CONFIG["Language"]][$string]) return $SMAW_TRANS["en"][$string];
	else return $SMAW_TRANS[$SMAW_CONFIG["Language"]][$string];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo $SMAW_CONFIG["SiteName"]; ?></title>
	<meta http-equiv="Content-type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="generator" content="SMAW (http://kucharskov.pl)">

	<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/css/foundation.min.css" rel="stylesheet">
	<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/css/normalize.min.css" rel="stylesheet">
	<style type="text/css">
	<!--
	a {	color: #333; }
	a:hover { color: #000; }
	.fileproblem { margin: 0.9375rem 0 0; padding: 0.9375rem 1.25rem; background-color: #f04124; border-color: #cf2a0e; color: #fff; }
	.pricing-table { margin-top: 3rem; margin-bottom: 0; }
	.pricing-table .price { font-size: 1rem; }
	.pricing-table .bullet-item { border-bottom: none; }
	.pricing-table .bullet-item .prefix { line-height: 2.3125rem !important; }
	.pricing-table .bullet-item input { margin: 0; }
	.pricing-table .cta-button { padding: 0; }
	.pricing-table .success { background-color: #43ac6a; border-color: #368a55; color: #fff; }
	.pricing-table .alert { background-color: #f04124; border-color: #cf2a0e; color: #fff; }
	.pricing-table button { margin-bottom: 0.9375rem; color: #fff; }
	-->
	</style>
	
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.1/js/foundation.min.js"></script>
	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->	
</head>
<body>

<div class="row">
	<div class="large-5 large-centered medium-7 medium-centered small-12 small-centered columns">
		<?php if(!file_exists($SMAW_CONFIG["BaseFile"]) || !is_writable($SMAW_CONFIG["BaseFile"]) || !is_readable($SMAW_CONFIG["BaseFile"])) echo "<div class='fileproblem'>".ShowText("BaseProblem")."</div>"; ?>
		<ul class="pricing-table">
			<li class="title"><?php echo $SMAW_CONFIG["SiteName"]; ?></li>
			<?php
				(int) $_GET["id"];
				if($_GET["id"] > 0) {
					$SMAW_Urls = file($SMAW_CONFIG["BaseFile"]);
					$SMAW_Url = $SMAW_Urls[$_GET["id"]-1];
					$SMAW_Url = str_replace("\r\n", "", $SMAW_Url);
					if(!$SMAW_Url) {
			?>
			<li class="bullet-item alert"><?php echo ShowText("DeletedURL"); ?></li>
			<?php
						header("Refresh: 2; url={$_SERVER['PHP_SELF']}");
					} else {
			?>
			<li class="bullet-item"><?php echo ShowText("LoadingURL"); ?></li>
			<?php
						header("Refresh: 2; url={$SMAW_Url}");
					}
				} else {
					if(isset($_POST["url"])) {	
						if(eregi($SMAW_CONFIG["RegEx"], $_POST["url"])) {
							$SMAW_Urls = file($SMAW_CONFIG["BaseFile"]);
							foreach($SMAW_Urls as $SMAW_Row)
							{
								$SMAW_RowID++;
								if($SMAW_Row === "{$_POST["url"]}\r\n") $SMAW_ID = $SMAW_RowID;
							}
							if(!isset($SMAW_ID)) {
								file_put_contents($SMAW_CONFIG["BaseFile"], "{$_POST["url"]}\r\n", FILE_APPEND);
								$SMAW_ID = count($SMAW_Urls)+1;
							}
							
							$SMAW_Url = "http://{$_SERVER["HTTP_HOST"]}{$_SERVER["PHP_SELF"]}?id={$SMAW_ID}";
							if($SMAW_CONFIG["RewriteMod"] === 1) {
								$SMAW_FName	= explode("/", $_SERVER["PHP_SELF"]);
								$SMAW_FName = $SMAW_FName[(count($SMAW_FName)-1)];
								$SMAW_Url = str_replace("{$SMAW_FName}?id=", "", $SMAW_Url);
							}
							$SMAW_OU = "<li class='price success'>".ShowText("ShortenURL")."<a href='{$SMAW_Url}'>{$SMAW_Url}</a></li>";
						} else {
							$SMAW_OU = "<li class='price alert'>".ShowText("BadURL")."</li>";
						}
					} else {
						$SMAW_OU = "<li class='price'>".ShowText("SMAWinfo")."</li>";
					}
					
					echo $SMAW_OU;
			?>
				<form action="index.php" method="post">
					<li class="bullet-item">
						<div class="row collapse">
							<div class="small-3 columns">
								<span class="prefix"><?php echo ShowText("SMAWurl"); ?></span>
							</div>
							<div class="small-9 columns">
								<input type="text" name="url" placeholder="">
							</div>
						</div>
					</li>
					<li class="cta-button"><button type="subimt" class="small"><?php echo ShowText("SMAWthat"); ?></button></a></li>
				</form>
			<?php
				}
			?>
		</ul>
		<p class="text-right"><a href="http://kucharskov.pl">M. Kucharskov</a></p>
	</div>
</div>
	
<script type="text/javascript">
$(document).foundation();
</script>

</body>
</html>