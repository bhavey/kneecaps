#!/bin/bash
mkdir ~/Projects/kneecaps/raspi-mount
sudo sshfs -o allow_other pi@192.168.1.199:/var/www/html ~/Projects/kneecaps/raspi-mount/
