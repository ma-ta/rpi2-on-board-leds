#!/bin/bash

#	Install bash script
#	ENCODING: UTF-8, LF
#
#	============================
#	PROJECT: RPi2 on-board LEDs:
#	============================
#
#	DESCRIPTION: web GUI for controlling the Raspberry Pi 2 (Model B) on-board LEDs
#	VERSION: 0.1.1 | 2015-06
#	LANGUAGE (ISO 639-3): eng, ces
#
#	AUTHOR: Martin Tábor
#	LICENCE: IndieCity REMIX EULA (http://store.raspberrypi.com/legal/eularemix)
# TIPs Bitcoin address: 18ftwpbU7ScjseadYBDjEr5xeSWVKUYfx3


if [[ $EUID -ne 0 ]]; then
  echo "You must run this script as root (type: sudo ./install.sh)." 2>&1
  exit 1
else
  # aktualizace databáze zdrojů aptitude
  echo UPDATING THE APP DATABASE...
  apt-get -qq update

  # instalace webového serveru lighttpd
  echo INSTALLING THE LIGHTTPD WEB SERVER...
  apt-get -qq install lighttpd -y

  # instalace interpretu PHP pro lighttpd
  echo INSTALLING THE PHP...
  apt-get install -qq php5-cgi -y
  lighttpd-enable-mod fastcgi
  lighttpd-enable-mod fastcgi-php
  /etc/init.d/lighttpd force-reload

  # zkopírování webového rozhraní RPi2 on-board LEDs do root adresáře web-serveru
  # a instalačního adresáře do domovské složky
  echo INSTALLING THE RPi2 ON-BOARD LEDs...
  cp led /var/www -R
  USER_HOME=$(eval echo ~${SUDO_USER})
  cp ../rpi2-onboard-leds ${USER_HOME} -R

  # nastavení on-board LEDs pro manuální ovládání
  echo SETTING UP YOUR SYSTEM FOR THE RPi2 ON-BOARD LEDs...
  chmod 777 /sys/class/leds/led0 -R
  chmod 777 /sys/class/leds/led1 -R
  echo none > /sys/class/leds/led0/trigger
  echo none > /sys/class/leds/led1/trigger

  # automatické nastavení on-board LEDs po každém spuštění systému
  cat /etc/rc.\local >> ${USER_HOME}/rc.\local-backupLED
  cat ./other/startup > /etc/rc.\local
  chmod +x /etc/rc.\local

  # hotovo
  echo
  echo COMPLETED.
  echo You can open the web interface on the address (URL) \"127.0.0.1/led\" from this PC.
  _IP=$(hostname -I | sed "s/ //g") || true
  if [ "$_IP" ]; then
    echo "...in another place use \"$_IP/led\"."
  fi
  echo You can read the file \help-LANGUAGE.txt \in your home folder.
  echo
  echo All Bitcoin tips on following address :-\):
  echo 18ftwpbU7ScjseadYBDjEr5xeSWVKUYfx3
  echo
  
  exit 0
fi
