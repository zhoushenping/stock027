<?

class Extention
{

    static function getLoaded()
    {
        return get_loaded_extensions();
    }

    static function isLoaded($extentionName)
    {
        $temp = self::getLoaded();

        return in_array($extentionName, $temp);
    }
}
