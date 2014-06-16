presetspath="/Users/benoit/Scripts/custom/compte-fil/assets/pre-sets"

for dir in `find $presetspath -iname "*_*" -type d`
do
  bash add_set.sh $dir
done