#!/usr/bin/env python

import requests, unicodedata, sys, re, json

wrong_data='Die Zugangsdaten waren ungultig!'
right_data=list()
right_data.append('Alle nichtgespeicherten Bestellungen verfallen!')
right_data.append('nachsten Essenzeitraum wahlen')
ret=list()

if(sys.argv[1].isdigit() and sys.argv[2].isdigit()):
	username=sys.argv[1]
	password=sys.argv[2]
	post_data={'kdnrv':username, 'geheim':password}
	answer = requests.post(url='http://v1388.vcdns.de/termmsn/login.php',data=post_data, allow_redirects=False)
	response=unicodedata.normalize('NFKD', answer.text).encode('ascii','ignore')
	if(response.find(wrong_data)>0):
		print(0)
	else:
		if(response==""):
			#That's the redirectionpage
			ret.append(answer.cookies["PHPSESSID"])
			cookies=dict(PHPSESSID=ret[0])
			selectpage=requests.get(url="http://v1388.vcdns.de/termmsn/intern.php", cookies=cookies)
			response2=unicodedata.normalize('NFKD', selectpage.text).encode('ascii','ignore')
			if(response2.find(right_data[0])>0 and response2.find(right_data[1])>0):
				#now we could see the menu
				ret.append(re.search('Essenbestellung fur (.+?)</div>', response2).group(1))
				print(json.dumps(ret))
			else:
				print(0)
				
		else:
			print(0)
else:
	print(0)


