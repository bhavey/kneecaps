I'm using a LAMP stack for this: Linux, Apache, MySQL, and PHP.

This is all to be placed in /var/www

There is a file mysql_auth.php which is not included in the directory. The file mysql_auth_example.php shows the contents of this file. You can copy this file and then change the username and password to reflect what's actually being used.

When setting up the server there is also a wpa_supplicant.conf file needed to configure a static IP. An example of this file is available in tools/wpa_supplicant.conf which ommits the SSID and password of my router.
