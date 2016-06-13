# csp9
Materials for PLTW Computer Science Principles for Cloud9. Scroll to read all of this document. 
# Welcome!

As instructed in Part III of the Lesson 2.1 Supplement, please type three commands at the Bash prompt below to set up your workspace. After each command, press enter and wait for the prompt to appear again.

 * `chmod 711 initialize.sh`
 * `./initialize.sh`. Create and record a password for this workspace's MySQL server as directed.
 * `sudo ./setup2.sh`

The worksapce should serve to the web at `https://<workspacename>-<c9username>.c9users.io`. If you get the error, "There does not seem to be an application running here" in Activity 2.2.2, enter the following command at a Bash prompt:

   sudo service apache2 restart

The `(master)` in the Bash prompt is git-related; The `initialize.sh` script will change your `.bashrc` file so that the Bash prompt of any Bash shells you open in the future will not show the git branch. 

v1.00 
