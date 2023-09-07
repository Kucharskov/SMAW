<?php
ob_start();
/***************************************************************************
 *
 *	SMAW v3.0 beta (So Minimize thAt Width)
 *	with love by M. Kucharskov (http://kucharskov.pl)
 *
 *	This is free software and it's distributed under Creative Commons BY-NC-SA License.
 *
 *	SMAW is written in the hopes that it can be useful to people.
 *	It has NO WARRANTY and when you use it, the author is not responsible
 *	for how it works (or doesn't).
 *
 ***************************************************************************/

// Configuration of SMAW
$SMAW_CONFIG["SiteName"]	= "Page powered by SMAW";		// Name of your website
$SMAW_CONFIG["Language"]	= "pl";							// Name of your language code (available: en, pl)
$SMAW_CONFIG["BaseFile"]	= "SMAWurls.txt";				// Name of your links file database. Remember to lock it via htaccess!
$SMAW_CONFIG["ShowLast"]	= 5;							// Show last X shortened URLs (0 - Disabled, X - Enabled to show X last)
$SMAW_CONFIG["LinksCount"]	= 1;							// Show link counter with all shortened links (0 - Disabled, 1 - Enabled)
$SMAW_CONFIG["RewriteMod"]	= 1;							// Use mod_rewrite links "domain.com/X" instead of "domain.com/index.php?id=X" (0 - Disabled, 1 - Enabled)
$SMAW_CONFIG["HashLinks"]	= 0;							// Use base64 function to hash link ID (0 - Disabled, 1 - Enabled)
$SMAW_CONFIG["FixSlash"]	= 0;							// Fixed doubled slash // for some apache/nginx servers (0 - Disabled, 1 - Enabled)

// Translations of SMAW
// English by M. Kucharskov
$SMAW_TRANS["en"] = array(
	"SMAWinfo"		=> "Paste link, which you want make shorter",
	"SMAWthat"		=> "Make shorter!",
	"SMAWurl"		=> "Link:",
	"CountURLs"		=> "Links:",
	"LastURLs"		=> "Last shortened links",
	"NoLastURLs"	=> "No recent shortened links!",
	"BadURL"		=> "Entered link is incorect!",
	"ShortenURL"	=> "Shortened link: ",
	"LoadingURL"	=> "Redirecting...",
	"DeletedURL"	=> "Redirect removed from database",
	"NotExistURL"	=> "Chosen redirect does not exist or was deleted",
	"BaseProblem"	=> "File with base does not exist or don't have assigned properly CHMOD (777) permissions"
);
// Polish by M. Kucharskov
$SMAW_TRANS["pl"] = array(
	"SMAWinfo"		=> "Wklej adres, który chcesz skrócić",
	"SMAWthat"		=> "Skróć adres!",
	"SMAWurl"		=> "Adres:",
	"CountURLs"		=> "Adresów:",
	"LastURLs"		=> "Ostatnio skrócone adresy",
	"NoLastURLs"	=> "Brak ostatnio skróconych adresów!",
	"BadURL"		=> "Wprowadzony adres jest niepoprawny!",
	"ShortenURL"	=> "Skrócony adres: ",
	"LoadingURL"	=> "Przekierowywanie...",
	"DeletedURL"	=> "Przekierowanie usunięte z bazy",
	"NotExistURL"	=> "Wybrane przekierowanie nie istnieje lub zostało usunięte",
	"BaseProblem"	=> "Plik z bazą nie istnieje lub nie ma danych poprawnych praw CHMOD (777)"
);

// Function to display text with multilang text security
function ShowText($string) {
	global $SMAW_CONFIG;
	global $SMAW_TRANS;
	
	if(!$SMAW_TRANS[$SMAW_CONFIG["Language"]][$string]) return $SMAW_TRANS["en"][$string];
	else return $SMAW_TRANS[$SMAW_CONFIG["Language"]][$string];
}

// Function for getting page title
function GetPageTitle($url){
	if(!($data = file_get_contents($url))) return false;
	if(preg_match("@<title>(.+)<\/title>@", $data, $title)) return trim($title[1]);
	else return false;
}

// Function for getting URL for specific ID
function GetURL($id) {
	global $SMAW_CONFIG;

	if($id < 0) return false;
	$urls = file($SMAW_CONFIG["BaseFile"]);
	if($id > count($urls)) return false;

	$url = $urls[$id - 1]; // Offset for first link
	$url = trim($url);

	return $url;
}

// Function for getting array of last n URLs
function GetLastURLs($amount) {
	global $SMAW_CONFIG;

	$last = [];
	$urls = file($SMAW_CONFIG["BaseFile"]);
	$indexShift = count($urls) - $SMAW_CONFIG["ShowLast"] + 1; // Offset for first link
	foreach(array_slice($urls, -$amount) as $index => $value){
	   $last[$index + $indexShift] = $value;
	}
	
	return $last;
}

// Function for saving URL in DB with checking if exact URL was saved earlier
function SaveURL($url) {
	global $SMAW_CONFIG;

	if(!filter_var($url, FILTER_VALIDATE_URL)) return false;
	
	$urls = file($SMAW_CONFIG["BaseFile"]);
	foreach($urls as $index => $value) {
		if(trim($value) === $url) return $index + 1; // Offset for first link
	}
	
	file_put_contents($SMAW_CONFIG["BaseFile"], "{$url}\r\n", FILE_APPEND);
	return count($urls) + 1; // Offset for first link
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
	.pricing-table .bullet-item .prefix { line-height: 2.3125rem !important; }
	.pricing-table .bullet-item input { margin: 0; }
	.pricing-table .bullet-item:last-child { border-bottom: none !important; }
	.pricing-table .cta-button { padding: 0; }
	.pricing-table .success { background-color: #43ac6a; border-color: #368a55; color: #fff; }
	.pricing-table .alert { background-color: #f04124; border-color: #cf2a0e; color: #fff; }
	.pricing-table button { margin-bottom: 0.9375rem; color: #fff; }
	.pricing-table.lastshorts { margin-top: 1rem; }
	.overflowfix { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
	.noborder { border-bottom: none !important; }
	.clearfix { margin-bottom: 3rem; }
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
				if(isset($_GET["id"])) {
					$id = (string) $_GET["id"];
					$url = GetURL($id);
					
					if(!$url) {
						echo "<li class='bullet-item alert'>".ShowText("NotExistURL")."</li>\n";
						header("Refresh: 3; url={$_SERVER['PHP_SELF']}");
					} else {
						if(GetPageTitle($url)) echo "<li class='bullet-item'>".GetPageTitle($url)."</li>\n";
						echo "<li class='bullet-item'>".ShowText("LoadingURL")."</li>\n";
						header("Refresh: 3; url={$url}");
					}
				}
				
				if(isset($_POST["url"])) {
					$status = SaveURL($_POST["url"]);
					
					if(!$status) {
						echo "<li class='price alert'>".ShowText("BadURL")."</li>\n";
					} else {
						$temp_URL = $status; // REFACTOR - Generating full URL for ID
						echo "<li class='price success'>".ShowText("ShortenURL")."<a href='{$temp_URL}'>{$temp_URL}</a></li>\n";
					}
				} 
				
				if(!isset($_GET["id"])) {
					echo "<li class='price'>".ShowText("SMAWinfo")."</li>\n";
			?>
				<form action="index.php" method="post">
					<li class="bullet-item noborder">
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
			<?php } ?>
		</ul>
		<?php
			if($SMAW_CONFIG["ShowLast"] > 0 && !isset($_GET["id"])) {
		?>
		<ul class="pricing-table lastshorts">
			<li class="title"><?php echo ShowText("LastURLs"); ?></li>
			<?php
				$last = GetLastURLs($SMAW_CONFIG["ShowLast"]);
				
				if(count($last) === 0) echo "<li class='price alert'>".ShowText("NoLastURLs")."</li>\n";
				foreach($last as $index => $value) {
					if($value === "") echo "<li class='bullet-item'><span class='alert label'>".ShowText("DeletedURL")."</span></li>\n";
					else {
						$temp_URL = $index; // REFACTOR - Generating full URL for ID
						echo "<li class='bullet-item overflowfix'><a href='{$temp_URL}'>{$value}</a></li>\n";
					}
				}
			?>
		</ul>
		<?php }	?>
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