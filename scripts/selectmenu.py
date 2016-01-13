#!/usr/bin/env python
import requests, sys, json

cookie=dict(PHPSESSID=sys.argv[1])
error=0
try:
	date=sys.argv[2]
	mealnumber=sys.argv[3]
	anz=int(sys.argv[4])
except IndexError:
    	error=1


data={'vor':""}
answer=requests.post("http://v1388.vcdns.de/termmsn/intern.php", cookies=cookie, data=data)
content=requests.get("http://v1388.vcdns.de/termmsn/warenkorb3.php", cookies=cookie)

if(answer.text.find("Sie sind nicht eingeloggt!")>0 or error==1):
	print(0)
else:
	url="http://v1388.vcdns.de/termmsn/intern.php?save=&hiddenanzahlessen%5B0%5D="+str(mealnumber)+"%7C%7C5--W&hiddendatum%5B0%5D="+date+"&anzahlessen%5B0%5D="+str(anz)
	answer2=requests.post(url, cookies=cookie)
	answer3=requests.get("http://v1388.vcdns.de/termmsn/warenkorb3.php", cookies=cookie)
	if(answer3.text==content.text):
		print(0)
	else:
		print(1)
