#!/bin/bash

for strLine in `547-location-expect.exp "$1" | sed 's/\s */,/g'`
  do
    echo "${strLine}" > 547-location.txt
done
