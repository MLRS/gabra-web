#!/bin/sh

LOCALDIR=dist/
# HOST=mlrs.research.um.edu.mt
# REMOTEDIR=/var/www/resources/gabra/app/
HOST=10.249.1.100
REMOTEDIR=/var/www/public_html/resources/gabra/
FLAGS="--recursive --checksum --compress --verbose --exclude-from=deploy-exclude.txt --log-file=deploy.log"

set -e

echo "Build Ġabra Web"
npm run build

echo "Deploy Ġabra Web"
if [ "$1" = "-wet" ]; then
  echo "(For real)"
  rsync ${FLAGS} ${LOCALDIR} ${HOST}:${REMOTEDIR}
else
  echo "(Dry-run)"
  rsync --dry-run --delete ${FLAGS} ${LOCALDIR} ${HOST}:${REMOTEDIR}
  echo
  echo "### This was just a dry-run. To push for real, use the flag '-wet' ###"
fi
