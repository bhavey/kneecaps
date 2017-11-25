#!/bin/bash
MOUNTING_DIR="$HOME/Projects/kneecaps/raspi-mount"
REMOTE_ADDR="pi@192.168.1.199"
REMOTE_DIR="/var/www"
RUNNING_USER=`who am i | awk '{print $1}'`

if [[ $EUID -ne 0 ]]; then
    echo "Script must be run as root"
    exit 1
fi

# See if directory exists
if [ -d "/home/tagsby/Projects/kneecaps/raspi-mount" ]; then
    if grep -qs $MOUNTING_DIR /proc/mounts; then
        if umount $MOUNTING_DIR; then
            if sshfs -o allow_other $REMOTE_ADDR:$REMOTE_DIR $MOUNTING_DIR; then
                echo "Successful mount"
            else
                echo "Unsuccessful mount"
            fi
        else
            echo "Unsuccessful umount"
            exit
        fi
    else
        if sshfs -o allow_other $REMOTE_ADDR:$REMOTE_DIR $MOUNTING_DIR; then
            echo "Successful mount"
        else
            echo "Unsuccessful mount"
        fi
    fi
else
    mkdir $MOUNTING_DIR
    # Endpoint transport error can mess with folder check. Force unmount
    umount $MOUNTING_DIR
    chown -R $RUNNING_USER:$RUNNING_USER $MOUNTING_DIR
    if sshfs -o allow_other $REMOTE_ADDR:$REMOTE_DIR $MOUNTING_DIR; then
        echo "Successful mount"
    else
        echo "Unsuccessful mount"
    fi
fi
