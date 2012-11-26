#!/bin/bash

while true
do
    for (( i=0; i < 33; i++ ))
    do
        php post_annunciator.php $i &
        sleep 20;
    done
done
