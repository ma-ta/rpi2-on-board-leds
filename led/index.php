<!--
	PHP/HTML5 index page
	ENCODING: UTF-8, CRLF

	============================
	PROJECT: RPi2 on-board LEDs:
	============================

	DESCRIPTION: web GUI for controlling the Raspberry Pi 2 (Model B) on-board LEDs
	VERSION: 0.1.1 | 2015-06
	LANGUAGE (ISO 639-3): eng, ces

	AUTHOR: Martin Tábor
	TIPs Bitcoin address:
-->

<?php
	define ("VERZE_APLIKACE", "version:&nbsp;0.1.1");
	define ("DATUM_SESTAVENI", "2015-06");
	define ("CHYBA", "Sorry, but something went wrong. Are you using the RPi2 Model B and OS Raspbian?");
	define ("STAV_VYPNUTO", "<div id=\"ovladace_stav-vypnuto\" class=\"ovladace_stav-vypnuto\" title=\"This LED is not lightning.\">Off</div>");
	define ("STAV_ZAPNUTO", "<div id=\"ovladace_staV-zapnuto\" class=\"ovladace_stav-zapnuto\" title=\"This LED is lightning.\">On</div>");
	
	define ("LED0", "/sys/class/leds/led0/brightness");
	define ("LED1", "/sys/class/leds/led1/brightness");
	
	function chyba ($chyba) {
		// JS alert
		//echo("<script type=\"text/javascript\">");
		//echo("alert(\"".CHYBA."\\n\\n(".$chyba.")\");");
		//echo("</script>");
		
		echo("<div id=\"chyba\">");
		echo("<p style=\"color: #00008B;\">".CHYBA."</p><p style=\"color: #8B0000;\">(".$chyba.")</p>");
		echo("</div>");
	}
?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8" />
	<meta name="author" content="Martin Tábor" />
	<!-- responsivní design -->
	<meta name="viewport" content="width=device-width,maximum-scale=1" />
	<script type="text/javascript" src="kod.js"></script>
	<!-- načtení písma Inconsolata ze serverů Googlu (volitelné) -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Inconsolata:400,700&subset=latin,latin-ext" />
	<link rel="stylesheet" href="styl.css" />
	<!-- připnuté weby pro Windows 8.1 a IE11 -->
	<meta name="application-name" content="RPi2 on-board LEDs" />
	<meta name="msapplication-config" content="ieconfig.xml" />
	<!-- favikonka -->
	<link rel="shortcut icon" href="favicon.ico" />
	<title>RPi2 on-board LEDs</title>
</head>
<body>

	<?php	
		if ((file_exists(LED0)) && (file_exists(LED1))) {
			$led0 = fopen(LED0, "w+");
			$led1 = fopen(LED1, "w+");
		}
		else {
			chyba("Unable to initialize the sysfs.");
		}

		if ((isset($_GET["zapVyp"])) && (isset($_GET["dioda"]))) {
			$zapVyp = $_GET["zapVyp"];
			$dioda = $_GET["dioda"];
			
			if ($zapVyp == "1") {
				switch ($dioda) {
					case "0":
						if (isset($led0))
							fwrite($led0,"1");
					break;
					case "1":
						if (isset($led1))
							fwrite($led1,"1");
					break;
				}
			}
			elseif ($zapVyp == "0") {
				switch ($dioda) {
					case "0":
						if (isset($led0))
							fwrite($led0,"0");
					break;
					case "1":
						if (isset($led1))
							fwrite($led1,"0");
					break;
				}
			}
		}
	?>
	
	<div id="zahlavi">
		<table>
			<tr>
				<td style="padding-right: 35px;">
					<img src="grafika/rpi2led-logo.png" alt="logo" />
				</td>
				<td>
					<h1>RPi2 on-board LEDs</h1>
					<p>Web GUI for controlling the Raspberry Pi 2 (Model B) on-board LEDs.</p>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="obsah">	
		<table class="ovladace_tabulka">
			<tr>
				<th rowspan="2" style="background-color: #006400; width: 30%;">
					Green<br />(ACT, led0)
				</th>
				<td style="width: 50%">
					<button id="onLed0" title="Switch on the LED 0" class="ovladace_tlacitkoOn" onclick="zapVyp('zap','0')">On</button>
				</td>
				<td class="ovladace_stav" rowspan="2" style="width: 20%;">
					<?php
						if (isset($led0)) {
							rewind($led0);
							$stav = fread($led0, 1);
							switch ($stav) {
								case "0":
									echo(STAV_VYPNUTO);
								break;
								case "1":
									echo(STAV_ZAPNUTO);
								break;
							}
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
					<button id="offLed0" title="Switch off the LED 0" class="ovladace_tlacitkoOff" onclick="zapVyp('vyp','0')">Off</button>
				</td>
			</tr>
			<tr>
				<th rowspan="2" style="background-color: #8B0000;">
					Red<br />(PWR, led1)
				</th>
				<td>
					<button id="onLed1" title="Switch on the LED 1" class="ovladace_tlacitkoOn" onclick="zapVyp('zap','1')">On</button>
				</td>
				<td class="ovladace_stav" rowspan="2">
					<?php
						if (isset($led1)) {
							rewind($led1);
							$stav = fread($led1, 1);
							switch ($stav) {
								case "0":
									echo(STAV_VYPNUTO);
								break;
								case "1":
									echo(STAV_ZAPNUTO);
								break;
							}
						}
					?>
				</td>
			</tr>
			<tr>
				<td>
					<button id="offLed1" title="Switch off the LED 1" class="ovladace_tlacitkoOff" onclick="zapVyp('vyp','1')">Off</button>
				</td>
			</tr>
		</table>
	</div>
	
	<div id="zapati">
		<table style="width: 100%; vertical-align: middle; text-align: center;">
			<tr>
				<!-- informace o verzi a autorovi -->
				<td>
					<p><?php echo(VERZE_APLIKACE." |&nbsp;".DATUM_SESTAVENI); ?></p>
					<p>&copy;&nbsp;2015<?php
						if (date("Y") != 2015)
							echo("&ndash;".date("Y"));
					?> Martin&nbsp;Tábor</p>
					<!-- dobrovolný BTC příspěvek -->
					<a class="prispevek" target="_blank" href=""
						title="Please donate ANY amount in Bitcoin">
							<img src="grafika/donate.png" alt="přispět BTC" onclick="btcQR()" />
					</a>	
					<div id="prispevek">
						<p><strong>adresa peněženky</strong></p>
						<a class="prispevek" target="_blank" href=""
						title="Please donate ANY amount in Bitcoin (QR code for smartphone)">
							<img id="btc-qr" src="grafika/btc-qr.svg" alt="přispět BTC" />
						</a>
					</div>
				</td>
			</tr>
			<!-- licence
			<tr>
				<td style="padding-top: 50px;">
					<a href="http://www.gnu.org/licenses/agpl-3.0.html" title="Pod licencí GNU AGPLv3" target="_blank">
						<img src="grafika/licence.svg" alt="GNU AGPLv3" />
					</a>
				</td>
			</tr>
			-->
		</table>
	</div>
	
</body>
</html>
