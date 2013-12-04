#!/bin/bash 

# add new set to digital linen tester

#set -x
clear
# need target folder
if [[ $# -eq 0 ]] ; then
    echo 'warning ! no source folder'
    exit 0
fi

sourcepath=$1
sourcename=$(basename $sourcepath)

setspath="/Users/benoit/Scripts/custom/compte-fil/assets/sets"
setpath=$setspath"/"$sourcename
timestamp=$(date +"%s")
resolutions=(1920 1280 500 100)

rm -rf $setpath
mkdir -v $setpath

pjpg=$setpath"/jpg-5000"
pwww=$setpath"/www-5000"

mkdir -v $pjpg
mkdir -v $pwww

for mov in `find $sourcepath -iname "*.mov" -type f`
do
  ffmpeg -i "$mov" -t 2 -r 0.5 $pjpg"/$(basename $mov)-"%d.jpg
done

for res in "${resolutions[@]}"
do
  
  pjpg=$setpath"/jpg-"$res
  pwww=$setpath"/www-"$res
  
  cp -r $setpath"/jpg-5000/" $pjpg"/"
  mkdir -v $pwww
done

montage "$pjpg/*.jpg" $setpath"/contact_$res.jpg"

sudo chown -R _www $setspath
sudo chmod -R 777 $setspath