#!/bin/bash
# Prepares Cloud9 workspace for use with PLTW CSP curriculum

echo "Initializing Cloud9 container for use in PLTW CSP."
echo "Installing PHP myAdmin."
phpmyadmin-ctl install


# This command used to enable tracepath for 2.1.3:
# sudo apt-get install iputils-tracepath


echo
echo "C9 username: "$C9_USER

# Change SQL password
echo "Because the system offers MySQL access to the world through PHPMyAdmin,"
echo "You will need to set a password for MySQL."
echo "In a secure location, write down a new secure password (8+ chars) for MySQL."

#Initialize pwd and pwd2 so that while executes at least once
pwd=""
pwd2="A"
while [ "$pwd" != "$pwd2" ]
do
  read -s  -p "Enter the new password for MySQL: " pwd
  echo
  read -s  -p "Confirm the new password for MySQL: " pwd2
  echo
  if [ $pwd != $pwd2 ];
  then
    echo "Passwords don't match. Try again."
  else  
    if [ ${#pwd} -le 7 ]; # length(pwd)<=7
    then
      echo "Password is too short. Try again."
      pwd="."$pwd2 # force while loop by concatenating period and pwd2
      # Troubleshooting:
      echo "debug:" $pwd $pwd2
    else
      echo
      echo "Changing MySQL password..."
      mysql -u $C9_USER -e "set password for '$C9_USER'@'%'=password('"$pwd"');"
      echo "MySQL password changed. Use this password for MySQL and for PHPMyAdmin."
      
      echo
      echo "Creating setep2 script to change PHPMyAdmin controluser password..."
      echo "#!/bin/bash" > setup2.sh
      echo "# Script created by initialize.sh for commands that require sudo." >> setup2.sh
      echo "">> setup2.sh
      
      echo "sed \"s/\$dbpass=.*/\$dbpass='"$pwd"';/\" /etc/phpmyadmin/config-db.php > /etc/phpmyadmin/config-db.php" >> setup2.sh
      chmod 711 setup2.sh
      
      # This is now included in PHP5 workspace template.
      # echo 'apt-get install python-dev' >> setup2.sh
      
      # This package is no longer located by apt-get
      # echo 'apt-get install libjpeg-dev' >> setup2.sh
      
      echo
      echo "****NOTE:****"
      echo "You still need to execute setup2 to update the PHPMyAdmin control-user password. At the $ prompt, type:"
      echo "     \$ sudo ./setup2.sh"
      
      #####
      # Create .login.php
      #####
      echo "<?php" > login.php
      echo '$host = "localhost";' >> login.php
      echo '$dbname ="artgallery";' >> login.php
      echo '$username = "'$C9_USER'";' >>login.php
      echo '$password = "'$pwd'";' >> login.php
      echo '?>' >> login.php
      
      #####
      # Create and populate SQL for Activity 2.2.2
      ####
      # This step not required because setup.sql creates the database now.
      # mysql -u $C9_USER -p$pwd -e "CREATE DATABASE artgallery"
      # Create and populate the database.
      mysql -u $C9_USER -p$pwd < setup.sql
      
      #####
      # Create database for Activity 2.2.3
      ####
      mysql -u $C9_USER -p$pwd -e "CREATE DATABASE shoes;"
      
      
    fi
  fi
done

# Remove git branch from shell prompt. 
sed -i -e "s/\$(__git_ps1 \" (%s)\")//" ../.bashrc
# Apply the new .bashrc to the current shell (This doesn't work from the script)
# source ../.bashrc 
