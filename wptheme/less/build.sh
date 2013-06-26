#!/bin/bash
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

cd $DIR;
recess --compress *.less > tmp.css;
cat header.txt tmp.css > ../style.css;
rm tmp.css;
