/*
	JavaScript
	ENCODING: UTF-8, CRLF

	============================
	PROJECT: RPi2 on-board LEDs:
	============================

	DESCRIPTION: web GUI for controlling the Raspberry Pi 2 (Model B) on-board LEDs
	VERSION: 0.1.1 | 2015-06
	LANGUAGE (ISO 639-3): eng, ces

	AUTHOR: Martin Tábor
	LICENCE: IndieCity REMIX EULA (http://store.raspberrypi.com/legal/eularemix)
	TIPs Bitcoin address: 18ftwpbU7ScjseadYBDjEr5xeSWVKUYfx3
*/

function zapVyp(stav, dioda) {
	
	if (stav == "vyp") {
		window.location.href = "?zapVyp=0&dioda="+dioda;
	}
	else if (stav == "zap") {
		window.location.href = "?zapVyp=1&dioda="+dioda;
	}
	
}

function btcQR() {
	
	var prispevek = document.getElementById("prispevek");
	prispevek.style.display = "inline";
	
}
