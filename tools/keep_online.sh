#!/bin/bash

ATTEMPTS=0
while true; do
	wget -q --tries=10 --timeout=20 --spider http://google.com
	if [[ $? -ne 0 ]]; then
		ATTEMPTS=$((ATTEMPTS+1))
		if [[ "$ATTEMPTS" -eq "3" ]]; then
			echo "Rebooting"
			reboot
		fi
	fi
	sleep 5
done
