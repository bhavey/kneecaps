#!/bin/bash
echo "Creates a backup of the database. Requires password of mysql user caps"
mysqldump -u caps -p --all-databases > "logs/backup_`date +%m_%d_%y`.sql"
