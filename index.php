<?php
ob_start();
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
$SMAW_CONFIG["ShowLast"]	= 0;
$SMAW_CONFIG["LinksCount"]	= 0;
$SMAW_CONFIG["RewriteMod"]	= 0;

//Translations of SMAW
//English by P. Kowalczyk
$SMAW_TRANS["en"] = array(
	"SMAWinfo"		=> "Paste link, which you want make shorter.",
	"SMAWthat"		=> "Make shorter",
	"SMAWurl"		=> "Link:",
	"CountURLs"		=> "Links:",
	"BadURL"		=> "Entered link is incorect!",
	"ShortenURL"	=> "Shortened link: ",
	"LoadingURL"	=> "Redirecting...",
	"DeletedURL"	=> "Choosen redirect does not exist lub was deleted",
	"BaseProblem"	=> "File with base does not exist or don't have assigned properly CHMOD (777) permissions."
);
//Polish by M. Kucharskov
$SMAW_TRANS["pl"] = array(
	"SMAWinfo"		=> "Wklej link, który chcesz skrócić.",
	"SMAWthat"		=> "Skróć link",
	"SMAWurl"		=> "Adres:",
	"CountURLs"		=> "Adresów:",
	"BadURL"		=> "Wprowadzony link jest niepoprawny!",
	"ShortenURL"	=> "Skrócony adres: ",
	"LoadingURL"	=> "Przekierowywanie...",
	"DeletedURL"	=> "Wybrane przekierowanie nie istnieje lub zostało usunięte",
	"BaseProblem"	=> "Plik z bazą nie istnieje lub nie ma nadych poprawnych praw CHMOD (777)."
);

//Function ShowText with multilang text security
function ShowText($string) {
	global $SMAW_CONFIG;
	global $SMAW_TRANS;
	if(!$SMAW_TRANS[$SMAW_CONFIG["Language"]][$string]) return $SMAW_TRANS["en"][$string];
	else return $SMAW_TRANS[$SMAW_CONFIG["Language"]][$string];
}

// Function to getting page title
function get_page_title($url){
	if(!($data = file_get_contents($url))) return false;
	if(preg_match("@<title>(.+)<\/title>@", $data, $title)) return trim($title[1]);
	else return false;
}
?>
<!DOCTYPE html>
<html lang="<?php echo $SMAW_CONFIG["Language"]; ?>">
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
	.pricing-table .bullet-item.dotted { border-bottom: 1px dotted #ddd; }
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
		<?php if(!file_exists($SMAW_CONFIG["BaseFile"]) || !is_writable($SMAW_CONFIG["BaseFile"]) || !is_readable($SMAW_CONFIG["BaseFile"])) echo "<div class='fileproblem text-justify'>".ShowText("BaseProblem")."</div>\n"; ?>
		<ul class="pricing-table">
			<li class="title"><?php echo $SMAW_CONFIG["SiteName"]; ?></li>
			<?php
				(int) $_GET["id"];
				$SMAW_Urls = file($SMAW_CONFIG["BaseFile"]);
				$SMAW_IDs = count($SMAW_Urls);
				if($_GET["id"] > 0) {
					$SMAW_Url = $SMAW_Urls[$_GET["id"]-1];
					$SMAW_Url = str_replace("\r\n", "", $SMAW_Url);
					if(!$SMAW_Url) {
						echo "<li class='bullet-item alert'>".ShowText("DeletedURL")."</li>\n";
						header("Refresh: 3; url={$_SERVER['PHP_SELF']}");
					} else {
						if(get_page_title($SMAW_Url)) echo "<li class='bullet-item dotted'>".get_page_title($SMAW_Url)."</li>\n";
						echo "<li class='bullet-item'>".ShowText("LoadingURL")."</li>\n";
						header("Refresh: 3; url={$SMAW_Url}");
					}
				} else {
					if(isset($_POST["url"])) {
						if(filter_var($_POST["url"], FILTER_VALIDATE_URL)) {
							foreach($SMAW_Urls as $SMAW_Row) {
								$SMAW_RowID++;
								if($SMAW_Row === "{$_POST["url"]}\r\n") $SMAW_ID = $SMAW_RowID;
							}
							if(!isset($SMAW_ID)) {
								file_put_contents($SMAW_CONFIG["BaseFile"], "{$_POST["url"]}\r\n", FILE_APPEND);
								$SMAW_ID = $SMAW_IDs+1;
							}
							$SMAW_Url = "http://{$_SERVER["HTTP_HOST"]}{$_SERVER["PHP_SELF"]}?id={$SMAW_ID}";
							if($SMAW_CONFIG["RewriteMod"] === 1) {
								$SMAW_FName	= explode("/", $_SERVER["PHP_SELF"]);
								$SMAW_FName = $SMAW_FName[(count($SMAW_FName)-1)];
								$SMAW_Url = str_replace("{$SMAW_FName}?id=", "", $SMAW_Url);
							}
							echo "<li class='price success'>".ShowText("ShortenURL")."<a href='{$SMAW_Url}'>{$SMAW_Url}</a></li>\n";
						} else {
							echo "<li class='price alert'>".ShowText("BadURL")."</li>\n";
						}
					} else {
						echo "<li class='price'>".ShowText("SMAWinfo")."</li>\n";
					}
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
					<li class="cta-button"><button type="subimt" class="small"><?php echo ShowText("SMAWthat"); ?></button></li>
				</form>
			<?php
				}
			?>
		</ul>
		<?php
			if($SMAW_CONFIG["ShowLast"] > 0) {
		?>
		<ul class="pricing-table">
			<li class="title">Ostatnio skrócone</li>
			<?php				
				if($SMAW_IDs >= $SMAW_CONFIG["ShowLast"]) $SMAW_LastUrls = array_slice($SMAW_Urls, $SMAW_IDs-$SMAW_CONFIG["ShowLast"]);
				else { 
					$SMAW_LastUrls = $SMAW_Urls;
					$SMAW_CONFIG["ShowLast"] = $SMAW_IDs;
				}
				for($SMAW_Count = 0; $SMAW_Count <= $SMAW_CONFIG["ShowLast"]-1; $SMAW_Count++) {
					if($SMAW_Count != $SMAW_CONFIG["ShowLast"]-1) {
						if($SMAW_LastUrls[$SMAW_Count] === "\r\n") echo "<li class='bullet-item dotted'>".ShowText("DeletedURL")."</li>\n";
						else echo "<li class='bullet-item dotted'><a href='{$SMAW_LastUrls[$SMAW_Count]}'>{$SMAW_LastUrls[$SMAW_Count]}</a></li>\n";
					} else {
						if($SMAW_LastUrls[$SMAW_Count] === "\r\n") echo "<li class='bullet-item'>".ShowText("DeletedURL")."</li>\n";
						else echo "<li class='bullet-item'><a href='{$SMAW_LastUrls[$SMAW_Count]}'>{$SMAW_LastUrls[$SMAW_Count]}</a></li>\n";
					}
				}
			}
			?>
		</ul>
		<div class="clearfix">
			<?php if($SMAW_CONFIG["LinksCount"] === 1) echo "<span class='left'>".ShowText("CountURLs")." {$SMAW_IDs}</span>\n"; ?>
			<a class="right" href="http://kucharskov.pl">M. Kucharskov</a>
		</div>
	</div>
</div>
	
<script type="text/javascript">
$(document).foundation();
</script>

</body>
</html>
<?php ob_end_flush(); ?>