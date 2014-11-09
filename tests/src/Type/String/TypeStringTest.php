<?php
namespace Phpeel\ApricotLib\Tests\Type\String;

use Phpeel\ApricotLib\Type\String\String;

class TypeStringTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ 1, false, false, null, 'InvalidArgumentException' ],
            [ 100, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX - 1, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX + 1, false, false, null, 'InvalidArgumentException' ],
            [ PHP_INT_MAX + 10, false, false, null, 'InvalidArgumentException' ],
            [ -1, false, false, null, 'InvalidArgumentException' ],
            [ -100, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX + 1, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX - 1, false, false, null, 'InvalidArgumentException' ],
            [ ~PHP_INT_MAX - 10, false, false, null, 'InvalidArgumentException' ],
            [ 0.1, false, false, null, 'InvalidArgumentException' ],
            [ 0.9, false, false, null, 'InvalidArgumentException' ],
            [ 1.0, false, false, null, 'InvalidArgumentException' ],
            [ 1.1, false, false, null, 'InvalidArgumentException' ],
            [ -0.9, false, false, null, 'InvalidArgumentException' ],
            [ -1.0, false, false, null, 'InvalidArgumentException' ],
            [ -1.1, false, false, null, 'InvalidArgumentException' ],
            [ '1', false, false, null, 'InvalidArgumentException' ],
            [ '100', false, false, null, 'InvalidArgumentException' ],
            [ '-1', false, false, null, 'InvalidArgumentException' ],
            [ '-100', false, false, null, 'InvalidArgumentException' ],
            [ 'a', true, true, null, null ],
            [ 'Z', true, true, null, null ],
            [ 'ABC', true, true, null, null ],
            [ 'XYZ', true, true, null, null ],
            [ 'ひらがな', true,true,  null, null ],
            [ '漢字', true, true, null, null ],
            [ 'ｶﾀｶﾅ', true, true, null, null ],
            [ 'カタカナ', true, true, null, null ],
            [ '%88%c0%95%94%20%8d%d8%81X%0d%0a', true, true, null, null ],
            [ '%bd%ef%ca%fd%20%c3%d2%b3%a8%ce%a4', true, true, null, 'InvalidArgumentException' ],
            [ 0, false, false, null, 'InvalidArgumentException' ],
            [ 0.0, false, false, null, 'InvalidArgumentException' ],
            [ '0', false, false, null, 'InvalidArgumentException' ],
            [ '0.0', false, false, null, 'InvalidArgumentException' ],
            [ null, false, false, null, 'InvalidArgumentException' ],
            [ '', true, false, null, null ],
            [ false, false, false, null, 'InvalidArgumentException' ],
            [ [], false, false, null, 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider provider
     */
    public function test($value, $result, $strict_result, $expected, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($result, (new String())->isValue($value));
        $this->assertSame($result, String::getInstance()->isValue($value));
        $this->assertSame($strict_result, (new String())->isValid($value));
        $this->assertSame($strict_result, String::getInstance()->isValid($value));
        $this->assertSame($expected ?: (is_array($value) ? '' : (new String())->getValue($value)), (new String())->getValue($value));
    }
    
    public function testDefaultValue()
    {
        $this->assertSame('', (new String())->getDefaultValue());
    }
}
