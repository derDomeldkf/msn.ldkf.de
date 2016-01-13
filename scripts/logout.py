#!/usr/bin/env python
import requests, unicodedata, sys

right_data=list()
right_data.append('Alle nichtgespeicherten Bestellungen verfallen!')
right_data.append('nachsten Essenzeitraum wahlen')
sessid=sys.argv[1]
cookie=dict(PHPSESSID=sessid)
empty=requests.get("http://v1388.vcdns.de/termmsn/logout.php", cookies=cookie,allow_redirects=False)
selectpage=requests.get(url="http://v1388.vcdns.de/termmsn/intern.php", cookies=cookie)
response2=unicodedata.normalize('NFKD', selectpage.text).encode('ascii','ignore')
if(response2.find(right_data[0])>0 and response2.find(right_data[1])>0):
	print(0)
else:
	print(1)

