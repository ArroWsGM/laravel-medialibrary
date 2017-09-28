<?php

namespace Spatie\MediaLibrary\HasMedia\Interfaces;

interface HasMediaConversions extends HasMedia
{
    /**
     * Register the conversions that should be performed.
     *
     * @param Spatie\MediaLibrary\Media $media
     *
     * @return array
     */
    public function registerMediaConversions(Spatie\MediaLibrary\Media $media);
}
