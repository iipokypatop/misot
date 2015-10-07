<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.10.2015
 * Time: 14:39
 */

namespace Aot\Orphography\Language\Driver\Pspell;


class LanguageBuilder
{
    const CUSTOM_DICTEINARY_EXTENTION = 'pws';

    public function build($language_name)
    {
        assert(is_string($language_name));
        assert(mb_strlen($language_name) !== 0);

        if (!$this->areRequiredFilesExist($language_name)) {
            throw new \RuntimeException("Language " . $language_name . " not found");
        }
        $pspell_config = pspell_config_create($language_name);
        pspell_config_data_dir($pspell_config, $this->getDir() . DIRECTORY_SEPARATOR);
        pspell_config_dict_dir($pspell_config, $this->getDir() . DIRECTORY_SEPARATOR);
        pspell_config_personal($pspell_config,
            $this->getDir() . DIRECTORY_SEPARATOR . $language_name . '.' . static::CUSTOM_DICTEINARY_EXTENTION);
        return $pspell_config;
    }

    protected function __construct()
    {

    }

    public static function create()
    {
        return new static();
    }

    protected function getDir()
    {
        return __DIR__ . "/Languages";
    }

    protected function getSystemFileNames()
    {
        return ["cp1251.cmap", "cp1251.cset", "standard.kbd"];
    }

    protected function getLanguageFileExtensions()
    {
        return ["dat", "multi", "rws", static::CUSTOM_DICTEINARY_EXTENTION];
    }

    /**
     * @param string $language_name
     * @return bool
     */
    public function areRequiredFilesExist($language_name)
    {
        assert(is_string($language_name));

        foreach ($this->getSystemFileNames() as $system_file_name) {

            if (!$this->isRequiredFileExist($this->getDir() . DIRECTORY_SEPARATOR . $system_file_name)) {
                return false;
            }

        }

        foreach ($this->getLanguageFileExtensions() as $language_file_extension) {
            if (!$this->isRequiredFileExist($this->getDir() . DIRECTORY_SEPARATOR . $language_name . '.' . $language_file_extension)) {
                return false;
            }
        }
        return true;
    }

    protected function isRequiredFileExist($file)
    {
        if (!file_exists($file)) {
            return false;
        }

        if (!is_readable($file)) {
            return false;
        }
        return true;
    }


}