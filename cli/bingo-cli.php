#!/usr/bin/php
<?php

/**
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 * This is the Bingo command line interface
 *
 */

require dirname(__DIR__) . '/packages/autoload.php';

set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::cliExceptionHandler');

$cmdl = new Core\CommandLine;

$history = [];

readline_completion_function('Core\CommandLine::tabComplete');

while (1) {
    $cmd = readline($cmdl->getCliText()['prefix'] . ' ' . $cmdl->getCliText()['title']);
    
    readline_add_history($cmd);
    
    $history[] = $cmd;
    
    switch ($cmd) {
            
        /**
         *
         * Get all the values defined in the Config file
         * @see App\Config
         *
         */ 
            
        case $cmdl->getAllCommands()[0]:
            foreach ($cmdl->getConfigConstants() as $attr => $option) {
                echo "\t {$attr}: {$option} \n";
            }
            break;
            
        /**
         *
         * Download a package from Composer 
         * @see composer.json
         * @see https://getcomposer.org
         *
         */    
            
        case $cmdl->getAllCommands()[1]:
            echo "{$cmdl->getCliText()['prefix']} Please enter a Composer package name]: ";
            $package = trim(fgets(STDIN));
            if ($cmdl->interactWithProcess($cmdl->getAllCommands()[1], $package)) {
                echo "\n{$cmdl->getCliText()['prefix']}]: The package {$package} was installed! \n";
            } else {
                echo "\n{$cmdl->getCliText()['prefix']}]: The package {$package} was not installed! \n";
            }
            break;
            
        /**
         *
         * Update dependencies defined in the composer.json file; upload new ones if declared
         * @see composer.json
         * @see https://getcomposer.org
         *
         */    
            
        case $cmdl->getAllCommands()[2]:
            if ($cmdl->interactWithProcess($cmdl->getAllCommands()[2])) {
                echo "\n{$cmdl->getCliText()['prefix']}]: Dependencies were installed!\n";
            } else {
                echo "\n{$cmdl->getCliText()['prefix']}]: Dependencies were not installed!\n";
            }
            break;
        
        /**
         *
         * Update Composer by triggering a self-update 
         * @see composer.json
         * @see https://getcomposer.org
         *
         */    
            
        case $cmdl->getAllCommands()[3]:
            if ($cmdl->interactWithProcess($cmdl->getAllCommands()[3])) {
                echo "\n{$cmdl->getCliText()['prefix']}]: Composer was successfully updated!\n";
            } else {
                echo "\n{$cmdl->getCliText()['prefix']}]: Composer was not updated!\n";
            }
            break;
        
        case $cmdl->getAllCommands()[4]:
            $counter = 0;
            foreach ($history as $event) {
                $counter++;
                echo "\t Command {$counter}: {$event} \n";
            }
            break;
            
        case $cmdl->getAllCommands()[5]:
            foreach ($cmdl->bundleCommands() as $cmd => $desc) {
                echo "\t {$cmd}: {$desc} \n";
            }
            break;  
            
        case $cmdl->getAllCommands()[7]:
            echo "\t dump autoloader \n";
            if ($cmdl->interactWithProcess($cmdl->getAllCommands()[8])) {
                echo "\n{$cmdl->getCliText()['prefix']}]: Class was successfully added!\n";
            } else {
                echo "\n{$cmdl->getCliText()['prefix']}]: Class was not added\n";
            }
            break;
            
        case $cmdl->getAllCommands()[6]:
            break 2;
            
        default:
            echo "\t Command not supported \n";
            break;
    }
}
echo "\t Thanks for using the shell.\n";