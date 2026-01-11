#!/bin/sh

LOCALDIR=dist/
# HOST=mlrs.research.um.edu.mt
# REMOTEDIR=/var/www/resources/gabra/app/
HOST=10.249.1.100
REMOTEDIR=/var/www/public_html/resources/gabra/
FLAGS="--recursive --checksum --compress --verbose --exclude-from=deploy-exclude.txt --log-file=deploy.log"

set -e

if [ "$1" = "-wet" ]; then
  echo "Deploy Ġabra Web (For real)"
  rsync ${FLAGS} ${LOCALDIR} ${HOST}:${REMOTEDIR}
elif [ "$1" = "-delete" ]; then
  echo "Cleanup Ġabra Web (Potentially dangerous!)"
  rsync --delete ${FLAGS} ${LOCALDIR} ${HOST}:${REMOTEDIR}
else
  echo "Deploy Ġabra Web (Dry-run)"
  rsync --dry-run --delete ${FLAGS} ${LOCALDIR} ${HOST}:${REMOTEDIR}
  echo
  echo "### This was just a dry-run. ###"
  echo "To push for real, use the flag '-wet'"
  echo "To delete extra files from server, use the flag '-delete' (potentially dangerous)"
fi
