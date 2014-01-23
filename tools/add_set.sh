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
resolutions=(5000 1920 1280 500 100)

rm -rf $setpath
mkdir -v $setpath

rm -f $sourcepath"/*-FFimage2-*"
for mov in `find $sourcepath -iname "*.mov" -type f`
do
  ffmpeg -i "$mov" -t 2 -r 0.5 $sourcepath"/$(basename $mov)-FFimage2-"%d.jpg
done

mogrify -background white -flatten -format jpg -quality 90 "$sourcepath/*.png"
mogrify -background white -flatten -format jpg -quality 90 "$sourcepath/*.tiff"
mogrify -background white -flatten -format jpg -quality 90 "$sourcepath/*.pdf"

for res in "${resolutions[@]}"
do
  pjpg=$setpath"/jpg-"$res
  pwww=$setpath"/www-"$res

	mkdir -v $pjpg
	mkdir -v $pwww

	if [[ -n "$prev_res" ]]
	  then   
	  sourcepath=$prev_path
	  detox -vr $sourcepath
  fi
  
	mogrify -background white -flatten -format jpg -quality 90 -path $pjpg -resize $resx$res\> "$sourcepath/*.jpg"
	prev_path=$pjpg

done
montage "$pjpg/*.jpg" $setpath"/contact_$res.jpg"

sudo chown -R _www $setspath
sudo chmod -R 777 $setspath

url="http://dev.compte-fil.dcfvg.com/tools/gen_print.php?set_name=$sourcename&refresh=ok"
echo "get $url"
wget -qO- $url &> /dev/null