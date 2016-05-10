#!/bin/bash
echo "Initializing Cloud9 container for use in PLTW CSP."
echo "Installing PHP myAdmin."
phpmyadmin-ctl install
echo "Asking MySQL to list databases:"
mysql "show databases;"
