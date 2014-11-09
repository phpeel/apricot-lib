<?php
namespace Phpeel\ApricotLib\Type\Enum;

/**
 * 列挙型クラスの値からクラスを取得する機能を持つクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class EnumClassGenerator
{
    /**
     * 列挙型クラスの値からクラスを取得し、それらの情報を返します。
     * 
     * @param String|Enum $enum_name 列挙型の名前
     * @param String $value          列挙型の値
     * 
     * @return Array(Enum, Object, String) 取得したクラスのインスタンスとその名前、列挙型のインスタンスの配列
     */
    public static function done($enum_name, $value)
    {
        $obj_enum   = $enum_name::init($value);
        $class_name = $obj_enum->getValue();
        $instance   = new $class_name();
        
        return [ $obj_enum, $instance, $class_name ];
    }
}
