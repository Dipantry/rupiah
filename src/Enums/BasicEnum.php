<?php

namespace Dipantry\Rupiah\Enums;

use ReflectionClass;
use ReflectionException;

abstract class BasicEnum
{
    private static array|null $constCacheArray = null;

    /* @throws ReflectionException */
    private static function getConstants()
    {
        if (self::$constCacheArray == null) {
            self::$constCacheArray = [];
        }

        $calledClass = get_called_class();
        if (!array_key_exists($calledClass, self::$constCacheArray)) {
            $reflect = new ReflectionClass($calledClass);
            self::$constCacheArray[$calledClass] = $reflect->getConstants();
        }

        return self::$constCacheArray[$calledClass];
    }

    public static function isValidValue($value, $strict = true): bool
    {
        try {
            $values = array_values(self::getConstants());

            return in_array($value, $values, $strict);
        } catch (ReflectionException) {
            return false;
        }
    }
}
