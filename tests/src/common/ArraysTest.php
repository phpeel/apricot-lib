<?php
namespace Phpingguo\ApricotLib\Tests\Src\Common;

use Phpingguo\ApricotLib\Common\Arrays;

class ArraysTest extends \PHPUnit_Framework_TestCase
{
    public function providerIsMethod()
    {
        return [
            [ [], [ true, false ] ],
            [ [ 'hoge' ], [ false, true ] ],
            [ [ 'a' => 'foo' ], [ false, true ] ],
            [ [ 0 => 'bar' ], [ false, true ] ],
            [ 0, [ false, false ] ],
            [ 1000, [ false, false ] ],
            [ 0.0, [ false, false ] ],
            [ 0.2, [ false, false ] ],
            [ '0', [ false, false ] ],
            [ '1000', [ false, false ] ],
            [ '0.0', [ false, false ] ],
            [ '0.2', [ false, false ] ],
            [ null, [ false, false ] ],
            [ '', [ false, false ] ],
            [ 'hogehoge', [ false, false ] ],
            [ true, [ false, false ] ],
            [ false, [ false, false ] ],
        ];
    }
    
    /**
     * @dataProvider providerIsMethod
     */
    public function testIsMethod($list, $expects)
    {
        $this->assertSame($expects[0], Arrays::isEmpty($list));
        $this->assertSame($expects[1], Arrays::isValid($list));
    }
    
    public function providerCheckSize()
    {
        return [
            [ [ 'a', 'b', 'c' ], 0, 3, true, null ],
            [ [ 'a', 'b', 'c' ], 2, 3, true, null ],
            [ [ 'a', 'b', 'c' ], 0, 2, false, null ],
            [ [ 'a', 'b', 'c' ], 4, 5, false, null ],
            [ [ 'a', 'b', 'c' ], null, 3, true, null ],
            [ [ 'a', 'b', 'c' ], null, 2, false, null ],
            [ [ 'a', 'b', 'c' ], 1.1, 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, 2.3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], -1, 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], -5, -1, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], true, 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, false, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, null, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], '', 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, '', false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 'foo', 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, 'foo', false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], [], 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, [], false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], [ 'a' ], 3, false, 'InvalidArgumentException' ],
            [ [ 'a', 'b', 'c' ], 0, [ 'a' ], false, 'InvalidArgumentException' ],
        ];
    }
    
    /**
     * @dataProvider providerCheckSize
     */
    public function testCheckSize($list, $min, $max, $expected, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        if (is_null($min)) {
            $this->assertSame($expected, Arrays::checkSize($list, $max));
        } else {
            $this->assertSame($expected, Arrays::checkSize($list, $max, $min));
        }
    }
}
