#!/usr/bin/env python
import requests, sys, unicodedata, datetime, re, json
from time import time

cookie=dict(PHPSESSID=sys.argv[1])

try:
    week=sys.argv[2]
except IndexError:
    week=0

#get today, the monday before the needed week, convert to UNIX-Timestamp
today=time()
day=int(today)+(int(week)-1)*60*60*24*7
if(datetime.date.fromtimestamp(int(today)).weekday()==5 or datetime.date.fromtimestamp(int(today)).weekday()==6):
	monday=datetime.date.fromtimestamp(day) + datetime.timedelta(0-datetime.date.fromtimestamp(day).weekday()+7) #here we get the next monday as daytime object
else:
	monday=datetime.date.fromtimestamp(day) + datetime.timedelta(0-datetime.date.fromtimestamp(day).weekday()) #same but this week
monday=int(monday.strftime("%s"))
#give this as datum=1420904518&vor= to intern.php
data={'datum':monday, 'vor':""}
answer=requests.post("http://v1388.vcdns.de/termmsn/intern.php", cookies=cookie, data=data)
content=answer.text
if(content.find("Sie sind nicht eingeloggt!")>0):
	print(0)
else:
	content=content[content.find("size='2000' maxlength='2000'/")+30:-729] #get trash before and after away
	parts=re.split("<td id=\'tag\'\>", content) #split into days
	parts.pop(0) #delete first, useless part
	if(parts==list()):
		print(1)	#The week is empty
	else:
		menu=list() #Here we will save all the stuff in
		for part in parts:
			if(part.find("kundenschlussel")>0): #get these crap out
				part=part[:-130]+part[-24:]
				
			if(part.find("<")>2):			#here starts the new day
				weekday=part[:part.find("<")]	
				counter=0
			#print(part)
			#print("\n\n")
			date=part[part.find("name='speiseplan1' value='")+26:part.find("name='speiseplan1' value='")+36] #that's the date
			mealpart=part[part.find("bontext")+9:]
			meal=mealpart[:mealpart.find("<")]
			betrag=mealpart[mealpart.find("betrag")+8:mealpart.find("betrag")+12]
			if(betrag=="</td"): betrag=0
			
			mealpart=mealpart[mealpart.find("hiddenanzahlessen")+18:]
			mealpart=mealpart[mealpart.find("value='")+7:]
			mealnumber=mealpart[:12]
			mealpart=mealpart[50:]
			if(mealpart[:1]=="z"): anz=0
			else:
				try:
					var=int(mealpart[:1])
				except ValueError:
					var=0
				if(var!=0): anz=var
				else:
					mealpart=mealpart[mealpart.find("anzahlessen["):]
					mealpart=mealpart[mealpart.find("value='")+7:]
					if(mealpart.find("'")==0):
						anz=0
					else:
						anz=mealpart[:mealpart.find("'")]
			
			menu.append(list([date, weekday, counter, meal, betrag, mealnumber, anz]))
			counter+=1
		print(json.dumps(menu))











