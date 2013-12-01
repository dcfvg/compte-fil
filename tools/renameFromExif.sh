# #!/bin/bash 
#set -x

# need target folder
if [[ $# -eq 0 ]] ; then
    echo 'need à folder !'
    exit 0
fi

folder=$1

exiftool '-filename<CreateDate' -d %Y-%m-%d_%H.%M.%S%%-c.%%le -r -ext jpg $folder

