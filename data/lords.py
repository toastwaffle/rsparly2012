#!/usr/bin/env python2

from xml.dom import minidom
import json

lords = {}

doc = minidom.parse('lords.xml')
for member in doc.getElementsByTagName('Member'):
    lord = {}
    lord['name'] = member.getElementsByTagName('DisplayAs')[0].childNodes[0].nodeValue
    for (key, value) in member.attributes.items():
        lord[key] = value
    listas = member.getElementsByTagName('ListAs')[0].childNodes[0].nodeValue.split(',')
    annunname = listas[1].strip() + ' ' + listas[0].strip()
    annunname = annunname.lower()
    lords[annunname] = lord

print json.dumps(lords, sort_keys=True)