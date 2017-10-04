<?php

namespace Spatie\MediaLibrary;

use League\Glide\ServerFactory;
use Spatie\MediaLibrary\Exceptions\SourceFileDoesNotExist;

class GlideImage
{
    /**
     * @var string The path to the input image.
     */
    protected $sourceFile;

    /**
     * @var array The modification the need to be made on the image.
     *            Take a look at Glide's image API to see which parameters are possible.
     *            http://glide.thephpleague.com/1.0/api/quick-reference/
     */
    protected $modificationParameters = [];

    /**
     * @param $sourceFile
     *
     * @return mixed
     */
    public static function create($sourceFile)
    {
        return (new static())->setSourceFile($sourceFile);
    }

    /**
     * @param $sourceFile
     *
     * @return $this
     * @throws SourceFileDoesNotExist
     */
    public function setSourceFile($sourceFile)
    {
        if (! file_exists($sourceFile)) {
            throw new SourceFileDoesNotExist();
        }

        $this->sourceFile = $sourceFile;

        return $this;
    }

    /**
     * @param array $modificationParameters
     *
     * @return $this
     */
    public function modify($modificationParameters)
    {
        $this->modificationParameters = $modificationParameters;

        return $this;
    }

    /**
     * @param $outputFile
     *
     * @return mixed
     */
    public function save($outputFile)
    {
        $sourceFileName = pathinfo($this->sourceFile, PATHINFO_BASENAME);

        $cacheDir = sys_get_temp_dir();

        $glideServerParameters = [
            'source' => dirname($this->sourceFile),
            'cache' => $cacheDir,
            'driver' => config('laravel-medialibrary.driver', 'gd'),
        ];

        if (isset($this->modificationParameters['mark'])) {
            $watermarkPathInfo = pathinfo($this->modificationParameters['mark']);
            $glideServerParameters['watermarks'] = $watermarkPathInfo['dirname'];
            $this->modificationParameters['mark'] = $watermarkPathInfo['basename'];
        }

        $glideServer = ServerFactory::create($glideServerParameters);

        $conversionResult = $cacheDir.'/'.$glideServer->makeImage($sourceFileName, $this->modificationParameters);

        rename($conversionResult, $outputFile);

        return $outputFile;
    }
}