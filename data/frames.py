#!/usr/bin/env python2

import pycurl
import cStringIO
import csv
from datetime import datetime
import json

lords = json.load(open('lords.json'))

messages = []

frames = open('frames.txt', 'r')
lastMsgText = ''
for line in frames:
    buffer = cStringIO.StringIO()
    date = datetime.strptime(line[6:19], '%H%M-%d%m%Y').strftime('%Y-%m-%d %H:%M')
    url = "http://ec2-54-247-39-234.eu-west-1.compute.amazonaws.com/data/thursday/annunciator/" + line.strip()
    c = pycurl.Curl()
    c.setopt(c.URL, url)
    c.setopt(c.WRITEFUNCTION, buffer.write)
    c.perform()
    c.close()
    frame = {'datetime': date, 'lines': []}
    for row in csv.reader(buffer.getvalue().splitlines()):
        if row[0] == '>Bell':
            frame['bell'] = row[2]
        elif row[0] == '>Background Screen Color':
            frame['bg-colour'] = (row[2], row[3], row[4])
        elif row[0] == '>Text Item':
            frame['lines'].append({})
            currentLine = int(row[2]) - 1
            frame['lines'][currentLine] = {'style': {}}
        elif row[0] == '>Justification':
            frame['lines'][currentLine]['justification'] = row[2]
        elif row[0] == '>Foreground Color':
            frame['lines'][currentLine]['fg-colour'] = (row[2], row[3], row[4])
        elif row[0] == '>Background Color':
            frame['lines'][currentLine]['bg-colour'] = (row[2], row[3], row[4])
        elif row[0] == '>Flash':
            frame['lines'][currentLine]['flash'] = row[2]
        elif row[0] == '>Font Name':
            frame['lines'][currentLine]['font'] = row[2]
        elif row[0] == '>Font Height':
            frame['lines'][currentLine]['font-size'] = row[2]
        elif row[0] == '>Font Style':
            frame['lines'][currentLine]['style'][row[2]] = row[3]
        elif row[0] == '>Font Effects':
            frame['lines'][currentLine]['style'][row[2]] = row[3]
        elif row[0] == '>Text':
            frame['lines'][currentLine]['text'] = row[2]
            try:
                frame['person'] = lords[row[2].strip().lower()]
            except KeyError:
                if row[2].endswith(':'):
                    frame['activity'] = row[2][:-1]
                else:
                    if currentLine == 2:
                        frame['topic'] = row[2]
                    else:
                        frame['activity'] = row[2]
        elif row[0] == '>Box Top Left':
            frame['lines'][currentLine]['top-left'] = (row[2], row[3])
        elif row[0] == '>Box Bottom Right':
            frame['lines'][currentLine]['bottom-right'] = (row[2], row[3])
    messages.append(frame)
    buffer.close()
    #raw_input()

print json.dumps(messages, sort_keys=False, indent=2)
