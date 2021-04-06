#!/bin/bash

# FUNCTIONS
err() {
  echo "$(date +'%Y-%m-%dT%H:%M:%S.%3N') [ERROR] $*" >&2
}
info() {
  echo "$(date +'%Y-%m-%dT%H:%M:%S.%3N') [INFO]  $*"
}

# This should be the script directory itself
CURRENT_DIR=$(pwd)
case $1 in
  default)
    FOLDER_MAIN="/data/joomla/importexport/importorders_bizoutmax"
    ;;
  *)
    FOLDER_MAIN=$1
    ;;
esac
FILES=$(ls $FOLDER_MAIN)
if [ ! -f "$CURRENT_DIR/import_orders.php" ]; then
  err "php cli file is not found"
  exit 1
fi
for entry in $FILES; do
  info "Starting import: $entry"
  if php "$CURRENT_DIR/import_orders.php" --import-file="$FOLDER_MAIN/$entry" --loglevel=debug; then
    mv "${FOLDER_MAIN}/$entry" "${FOLDER_MAIN}_old/imported_$entry"
    info "Import done: $entry"
  else
    err "Import failed: $entry"
  fi
done