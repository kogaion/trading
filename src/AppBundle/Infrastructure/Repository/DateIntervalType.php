<?php
/**
 * Created by PhpStorm.
 * User: Kogaion
 * Date: 2/16/2018
 * Time: 7:20 PM
 */

namespace AppBundle\Infrastructure\Repository;


use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class DateIntervalType extends Type
{
    public function getName()
    {
        return 'type_date_interval';
    }
    
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getVarcharTypeDeclarationSQL($fieldDeclaration);
    }
    
    public function getDefaultLength(AbstractPlatform $platform)
    {
        return $platform->getVarcharDefaultLength();
    }
    
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $format = explode(',', $value);
        $di = new \DateInterval($format[2]);
        $di->invert = $format[1] == '-' ? 1 : 0;
        $di->days = strlen($format[0]) ? (int) $format[0] : false;
        
        return $di;
    }
    
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value instanceof \DateInterval) {
            $format = $value->format(',%R,P%yY%mM%dDT%hH%iM%sS');
            if ('(unknown)' != ($days = $value->format('%a'))) {
                $format = "{$days}{$format}";
            }
            return $format;
        }
        return ',+,P0D';
    }
}