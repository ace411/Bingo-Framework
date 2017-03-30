<?php

/**
 *
 * Command Line Interface tool for the Bingo Framework
 *
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

class CommandLine
{
    private $assetManager;
    /**
     *
     * Commands supported by the Bingo CLI
     *
     * @access private
     * @var array $commands
     *
     */

    private $commands = [
        'settings',
        'install',
        'update',
        'self update',
        'history',
        'help',
        'terminate',
        'add',
        'bundle'
    ];

    /**
     *
     * Descriptions to be bundled with the commands supported by the Bingo CLI
     *
     * @access private
     * @var array $descriptions
     *
     */

    private $descriptions = [
        'View your configuration settings',
        'Install a new package via Composer',
        'Install the packages in the composer.json file',
        'Update your version of Composer',
        'View a history of the Bingo shell commands you have entered',
        'View all the available Shell commands',
        'Close the Bingo Shell',
        'Update the Composer autoloader after including a new Controller or Model',
        'Bundle all the assets in the assets directory'
    ];

    /**
     *
     * Collection of pervasive phrases
     *
     * @access private
     * @var array $cliText
     *
     */

    private $cliText = [
        'prefix' => '[ Bingo Shell:',
        'title'  => 'Enter command ] > ',
        'thanks' => 'Thanks for using the shell!'
    ];

    /**
     *
     * Constructor for the cli; checks if the bingo-cli script is running from the Command Line
     *
     */

    public function __construct()
    {
        if (php_sapi_name() !== 'cli') {
            throw new \Exception('Cannot open the CLI in a browser');
        }
        $this->assetManager = new \Core\Assets;
    }

    /**
     *
     * Processes bound to commands
     *
     * @return array
     *
     */

    private function getProcesses()
    {
        return [
            'install'     => 'composer install',
            'update'      => 'composer update',
            'self update' => 'composer self-update',
            'add'         => 'composer dumpautoloader -o',
            'launch'      => 'start'
        ];
    }

    /**
     *
     * Pipe process defaults for process interaction
     *
     * @return array
     *
     */

    private function processDefaults()
    {
        return [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => [
                'pipe',
                fwrite(STDOUT, "\t Bingo is working...")
            ]
        ];
    }

    /**
     *
     * Pipe process values with a file log stream
     *
     * @param string $logName Name of the file in which the results of the process will be stored
     *
     * @return array
     *
     */

    private function processLogValues($logName)
    {
        return [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['file', $this->filePath('/logs/cli-successes/' . time() . "{$logName}.txt"), 'w+']
        ];
    }

    /**
     *
     * Get absolute path relative to Bingo directory
     *
     * @param string $path Relative path to file or folder to be used
     *
     * @return string
     *
     */

    public function filePath($path)
    {
        return str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__) . $path);
    }

    /**
     *
     * Bundle commands and descriptions in an array
     *
     * @return array
     *
     */

    public function bundleCommands()
    {
        return array_combine($this->commands, $this->descriptions);
    }

    /**
     *
     * Interact with other processes
     *
     * @param string $process Command to trigger action
     * @param null string $arg Argument entered by user
     *
     * @return bool
     *
     */

    public function interactWithProcess($process, $arg = null)
    {
        $validateProcess = function ($proc, $procArr) {
            if (array_key_exists($proc, $procArr)) {
                return $procArr[$proc];
            }
        };

        $validProcess = $validateProcess($process, $this->getProcesses());
        $processOptions = $this->processDefaults();

        if (!is_null($arg)) {
            switch ($process) {
                case $this->getProcesses()['install']:
                    $validProcess = $this->getProcesses()['install'] . " {$arg}";
                    $processOptions = $this->processLogValues('_composer_install');
                    break;
            }
        }

        switch ($process) {
            case $this->getProcesses()['update']:
                $processOptions = $this->processLogValues('_composer_update');
                break;

            case $this->getProcesses()['self update']:
                $processOptions = $this->processLogValues('_composer_self_update');
                break;
        }

        $process = proc_open($validProcess, $processOptions, $pipes);

        if (is_resource($process)) {
            echo stream_get_contents($pipes[1]);
            fclose($pipes[1]);

            $returnValue = proc_close($process);

            if ($returnValue === 0) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function bundleAll()
    {
        return array_combine($this->assetManager->getFileTypes(), [
            $this->assetManager->cssFileBundler(),
            $this->assetManager->lessFileBundler(),
            $this->assetManager->scssFileBundler(),
            $this->assetManager->jsFileBundler(),
            $this->assetManager->pngFileModifier(),
            $this->assetManager->jpegFileModifier(),
            $this->assetManager->jpegFileModifier()
        ]);
    }

    public function getAssetManager()
    {
        return $this->assetManager;
    }

    /**
     *
     * Get the Configuration options set in the Config class
     *
     * @see App\Config.php
     *
     * @return array
     *
     */

    public function getConfigConstants()
    {
        return \App\Config::getConstants();
    }

    /**
     *
     * Get the contents of a file
     *
     * @param string $file The relative path of the file to be used
     *
     * @return string
     *
     */

    public function openFile($file)
    {
        $file = $this->filePath($file);
        if (file_exists($file)) {
            return file_get_contents($file);
        } else {
            return false;
        }
    }

    /**
     *
     * Allow tab auto-completion of commands like in UNIX systems
     *
     * @param string $partial Partial command
     *
     * @return array $this->commands
     */

    public static function tabComplete($partial)
    {
        $cmdl = new CommandLine;
        return $cmdl->commands;
    }

    /**
     *
     * Get all the commands supported by the CLI
     *
     * @return array $this->commands
     *
     */

    public function getAllCommands()
    {
        return $this->commands;
    }

    /**
     *
     * Get the commonly used CLI prefixes
     *
     * @return array $this->cliText
     *
     */

    public function getCliText()
    {
        return $this->cliText;
    }
}
