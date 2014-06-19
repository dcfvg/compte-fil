presetspath="/Users/benoit/Scripts/custom/compte-fil/assets/pre-sets"

for dir in `find $presetspath -iname "*_*" -type d`
do
	echo "scan "$dir
	target=$(dirname $presetspath)/sets/$(basename $dir)
	if [ ! -d "$target" ]; then
	  # Control will enter here if $DIRECTORY doesn't exist.
		echo "create $target" 
	  bash add_set.sh $dir
	fi
done