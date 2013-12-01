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
resolutions=(5000 1920 500 100 10)

#

mkdir -v $setpath

for res in "${resolutions[@]}"
do
  pjpg=$setpath"/_jpg-"$res
  pwww=$setpath"/_www-"$res
  
	mkdir -v $pjpg
	mkdir -v $pwww
	
	sudo chown -R benoit:_www $pwww
  chmod -R u=rwX,g=rX,o= $pwww

	if [[ -n "$prev_res" ]]
	  then  sourcepath=$setpath"/_jpg-"$prev_res""
  fi
  
	mogrify -format jpg -quality 90 -path $setpath"/_jpg-"$res -resize $resx$res\> "$sourcepath/*.*"
	prev_res=$res

done
montage "$pjpg/*.jpg" $setpath"/contact_$res.jpg"