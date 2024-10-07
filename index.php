<?php
ob_start();
/***************************************************************************
 *
 *	SMAW v3.1 beta (So Minimize thAt Width)
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
$SMAW_CONFIG["HashLinks"]	= 1;							// Use HashInt class to hash link ID (0 - Disabled, 1 - Enabled)
$SMAW_CONFIG["HashSize"]	= 3;							// Set size for hashed URLs. Small values might occurs with overlaping! (Default: 5)
$SMAW_CONFIG["FixSlash"]	= 0;							// Add slash in URL for fixes in some apache/nginx servers (0 - Disabled, 1 - Enabled)

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
	"BaseProblem"	=> "Plik z bazą nie istnieje lub nie posiada poprawnych uprawnień CHMOD (777)"
);
// German by r0BIT (https://twitter.com/0xr0BIT)
$SMAW_TRANS["de"] = array(
    "SMAWinfo"		=> "Link zum Kürzen einfügen",
    "SMAWthat"		=> "Kürzen!",
    "SMAWurl"		=> "Link:",
    "CountURLs"		=> "Links:",
    "LastURLs"		=> "Kürzlich gekürzte Links",
    "NoLastURLs"	=> "Keine kürzlich gekürzten Links!",
    "BadURL"		=> "Der eingegebene Link ist nicht korrekt!",
    "ShortenURL"	=> "Gekürzter Link: ",
    "LoadingURL"	=> "Weiterleiten...",
    "DeletedURL"	=> "Weiterleitung aus Datenbank entfernt",
    "NotExistURL"	=> "Die gewünschte Weiterleitung existiert nicht oder wurde gelöscht",
    "BaseProblem"	=> "Die Datei existiert nicht oder hat nicht die erforderlichen Berechtigungen (CHMOD 777)"
);
// Italian by P0 (https://twitter.com/Pzz02)
$SMAW_TRANS["it"] = array(
    "SMAWinfo"		=> "Incolla il link che vuoi accorciare",
    "SMAWthat"		=> "Accorcia!",
    "SMAWurl"		=> "Link:",
    "CountURLs"		=> "Links:",
    "LastURLs"		=> "Ultimi links accorciati",
    "NoLastURLs"	=> "Non ci sono links accorciati recenti",
    "BadURL"		=> "Link incorretto!",
    "ShortenURL"	=> "Link accorciato: ",
    "LoadingURL"	=> "Reindirizzando...",
    "DeletedURL"	=> "Reindirizzamento rimosso dal database",
    "NotExistURL"	=> "Reindirizzamento selezionato non esiste o cancellato",
    "BaseProblem"	=> "Database file non esiste o non ha i permessi corretti (CHMOD 777)"
);

// Whole class for generating proper hashes for specific IDs
// Source: https://github.com/dmhendricks/hash-int/
/**
* Generate a short hash from an integer
* @link http://web.archive.org/web/20130727034425/http://blog.kevburnsjr.com/php-unique-hash
*/
class HashInt {
	/* Key: Next prime greater than 62 ^ n / 1.618033988749894848 */
	/* Value: modular multiplicative inverse */
	private static $golden_primes = array(
		'1'                  => '1',
		'41'                 => '59',
		'2377'               => '1677',
		'147299'             => '187507',
		'9132313'            => '5952585',
		'566201239'          => '643566407',
		'35104476161'        => '22071637057',
		'2176477521929'      => '294289236153',
		'134941606358731'    => '88879354792675',
		'8366379594239857'   => '7275288500431249',
		'518715534842869223' => '280042546585394647',
		'32160363160257795059' => '44574265781059650875',
		'1993942515935983293581' => '535548952060639952453',
		'123624435988030964199509' => '9884567988947108138237'
	);

	private static $chars62 = array(
		0=>48,1=>49,2=>50,3=>51,4=>52,5=>53,6=>54,7=>55,8=>56,9=>57,10=>65,
		11=>66,12=>67,13=>68,14=>69,15=>70,16=>71,17=>72,18=>73,19=>74,20=>75,
		21=>76,22=>77,23=>78,24=>79,25=>80,26=>81,27=>82,28=>83,29=>84,30=>85,
		31=>86,32=>87,33=>88,34=>89,35=>90,36=>97,37=>98,38=>99,39=>100,40=>101,
		41=>102,42=>103,43=>104,44=>105,45=>106,46=>107,47=>108,48=>109,49=>110,
		50=>111,51=>112,52=>113,53=>114,54=>115,55=>116,56=>117,57=>118,58=>119,
		59=>120,60=>121,61=>122
	);

	/**
	* Create a hash of defined length from integer
	*
	* @param int $num Integer to hash
	* @param int $len Length of hash to return
	* @return string
	*/
	public static function hash( $num, $len = 6 ) {
		$ceil = bcpow( 62, $len );
		$primes = array_keys( self::$golden_primes );
		$prime = $primes[$len];
		$dec = bcmod( bcmul( $num, $prime ), $ceil );
		$hash = self::base62( $dec );
		return str_pad( $hash, $len, '0', STR_PAD_LEFT );
	}

	/**
	* Unhash a hashed string
	*
	* @param string $num Integer to hash
	* @param int $len Length of hash to return
	* @return string
	*/
	public static function unhash( $hash ) {
		$len = strlen( $hash );
		$ceil = bcpow( 62, $len );
		$mmiprimes = array_values( self::$golden_primes );
		$mmi = $mmiprimes[$len];
		$num = self::unbase62( $hash );
		$dec = bcmod( bcmul( $num, $mmi ), $ceil );
		return $dec;
	}

	public static function base62( $int ) {
		$key = '';
		while( bccomp( $int, 0 ) > 0 ) {
			$mod = bcmod( $int, 62 );
			$key .= chr( self::$chars62[$mod] );
			$int = bcdiv( $int, 62 );
		}
		return strrev( $key );
	}

	public static function unbase62( $key ) {
		$int = 0;
		foreach( str_split( strrev( $key ) ) as $i => $char ) {
			$dec = array_search( ord( $char ), self::$chars62 );
			$int = bcadd( bcmul( $dec, bcpow( 62, $i ) ), $int );
		}
		return $int;
	}
}

// Function to display text with multilang text security
function ShowText($string) {
	global $SMAW_CONFIG;
	global $SMAW_TRANS;
	
	if(!$SMAW_TRANS[$SMAW_CONFIG["Language"]][$string]) return $SMAW_TRANS["en"][$string];
	else return $SMAW_TRANS[$SMAW_CONFIG["Language"]][$string];
}

// Function for getting page title
function GetPageTitle($url){
	$dom = new DOMDocument();
	$data = @file_get_contents($url);
	
	if(strlen($data) === 0) return false;
	
	if($dom->loadHTML(mb_convert_encoding($data, "HTML-ENTITIES", "UTF-8"))) {
		$list = $dom->getElementsByTagName("title");
		if ($list->length > 0) {
			return htmlspecialchars($list->item(0)->textContent);
		}
	}
	
	return false;
}

// Function for getting URL for specific ID
function GetURL($id) {
	global $SMAW_CONFIG;

	if($SMAW_CONFIG["HashLinks"]) $id = HashInt::unhash($id);

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
	   $last[$index + $indexShift] = htmlspecialchars($value);
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
	
	file_put_contents($SMAW_CONFIG["BaseFile"], "{$url}".PHP_EOL, FILE_APPEND);
	return count($urls) + 1; // Offset for first link
}

// Function for generating URL with all tweaks
function GenerateURL($id) {
	global $SMAW_CONFIG;
	
	$url = "http";
	$url .= empty($_SERVER["HTTPS"]) ? "":"s";	
	$url .= "://{$_SERVER["HTTP_HOST"]}";
	$url .= parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
	if($SMAW_CONFIG["FixSlash"]) $url .= "/";
	if(!$SMAW_CONFIG["RewriteMod"]) $url .= "?id=";
	if($SMAW_CONFIG["HashLinks"]) $url .= HashInt::hash($id, $SMAW_CONFIG["HashSize"]);
	else $url .= $id;
	
	return $url;
}

// Function returning amount of all links (I count deleted also)
function CountURLs() {
	global $SMAW_CONFIG;
	
	return count(file($SMAW_CONFIG["BaseFile"]));
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

	<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/css/foundation.min.css" rel="stylesheet">
	<link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/css/normalize.min.css" rel="stylesheet">
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
</head>
<body>

<div class="row">
	<div class="large-5 large-centered medium-7 medium-centered small-12 small-centered columns">
		<?php if(!file_exists($SMAW_CONFIG["BaseFile"]) || !is_writable($SMAW_CONFIG["BaseFile"]) || !is_readable($SMAW_CONFIG["BaseFile"])) echo "<div class='fileproblem text-justify'>".ShowText("BaseProblem")."</div>\n"; ?>
		<ul class="pricing-table">
			<li class="title"><?php echo $SMAW_CONFIG["SiteName"]; ?></li>
			<?php
			
				if(!empty($_GET["id"])) {
					$id = (string) $_GET["id"];
					$url = GetURL($id);
					
					if(!$url) {
						echo "<li class='bullet-item alert'>".ShowText("NotExistURL")."</li>\n";
						header("Refresh: 3; url={$_SERVER["PHP_SELF"]}");
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
						echo "<li class='price success'>".ShowText("ShortenURL")."<a href='".GenerateURL($status)."'>".GenerateURL($status)."</a></li>\n";
					}
				} 
				
				if(empty($_GET["id"])) {
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
			if($SMAW_CONFIG["ShowLast"] > 0 && empty($_GET["id"])) {
		?>
		<ul class="pricing-table lastshorts">
			<li class="title"><?php echo ShowText("LastURLs"); ?></li>
			<?php
				$last = GetLastURLs($SMAW_CONFIG["ShowLast"]);
				
				if(count($last) === 0) echo "<li class='price alert'>".ShowText("NoLastURLs")."</li>\n";
				foreach($last as $index => $value) {
					$value = trim($value);
					if($value === "") echo "<li class='bullet-item'><span class='alert label'>".ShowText("DeletedURL")."</span></li>\n";
					else echo "<li class='bullet-item overflowfix'><a href='".GenerateURL($index)."'>{$value}</a></li>\n";
				}
			?>
		</ul>
		<?php }	?>
		<div class="clearfix">
			<?php if($SMAW_CONFIG["LinksCount"] === 1) echo "<span class='left'>".ShowText("CountURLs")." ".CountURLs()."</span>\n"; ?>
			<a class="right" href="https://github.com/Kucharskov/SMAW">SMAW</a>
		</div>
	</div>
</div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.3/js/foundation.min.js"></script>
<script type="text/javascript">
$(document).foundation();
</script>

</body>
</html>
<?php ob_end_flush(); ?>