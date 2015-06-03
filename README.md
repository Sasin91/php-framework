# Weclome to the Readme.


If you're running Ubuntu or any other Linux distro for that matter,
 make sure to enable php5 & rewrite.
the easiest way of doing so is installing the lamp-server, like so:

#### Ubuntu
```
sudo apt-get install lamp-server^ php5-xdebug
sudo a2enmod rewrite
```

When that's done, you'll need to open, configure and copy a couple of files in ~/Path_to_framework/Server Configuration/

*Those files depend on your current setup, if you followed above stated, then its those:*
```
Apache2/Vhosts/apache2-vhost.conf -> /etc/apache2/sites-available/
```

Done setting everything up? good.
Now it's time to configure ~/Path_to_framework/app/Config/Config.yml


Additionally on Ubuntu, if you want to serve PHP from your /home/$USER/public_html folder,
```
sudo a2enmod userdir
```
edit /etc/apache2/mods-enabled/php5.conf,
Comment out (append #) to
```
 <IfModule mod_userdir.c>
```

finally, restart Apache2.

```
sudo service apache2 restart
```