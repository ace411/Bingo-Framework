<?php

/**
 * Asset manager for Bingo Framework
 * Handles asset management using Assetic
 *
 * @see https://github.com/kriswallsmith/assetic
 * @package Bingo Framework
 * @author Lochemem Bruno Michael
 *
 */

namespace Core;

use \App\Config;
use \Assetic\AssetWriter;
use \Assetic\AssetManager;
use \Assetic\FilterManager;
use \Assetic\Asset\FileAsset;
use \Assetic\Asset\GlobAsset;
use \Assetic\Filter\LessphpFilter;
use \Assetic\Filter\JSqueezeFilter;
use \Assetic\Filter\SeparatorFilter;
use \Assetic\Filter\ScssphpFilter;
use \Assetic\Filter\CssMinFilter;
use \Assetic\Filter\JpegoptimFilter;
use \Assetic\Filter\OptiPngFilter;
use \Assetic\Asset\AssetCache;
use \Assetic\Cache\FilesystemCache;
use \Assetic\Asset\AssetCollection;

class Assets
{
    /**
     * Assetic Filters
     *
     * @access protected
     * @var array $filters
     *
     */

    protected $filters = [];

    /**
     * Asset file destinations
     *
     * @access protected
     * @var array $destinations
     *
     */

    protected $destinations = [];

    /**
     * Asset file types
     *
     * @access protected
     * @var array $fileTypes
     *
     */

    protected $fileTypes = [
        'css',
        'less',
        'scss',
        'js',
        'png',
        'jpeg',
        'jpg'
    ];

    /**
     * Create a complete file path
     *
     * @param string $file The file whose complete path you intend to construct
     * @return string $path The complete file path
     *
     */

    protected function createPath($file)
	{
		return str_replace(DIRECTORY_SEPARATOR, '/', dirname(__DIR__)) . '/' . $file;
	}

    /**
     * Cache files internally using the Assetic internal cache
     *
     * @param string $destination The destination of the cached file
     * @param object $files The file object for the caching action
     * @return object $cache Details of the cached file
     *
     */

    protected function internalCache($destination, $files)
    {
        $cache = new AssetCache($files, new FilesystemCache($destination));
        return $cache;
    }

    /**
     * Generate a unique file name for a file
     *
     * @param string $unique Unique name for the file
     * @return string $unique MD5 hashed value of the file name
     *
     */

    protected function setFileName($unique)
    {
        return substr(hash('md5', "_bingo_{$unique}"), 0, 16);
    }

    /**
     * Set a destination for files of a certain supported file type
     *
     * @param string $fileType The supported file type
     * @param string $destination
     */

    public function setDestination($fileType, $destination)
    {
        $verifyTypes = function ($type, $typesArray) {
            if (!in_array($type, $typesArray)) {
                return false;
            }
            return $type;
        };
        $destination = $this->createPath($destination);
        if (!is_dir($destination)) {
            throw new \Exception("Invalid destination: {$destination}");
        }
        $this->destinations[$verifyTypes($fileType, $this->fileTypes)] = $destination;
    }

    /**
     * Assets class constructor
     *
     */

    public function __construct()
    {
        $this->destinations = array_combine($this->fileTypes, [
            $this->createPath('public/css'),
            $this->createPath('public/css'),
            $this->createPath('public/css'),
            $this->createPath('public/js'),
            $this->createPath('public/png'),
            $this->createPath('public/img'),
            $this->createPath('public/img'),
        ]);
        $this->filters = array_combine($this->fileTypes, [
            [
                new CssMinFilter()
            ],
            [
                new LessphpFilter(),
                new CssMinFilter()
            ],
            [
                new ScssphpFilter(),
                new CssMinFilter()
            ],
            [
                new JSqueezeFilter(),
                new SeparatorFilter()
            ],
            [
                new OptiPngFilter()
            ],
            [
                new JpegoptimFilter()
            ],
            [
                new JpegoptimFilter()
            ]
        ]);
    }

    /**
     * Get the destination for a particular file type
     *
     * @param string $fileType
     * @return string $destination
     */

    public function getDestination($fileType)
    {
        return $this->destinations[$fileType];
    }

    /**
     * Get all the file destinations
     *
     * @return array $destinations
     *
     */

    public function getDestinations()
    {
        return $this->destinations;
    }

    /**
     * Get all the file filters
     *
     * @return array $filters The file filters provided by Assetic
     */

    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * Get a particular Assetic file filter
     *
     * @param string $fileType
     * @return array $filters
     */

    public function getFilter($fileType)
    {
        return $this->filters[$fileType];
    }

    /**
     * Get all the file types
     *
     * @return array $fileTypes
     */

    public function getFileTypes()
    {
        return $this->fileTypes;
    }

    /**
     * Match a file extension to a unique file name
     *
     * @param string $fileName Unique file name
     * @param string $ext File extension
     * @return string $completeFileName Generated file name
     */

    protected function matchFileExtension($fileName, $ext)
    {
        switch ($ext) {
            case 'less': //less files
                $completeFileName = $fileName . '.css';
                break;

            case 'scss': //scss files
                $completeFileName = $fileName . '.css';
                break;

            case 'css': //css files
                $completeFileName = $fileName . '.css';
                break;

            case 'js': //js files
                $completeFileName = $fileName . '.js';
                break;

            case 'png': //png files
                $completeFileName = $fileName . '.png';
                break;

            case 'jpg':
            case 'jpeg': //jpg files
                $completeFileName = $fileName . '.jpg';
                break;
        }
        return $completeFileName;
    }

    /**
     * Boilerplate for applying the Assetic file filters
     *
     * @param string $file The path of the asset to be modified
     * @param string $destination
     * @param object $assetData The asset object
     * @param array $filters A set of filter objects
     * @return string $path Path to modified file
     */

    protected function fileFilter($file, $destination, $assetData, $filters)
    {
        $am = new AssetManager();
        $writer = new AssetWriter($destination);
        $collection = new AssetCollection([$assetData], $filters);

        $ext = explode('.', $file);
        $ext = $ext[1]; //get the file extension
        $fileName = $this->setFileName($file);
        $completeFileName = $this->matchFileExtension($fileName, $ext); //get the complete filename
        $collection->setTargetPath($completeFileName);
        $am->set($fileName, $collection);
        $writer->writeManagerAssets($am);

        if (Config::ASSET_CACHE === true) {
            $cache = $this->internalCache(
                $this->createPath(Config::CACHE_DIR . '/' . $ext),
                $assetData
            );
        }

        if (empty($cache->dump())) {
            return false;
        }

        return $this->getDestination($ext) . '/' . $collection->getTargetPath();
    }

    /**
     * Boilerplate for applying the Assetic file filters
     * Function returns multiple FileAssets
     *
     * @param string $ext File extension
     * @param string $destination
     * @param array $filters A set of filter objects
     * @return array $path Paths to modified files
     */

    protected function splitBundles($ext, $destination, $filters)
    {
        $am = new AssetManager;
        $writer = new AssetWriter($destination);
        $files = scandir($this->createPath("assets/{$ext}")); //scan a directory for files
        $files = array_slice($files, 2);  //remove the '.' and '..' values from the array

        $cache = [];
        $content = [];
        $scripts = [];
        $fileNames = [];
        $completeFileNames = [];

        for ($x = 0; $x < count($files); $x++) {
            $fileNames[$x] = $this->setFileName($files[$x]);
            $completeFileNames[$x] = $this->matchFileExtension($fileNames[$x], $ext);
            $scripts[$x] = new AssetCollection([
                new FileAsset($this->createPath("assets/{$ext}/{$files[$x]}"))
            ], $filters);
            $scripts[$x]->setTargetPath($completeFileNames[$x]);
            $am->set($fileNames[$x], $scripts[$x]);
            $writer->writeManagerAssets($am);

            if (Config::ASSET_CACHE === true) {
                $cache[$x] = $this->internalCache(
                    $this->createPath(Config::CACHE_DIR . '/' . $ext),
                    new FileAsset($this->createPath("assets/{$ext}/{$files[$x]}"))
                );
            }

            if (empty($cache[$x]->dump())) {
                return false;
            }

            if (!file_exists($this->createPath("public/{$ext}/" . $scripts[$x]->getTargetPath()))) {
                return false;
            }
            $content[$x] = $this->createPath("public/{$ext}/" . $scripts[$x]->getTargetPath());
        }
        return $content;
    }

    /**
     * Split multiple Less files into single single css files
     *
     * @return array $files Modified Less files
     */

    public function splitLessBundles()
    {
        return $this->splitBundles(
            'less',
            $this->createPath('public/css'),
            $this->filters['less']
        );
    }

    /**
     * Split multiple SCSS files into single single css files
     *
     * @return array $files Modified SCSS files
     */

    public function splitScssBundles()
    {
        return $this->splitBundles(
            'scss',
            $this->createPath('public/css'),
            $this->filters['scss']
        );
    }

    /**
     * Split multiple CSS files into single single css files
     *
     * @return array $files Modified CSS files
     */

    public function splitCssBundles()
    {
        return $this->splitBundles(
            'css',
            $this->createPath('public/css'),
            $this->filters['css']
        );
    }

    /**
     * Split multiple JS files into single single css files
     *
     * @return array $files Modified JS files
     */

    public function splitJsBundles()
    {
        return $this->splitBundles(
            'js',
            $this->createPath('public/js'),
            $this->filters['js']
        );
    }

    /**
     * Modify single JS file
     *
     * @param string $jsFile JS file to be modified
     * @return string $file Modified JS file
     */

    public function jsFileFilter($jsFile)
    {
        return $this->fileFilter(
            $jsFile,
            $this->createPath('public/js'),
            new FileAsset($this->createPath('assets/js/' . $jsFile)),
            $this->filters['js']
        );
    }

    /**
     * Modify single Less file
     *
     * @param string $lessFile LESS file to be modified
     * @return string $file Modified Less file
     */

    public function lessFileFilter($lessFile)
    {
        return $this->fileFilter(
            $lessFile,
            $this->createPath('public/css'),
            new FileAsset($this->createPath('assets/less/' . $lessFile)),
            $this->filters['less']
        );
    }

    /**
     * Modify single SCSS file
     *
     * @param string $scssFile SCSS file to be modified
     * @return string $file Modified SCSS file
     */

    public function scssFileFilter($scssFile)
    {
        return $this->fileFilter(
            $scssFile,
            $this->createPath('public/css'),
            new FileAsset($this->createPath('assets/scss/' . $scssFile)),
            $this->filters['scss']
        );
    }

    /**
     * Modify single CSS file
     *
     * @param string $cssFile CSS file to be modified
     * @return string $file Modified CSS file
     */

    public function cssFileFilter($cssFile)
    {
        return $this->fileFilter(
            $cssFile,
            $this->createPath('public/css'),
            new FileAsset($this->createPath('assets/css/' . $scssFile)),
            $this->filters['css']
        );
    }

    /**
     * Bundle all JS files into one large JS file
     *
     * @return string $file Modified JS file
     */

    public function jsFileBundler()
    {
        return $this->fileFilter(
            'scripts.js',
            $this->createPath('public/js'),
            new GlobAsset($this->createPath('assets/js/*')),
            $this->filters['js']
        );
    }

    /**
     * Bundle all Less files into one large CSS file
     *
     * @return string $file Modified Less file
     */

    public function lessFileBundler()
    {
        return $this->fileFilter(
            'scripts.less',
            $this->createPath('public/css'),
            new GlobAsset($this->createPath('assets/less/*')),
            $this->filters['less']
        );
    }

    /**
     * Bundle all SCSS files into one large CSS file
     *
     * @return string $file Modified SCSS file
     */

    public function scssFileBundler()
    {
        return $this->fileFilter(
            'scripts.scss',
            $this->createPath('public/css'),
            new GlobAsset($this->createPath('assets/scss/*')),
            $this->filters['scss']
        );
    }

    /**
     * Bundle all CSS files into one large CSS file
     *
     * @return string $file Modified CSS file
     */

    public function cssFileBundler()
    {
        return $this->fileFilter(
            'scripts.css',
            $this->createPath('public/css'),
            new GlobAsset($this->createPath('assets/css/*')),
            $this->filters['css']
        );
    }

    /**
     * Modify all jpg files
     *
     * @return string $file Modified jpg image(s)
     */

    public function jpegFileModifier()
    {
        return $this->fileFilter(
            'images.jpg',
            $this->createPath('public/img'),
            new GlobAsset($this->createPath('assets/img/*.jpg')),
            $this->filters['jpg']
        );
    }

    /**
     * Modify all png files
     *
     * @return string $file Modified png image(s)
     */

    public function pngFileModifier()
    {
        return $this->fileFilter(
            'images.png',
            $this->createPath('public/img'),
            new GlobAsset($this->createPath('assets/img/*.png')),
            $this->filters['png']
        );
    }

    /**
     * Retrieve all the files of a particular type
     *
     * @param string $fileType
     * @return array $files Available files
     */

    public function getFiles($fileType)
    {
        if (!in_array($fileType, $this->fileTypes)) {
            throw new \Exception("File type {$fileType} not supported!");
        }
        return array_slice(scandir($this->destinations[$fileType]), 2);
    }

    /**
     * Returns the address of a file from its allocated directory
     * Works well with splitBundles and fileFilters
     *
     * @param string $fileName The name of the file
     * @return string $filePath 
     */

    public function getFile($fileName)
    {
        $fileExt = explode('.', $fileName);
        $fileExt = strtolower($fileExt[1]);

        if (!in_array($fileExt, $this->fileTypes)) {
            throw new \Exception("File of type {$fileExt} is not supported!");
        }

        return $this->setFileName($fileName) . '.' . $fileExt;
    }
}
