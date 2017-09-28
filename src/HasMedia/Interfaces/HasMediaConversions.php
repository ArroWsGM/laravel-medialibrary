<?php

namespace Spatie\MediaLibrary\HasMedia\Interfaces;

interface HasMediaConversions extends HasMedia
{
    /**
     * Register the conversions that should be performed.
     *
     * @param $media
     *
     * @return array
     */
    public function registerMediaConversions($media);
}
