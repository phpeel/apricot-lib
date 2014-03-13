<?php
namespace Phpingguo\ApricotLib\Tests\Type\Enum;

use Phpingguo\ApricotLib\Enums\Variable;
use Phpingguo\ApricotLib\Enums\LibEnumName;

class TypeEnumTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ LibEnumName::CHARSET, 'ASCII', 'ASCII', null ],
            [ LibEnumName::CHARSET, 'UTF8', 'UTF-8', null ],
            [ LibEnumName::CHARSET, 'EUC_JP', 'EUC-JP', 'InvalidArgumentException' ],
            [ LibEnumName::HTTP_METHOD, 'GET', 'GET', null ],
            [ LibEnumName::HTTP_METHOD, 'POST', 'POST', null ],
            [ LibEnumName::HTTP_METHOD, 'HOGEHOGE', 'HOGEHOGE', 'InvalidArgumentException' ],
            [ LibEnumName::VARIABLE, 'INTEGER', Variable::INTEGER, null ],
            [ LibEnumName::VARIABLE, 'STRING', Variable::STRING, null ],
            [ LibEnumName::VARIABLE, 'VECTOR', 'VECTOR', 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($enum_name, $key, $value, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($value, (new $enum_name($value))->getValue());
        $this->assertSame($value, (string)(new $enum_name($value)));
        $this->assertSame($value, $enum_name::{$key}()->getValue());
        $this->assertSame($value, (string)$enum_name::{$key}());
        $this->assertSame($value, $enum_name::init($value)->getValue());
        $this->assertSame($value, (string)$enum_name::init($value));
    }
    
    public function providerInitMethod()
    {
        return [
            [ LibEnumName::VARIABLE, Variable::INTEGER(), Variable::INTEGER ],
            [ LibEnumName::VARIABLE, new Variable(Variable::INTEGER), Variable::INTEGER ],
        ];
    }
    
    /**
     * @dataProvider providerInitMethod
     */
    public function testInitMethod($enum_name, $instance, $expected)
    {
        $this->assertSame($expected, $enum_name::init($instance)->getValue());
        $this->assertSame($expected, (string)$enum_name::init($instance));
    }
}
