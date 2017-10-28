#!/bin/bash
# Backup the database before starting.
cd /var/www/granbyrotary.org/
dir=other
bkupdate=`date +%B-%d-%y`
filename="GR_BACKUP.$bkupdate.sql"

mysqldump --user=barton --no-data --password=7098653 granbyrotary 2>/dev/null > $dir/granbyrotary.schema
mysqldump --user=barton --add-drop-table --password=7098653 granbyrotary 2>/dev/null >$dir/$filename

gzip $dir/$filename

echo "bkupdb.sh for granbyrotary.org Done"
