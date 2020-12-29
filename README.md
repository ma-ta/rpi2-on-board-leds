# RPi2-on-board-LEDs
> Rozsvícení LED na desce Raspberry Pi

### Screenshot:

![Screenshot webové administrace](/screenshot-1.png)

### Instrukce:

```text

NÁPOVĚDA
KODOVANI: UTF-8, CRLF


PROJEKT: RPi2 on-board LEDs:
============================

POPIS: webové ovládací rozhraní pro on-board LEDs na Raspberry Pi 2 (Model B)
VERZE: 0.1.1 | 2015-06
JAZYK (ISO 639-3): eng, ces

AUTOR: Martin Tábor

OBSAH

(1) INSTALACE (odinstalace)
     1a) Použití skriptu
     1b) Ruční instalace
     2)  Obnovení výchozích funkcí LED
(2) POUŽITÍ APLIKACE
(3) TECHNICKÁ SPECIFIKACE
(4) DODATEK

----------------------------------------------------------------------------------

(1)  INSTALACE (odinstalace):
Pro instalaci a kompletní nastavení můžete použít přiložený skript "install.sh"
nebo provést všechny kroky manuálně a dle vlastních potřeb.

!! POZOR !!
Pakliže na Raspberry již máte nainstalován webový server (defaultně tomu tak není),
musíte z instalačního skriptu patřičné kroky odstranit, případně provést instalaci
ručně!

1a) Použití skriptu:
- Jestliže jste tak již neučinili, rozbalte všechny soubory ze staženého ZIP
archivu do jednoho adresáře.
- Nastavte souboru "install.sh" příznak spuštění (buď prostřednictvím grafického
správce souborů nebo zadáním příkazu do terminálu:
	sudo chmod +x install.sh
- Spusťte instalační skript jako správce či opět použitím sudo:
	sudo ./install.sh
- Počkejte na dokončení instalace.

(Skript provede zálohu souboru /etc/rc.local do souboru rc.local-backupLED
 ve vaší domovské složce.)

1b) Ruční instalace:
- Jednotlivé kroky instalačního skriptu jsou okomentovány. V zásadě je třeba
zprovoznit libovolný webový server (Lighttpd, Apache) a interpret PHP.
- Složku "led" je možno umístit do kořenového adresáře web-serveru.
- Webová aplikace pak přistupuje k on-board LED skrze linuxový systém virtuálních
souborů sysfs. Je tedy nutno nastavit práva zápisu do příslušných souborů
a zároveň oběma LED odebrat výchozí úlohu:

	# vše níže uvedené musíte provést jako SPRÁVCE (např. použitím sudo):

	# nastavení přístupových práv souborům v sysfs
	chmod 777 /sys/class/leds/led0 -R
	chmod 777 /sys/class/leds/led1 -R

	# odebrání výchozích úloh LED
	echo none > /sys/class/leds/led0/trigger
	echo none > /sys/class/leds/led1/trigger

	(POZN. výše uvedeným způsobem je možné spárovat libovolnou LED s některou
	z událostí v souboru trigger. Jejich seznam vypíšete do terminálu příkazem
	cat /sys/class/leds/ledX/trigger, kde X nahrazuje konkrétní složku v sysfs.)

- Výše uvedené čtyři příkazy je potřeba znovu vykonat po každém nabootování OS
(tedy nastavit automatické vykonání uvedených příkazů po spuštění systému).
V Raspbianu toho lze dosáhnout např. zkopírováním příkazů do souboru /etc/rc.local
před poslední řádek "exit 0". Soubor /etc/rc.local pak musí mít nastaven příznak
spuštění (sudo chmod +x /etc/rc.local), jinak se po startu nevykoná
(jak říká komentář přímo v souboru /etc/rc.local).

2) Obnovení výchozích funkcí LED:
- Zelená  LED (led0): ve výchozím stavu indikuje práci s paměťovou kartou.
  (echo mmc0 > /sys/class/leds/led0/trigger)
- Červená LED (led1): ve výchozím stavu indikuje napájení.
  (echo input > /sys/class/leds/led1/trigger)

Pro obnovení funkcí LED po dalším restartu Raspberry Pi 2 jednoduše vraťte
soubor /etc/rc.local do původního stavu. OS již sám nastaví LED defaultně.
(Pakliže jste použili instalační skript, záloha souboru se nachází v
~/rc.local-backupLED (ve vašem domácím adresáři). V opačném případě ručně
odstraňte příkazy pod komentářem "# RPi2 on-board LEDs:".)


(2)  POUŽITÍ APLIKACE:
Webové ovládací rozhraní RPi2 on-board LEDs je responzivní a jde bez problémů
používat na smartphonech a tabletech. Víceméně jediným omezením je nutnost
podpory JavaScriptu ze strany prohlížeče (výhledově se počítá s nasazením AJAXu).

Defaultně je Ovládání LED dostupné na webové adrese
	"IP_VASEHO_RPi2"/led,
kde "IP_VASEHO_RPi2" představuje adresu Raspberry Pi 2 v síti (lze ji zjistit např.
zadáním příkazu ifconfig do terminálu Raspberry). Přistupujete-li k webovému
rozhraní přímo z Raspberry, můžete zadat adresu:
	localhost/led
či
	127.0.0.1/led

(Stavy "On/Off" v uživatelském rozhraní jsou načítány přímo z Raspberry Pi
a měly by odrážet skutečný stav LED.)

Pakliže potřebujete ovládat LED z prohlížeče, který JavaScript nepodporuje,
nouzově můžete zadat do webové adresy potřebné parametry ručně:
	
	# zapnutí zelené LED (led0)
	http://.../led/?zapVyp=1&dioda=0
	# vypnutí zelené LED (led0)
	http://.../led/?zapVyp=0&dioda=0
	# zapnutí červené LED (led1)
	http://.../led/?zapVyp=1&dioda=1
	# vypnutí červené LED (led1)
	http://.../led/?zapVyp=0&dioda=1


(3)  TECHNICKÁ SPECIFIKACE:
Webová aplikace RPi2 on-board LEDs využívá pro přístup k LED
interpret PHP, který čte a zapisuje do systému virtuálních
souborů sysfs. Z něj lze číst informace o stavu hardware,
ale také jej ovládat.

Stav LED na desce Raspberry Pi 2 je prezentován souborem
/sys/class/leds/ledX/brightness, kde X je 0 pro zelenou
a 1 pro červenou diodu. Do souboru lze zapsat 256 hodnot, avšak
HW či ovladač v Raspbianu (build 2015-05-05) v tomto případě
zvládá jen dva stavy – smysl tedy mají pouze hodnoty "0"
(LED nesvítí) a "1" (LED svítí). "Internet pak hojně zmiňuje",
že led0 lze vyexportovat na GPIO pin 16, avšak na Raspberry
Pi 2 Model B (V1.1) se informaci nepodařilo verifikovat.

Aplikace si samozřejmě říká o bohaté rozšíření – na mysl
přichází AJAX, redesign a obohacení GUI, přidání dalších
ovládacích možností (časovač, blikač, "telegraf", propojení
se systémovými funkcemi, API atd.).

V konečném důsledku pak může webové rozhraní s dostatečným
množstvím nastavitelných parametrů vyústit v "univerzální"
ovladač rozhraní GPIO, které již může řidit prakticky cokoliv.

GPIO lze v sysfs ovládat skrze /sys/class/gpio, avšak
konkrétní pin (x) si je třeba vyexportovat např. příkazem:
	echo X > /sys/class/gpio/export
Poté jsou dané parametry pinu reprezentovány soubory
v nově vytvořené složce /sys/class/gpio/gpioX.


(4)  DODATEK:
Projekt je šířen pod svobodnou licencí a jeho smyslem je především
ukázat možnosti vzdáleného ovládání Raspberry Pi 2 Model B a případně poskytnout
základ (odrazový můstek) pro složitější aplikace.

Analogickým způsobem – čtením a zápisem do souborového systému sysfs – lze totiž
přistupovat i k rozhraní GPIO, pro něž je na internetu již dostatečné množství
návodů a příkladů typu "Hello, World!". Ty jsou často právě v podobě rozsvícení
externí LED připojené k rozhraní GPIO. Program RPi2 on-board LEDs lze však
vyzkoušet ihned po zakoupení Raspberry, a to bez potřeby dalšího hardware jako
kabely, rezistor, LED, breadboard atd…

PŘÍJEMNOU TECHNOFILNÍ ZÁBAVU! :-)


V jednom májovém týdnu stvořil:

© 2015 Martin Tábor

```
