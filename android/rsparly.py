import android
import urllib2
import json
from threading import Thread
import time
import logging

logging.basicConfig(filename='rsparlylog.txt')

droid = android.Android()

oldperson = ''
oldtopic = ''
oldactivity = ''

def annunSpeak():
    global oldperson
    global oldtopic
    global oldactivity
    while True:
        try:
            uh = urllib2.urlopen("http://rsparly.toastwaffle.com/get_annunciator.php")
            jsontext = uh.read()
            frame = json.loads(jsontext)
            if frame is not None:
                if frame.has_key('person'):
                    speakstring = ''
                    if frame['person']['name']!=oldperson:
                        oldperson = frame['person']['name']
                        speakstring += "%s is now speaking. " % frame['person']['name']
                    if frame['activity']!=oldactivity:
                        oldactivity = frame['activity']
                        speakstring += "It is now a %s. " % frame['activity'].lower()
                    if frame['topic']!=oldtopic:
                        oldtopic = frame['topic']
                        speakstring += "The topic is now %s." % frame['topic'].lower()
                    droid.ttsSpeak(speakstring)
                elif frame['activity']=='HOUSEUP':
                    if frame['activity']!=oldactivity:
                        oldactivity = 'HOUSEUP'
                        droid.ttsSpeak('The session is now finished.')
                time.sleep(5)
        except Exception, e:
            logging.debug(e)

t = Thread(target=annunSpeak)
t.daemon = True
t.start()

raw_input('Press return to exit')

