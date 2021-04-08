#!/bin/bash

err() {
  echo "$(date +'%Y-%m-%dT%H:%M:%S.%3N') [ERROR] $*" >&2
}
info() {
  echo "$(date +'%Y-%m-%dT%H:%M:%S.%3N') [INFO]  $*"
}

CURRENT_DIR=$(pwd)
case $1 in
  main)
    FOLDER_ZIP="/data/joomla/importexport/simpleimport_zip/"
    ;;
  regular)
    FOLDER_ZIP="/data/joomla/importexport/simpleimport_zip_regular/"
    ;;
  *)
    err "Wrong option"
    exit 1
esac
if [ -z "${CURRENT_DIR}/import_products.php" ]; then
    err "php cli file is not found"
    exit 1
fi
FOLDER_MAIN="/data/joomla/importexport/simpleimport/"
LOCK_FILE="/data/joomla/importexport/.import_products_lock"
FILES=$(ls ${FOLDER_ZIP})
if [ ! -f ${LOCK_FILE} ]
  then
    info "$1: Creating lock file"
    touch ${LOCK_FILE}
    if [ ! -f ${LOCK_FILE} ]; then
      err "$1: Failed to create lock file"
      exit 1
    fi
    for entry in ${FILES}
    do
      BASE=$(basename $entry .zip) 
      info "$1: test archive - $entry"
      if unzip -t "${FOLDER_ZIP}${entry}" >/dev/null 2>&1
        then
          info "$1: unzip archive - $entry"
          unzip "${FOLDER_ZIP}${entry}" -d "${FOLDER_MAIN}${BASE}" >/dev/null 2>&1
          info "$1: import - $entry"
          su www-data -s /bin/bash -c "php ${CURRENT_DIR}/import_products.php --import-dir=\"${FOLDER_MAIN}${BASE}\" --save-checksums-count=5000 >/dev/null 2>&1"
          info "$1: remove - $entry"
          rm -r "${FOLDER_ZIP}${entry}"
          rm -r "${FOLDER_MAIN}${BASE}"
        else
          err "$1: ${FOLDER_ZIP}${entry} is corrupted"
          continue
        fi
    done
    info "$1: Removing lock file"
    rm ${LOCK_FILE}
  else
    err "$1: import already in progress"
fi
