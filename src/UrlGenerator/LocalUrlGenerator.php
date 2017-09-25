<?php

namespace Spatie\MediaLibrary\UrlGenerator;

use Illuminate\Support\Facades\Storage;

class LocalUrlGenerator extends BaseUrlGenerator implements UrlGenerator
{
    /**
     * Get the url for the profile of a media item.
     *
     * @return string
     */
    public function getUrl() {
        return Storage::disk( $this->media->disk )->url( $this->getPathRelativeToRoot() );
    }

    /**
     * Get the path for the profile of a media item.
     *
     * @return string
     */
    public function getPath()
    {
        return $this->getStoragePath().'/'.$this->getPathRelativeToRoot();
    }

    /**
     * Get the directory where all files of the media item are stored.
     *
     * @return \Spatie\String\Str
     */
    protected function getBaseMediaDirectory()
    {
        $baseDirectory = string($this->getStoragePath())->replace(public_path(), '');

        return $baseDirectory;
    }

    /**
     * Get the path where the whole medialibrary is stored.
     *
     * @return string
     */
    protected function getStoragePath()
    {
        $diskRootPath = $this->config->get('filesystems.disks.'.$this->media->disk.'.root');

        return realpath($diskRootPath);
    }

    /**
     * Get the path to the requested file relative to the root of the media directory.
     *
     * @return string
     */
    public function getPathRelativeToRoot() {
        if ( is_null( $this->conversion ) ) {
            return $this->pathGenerator->getPath( $this->media ) . $this->media->file_name;
        }

        $converted = $this->pathGenerator->getPathForConversions( $this->media ) .
                     $this->conversion->getName() . '.' . $this->conversion->getResultExtension( $this->media->extension );

        return Storage::disk( $this->media->disk )->exists( $converted )
            ? $converted
            : $this->pathGenerator->getPath( $this->media ) . $this->media->file_name;
    }
}
