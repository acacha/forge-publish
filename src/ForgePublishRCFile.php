<?php

namespace Acacha\ForgePublish;

use Illuminate\Support\Facades\File;

/**
 * Class ForgePublishRCFile.
 *
 * @package Acacha\Llum
 */
class ForgePublishRCFile {

    /**
     * Relative path to llumrc file.
     *
     * @var string
     */
    const RELATIVE_FILE_PATH = "/.forgepublishrc";

    /**
     * Get real path (form user home)
     *
     * @return string
     */
    public static function path()
    {
        return getenv("HOME") . self::RELATIVE_FILE_PATH;
    }

    /**
     * Get real path (form user home)
     *
     * @return string
     */
    public static function exists()
    {
        return File::exists(self::path());
    }
}