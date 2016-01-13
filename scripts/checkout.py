#!/usr/bin/env python
import requests, sys, urllib, re

cookie=dict(PHPSESSID=sys.argv[1])

try:
	subnumber=sys.argv[2]
except IndexError:
    	subnumber=0

page1=requests.get("http://v1388.vcdns.de/termmsn/warenkorb3.php", cookies=cookie)
if(page1.text.find("keine offenen Bestellungen")>0 or page1.text.find("sind nicht eingeloggt!")>0): #Nicht eingeloggt oder nichts im Warenkorb
	print(0)
else:
	content1=page1.text[page1.text.find("<table><tr><td>")+7:page1.text.find("<tr><td class='ende' colspan='3'>")]
	parts=re.split("<tr>", content1)
	parts.pop(0)
	url="http://v1388.vcdns.de/termmsn/warenkorb3.php?email=&eintragcopy=Bestellung%2Babschlie%25DFen"
	counter=0
	for part in parts:
		part=part[-62:-3]
		if(subnumber>0 and counter==0):
			part=part[:-1]+str(subnumber)
		part=urllib.quote(part, "!")
		url+="&hiddentable%5B%5D="+part
		counter=1	
	answer=requests.post(url, cookies=cookie)
	if(answer.text.find("<b>Bestellungen wurden")>0):
		print(1)
	else:
		print(0)
		
