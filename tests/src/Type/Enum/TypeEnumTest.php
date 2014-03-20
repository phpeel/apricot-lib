<?php
namespace Phpingguo\ApricotLib\Tests\Type\Enum;

use Phpingguo\ApricotLib\Enums\Charset;
use Phpingguo\ApricotLib\Enums\Variable;
use Phpingguo\ApricotLib\LibSupervisor;

class TypeEnumTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ LibSupervisor::ENUM_CHARSET, 'ASCII', 'ASCII', null ],
            [ LibSupervisor::ENUM_CHARSET, 'UTF8', 'UTF-8', null ],
            [ LibSupervisor::ENUM_CHARSET, 'EUC_JP', 'EUC-JP', 'InvalidArgumentException' ],
            [ LibSupervisor::ENUM_VARIABLE, 'INTEGER', Variable::INTEGER, null ],
            [ LibSupervisor::ENUM_VARIABLE, 'STRING', Variable::STRING, null ],
            [ LibSupervisor::ENUM_VARIABLE, 'VECTOR', 'VECTOR', 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($enum_name, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $full_name = LibSupervisor::getEnumFullName($enum_name);
        
        $this->assertSame($value, (new $full_name($value))->getValue());
        $this->assertSame($value, (string)(new $full_name($value)));
        $this->assertSame($value, $full_name::{$key}()->getValue());
        $this->assertSame($value, (string)$full_name::{$key}());
        $this->assertSame($value, $full_name::init($value)->getValue());
        $this->assertSame($value, (string)$full_name::init($value));
    }
    
    public function providerInitMethod()
    {
        return [
            [ LibSupervisor::ENUM_VARIABLE, Variable::INTEGER(), Variable::INTEGER ],
            [ LibSupervisor::ENUM_VARIABLE, new Variable(Variable::INTEGER), Variable::INTEGER ],
        ];
    }
    
    /**
     * @dataProvider providerInitMethod
     */
    public function testInitMethod($enum_name, $instance, $expected)
    {
        $full_name = LibSupervisor::getEnumFullName($enum_name);
        
        $this->assertSame($expected, $full_name::init($instance)->getValue());
        $this->assertSame($expected, (string)$full_name::init($instance));
    }
    
    public function providerGetName()
    {
        return [
            [ LibSupervisor::ENUM_CHARSET, Charset::ASCII, 'ASCII' ],
            [ LibSupervisor::ENUM_VARIABLE, Variable::INTEGER, 'INTEGER' ],
        ];
    }

    /**
     * @dataProvider providerGetName
     */
    public function testGetName($enum_name, $value, $expected)
    {
        $full_name = LibSupervisor::getEnumFullName($enum_name);
        
        $this->assertSame($expected, (new $full_name($value))->getName());
    }
}
