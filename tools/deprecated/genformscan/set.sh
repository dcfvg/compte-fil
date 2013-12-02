#!/bin/bash 

# add new set to digital linen tester

#set -x
clear

sourcepath=/Users/benoit/Scripts/custom/compte-fil/tools/deprecated/genformscan/assets/source/
sourcename=$(basename $sourcepath)

setspath="/Users/benoit/Scripts/custom/compte-fil/tools/deprecated/genformscan/assets/result/"
setpath=$setspath"/"$sourcename
resolutions=(1000 500 300 200 150 100 80 50 30)

rm -rf $setpath
mkdir -v $setpath

for res in "${resolutions[@]}"
do
  pjpg=$setpath"/png-"$res

	mkdir -v $pjpg

	if [[ -n "$prev_res" ]]
	  then   
	  sourcepath=$prev_path
  fi
  
	mogrify -format png -path $pjpg -resize $resx$res\> "$sourcepath/*.*"
	prev_path=$pjpg

done