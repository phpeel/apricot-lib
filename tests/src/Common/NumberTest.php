<?php
namespace Phpingguo\ApricotLib\Tests\Common;

use Phpingguo\ApricotLib\Common\Number;

class NumberTest extends \PHPUnit_Framework_TestCase
{
    public function providerIsValidInt()
    {
        return [
            [ [ 0, 1, 2, 3, 4 ], true ],
            [ [ -2, -1, 0, 1, 2 ], true ],
            [ [ -4, -3, -2, -1, 0 ], true ],
            [ [ '0', '1', '2', '3', '4' ], false ],
            [ [ '-4', '-3', '-2', '-1', '0' ], false ],
            [ [ true, false, true, false, true ], false ],
            [ [ 'a', 'b', 'c', 'd', 'e' ], false ],
            [ [ null, null, null, null, null ], false ],
            [ [ new \stdClass(), new \stdClass(), new \stdClass() ], false ],
            [ [ [], [], [], [], [] ], false ],
            [ [ true, false, null, [], new \stdClass() ], false ],
        ];
    }

    /**
     * @dataProvider providerIsValidInt
     */
    public function testIsValidInt($values, $expected)
    {
        $this->assertSame(
            $expected,
            call_user_func_array('Phpingguo\ApricotLib\Common\Number::isValidInt', $values)
        );
    }
    
    public function providerIsValidUInt()
    {
        return [
            [ [ 0, 1, 2, 3, 4 ], true ],
            [ [ -2, -1, 0, 1, 2 ], false ],
            [ [ -4, -3, -2, -1, 0 ], false ],
            [ [ '0', '1', '2', '3', '4' ], false ],
            [ [ '-4', '-3', '-2', '-1', '0' ], false ],
            [ [ true, false, true, false, true ], false ],
            [ [ 'a', 'b', 'c', 'd', 'e' ], false ],
            [ [ null, null, null, null, null ], false ],
            [ [ new \stdClass(), new \stdClass(), new \stdClass() ], false ],
            [ [ [], [], [], [], [] ], false ],
            [ [ true, false, null, [], new \stdClass() ], false ],
        ];
    }

    /**
     * @dataProvider providerIsValidUInt
     */
    public function testIsValidUInt($values, $expected)
    {
        $this->assertSame(
            $expected,
            call_user_func_array('Phpingguo\ApricotLib\Common\Number::isValidUInt', $values)
        );
    }
    
    public function providerIsValidFloat()
    {
        return [
            [ [ 0.0, 0.1, 0.2, 0.3, 0.4 ], true ],
            [ [ -0.2, -0.1, 0.0, -0.1, -0.2 ], true ],
            [ [ -0.4, -0.3, -0.2, -0.1, 0.0 ], true ],
            [ [ '0.0', '0.1', '0.2', '0.3', '0.4' ], false ],
            [ [ '-0.4', '-0.3', '-0.2', '-0.1', '0.0' ], false ],
            [ [ true, false, true, false, true ], false ],
            [ [ 'a', 'b', 'c', 'd', 'e' ], false ],
            [ [ null, null, null, null, null ], false ],
            [ [ new \stdClass(), new \stdClass(), new \stdClass() ], false ],
            [ [ [], [], [], [], [] ], false ],
            [ [ true, false, null, [], new \stdClass() ], false ],
        ];
    }

    /**
     * @dataProvider providerIsValidFloat
     */
    public function testIsValidFloat($values, $expected)
    {
        $this->assertSame(
            $expected,
            call_user_func_array('Phpingguo\ApricotLib\Common\Number::isValidFloat', $values)
        );
    }
    
    public function providerIsValidUFloat()
    {
        return [
            [ [ 0.0, 0.1, 0.2, 0.3, 0.4 ], true ],
            [ [ -0.2, -0.1, 0.0, -0.1, -0.2 ], false ],
            [ [ -0.4, -0.3, -0.2, -0.1, 0.0 ], false ],
            [ [ '0.0', '0.1', '0.2', '0.3', '0.4' ], false ],
            [ [ '-0.4', '-0.3', '-0.2', '-0.1', '0.0' ], false ],
            [ [ true, false, true, false, true ], false ],
            [ [ 'a', 'b', 'c', 'd', 'e' ], false ],
            [ [ null, null, null, null, null ], false ],
            [ [ new \stdClass(), new \stdClass(), new \stdClass() ], false ],
            [ [ [], [], [], [], [] ], false ],
            [ [ true, false, null, [], new \stdClass() ], false ],
        ];
    }

    /**
     * @dataProvider providerIsValidUFloat
     */
    public function testIsValidUFloat($values, $expected)
    {
        $this->assertSame(
            $expected,
            call_user_func_array('Phpingguo\ApricotLib\Common\Number::isValidUFloat', $values)
        );
    }
    
    public function providerIsInInterval()
    {
        return [
            [ 0, 0, 0, false, true ],
            [ 0, -5, 5, false, true ],
            [ 5, -5, 5, false, true ],
            [ -5, -5, 5, false, true ],
            [ null, -5, 5, false, false ],
            [ true, -5, 5, false, false ],
            [ false, -5, 5, false, false ],
            [ [], -5, 5, false, false ],
            [ [ 'a' ], -5, 5, false, false ],
            [ '', -5, 5, false, false ],
            [ '0', -5, 5, false, false ],
            [ 'a', -5, 5, false, false ],
            [ new \stdClass(), -5, 5, false, false ],
            [ 0, 0, 0, true, false ],
            [ 0, -5, 5, true, true ],
            [ 5, -5, 5, true, false ],
            [ -5, -5, 5, true, false ],
            [ null, -5, 5, true, false ],
            [ true, -5, 5, true, false ],
            [ false, -5, 5, true, false ],
            [ [], -5, 5, true, false ],
            [ [ 'a' ], -5, 5, true, false ],
            [ '', -5, 5, true, false ],
            [ '0', -5, 5, true, false ],
            [ 'a', -5, 5, true, false ],
            [ new \stdClass(), -5, 5, true, false ],
        ];
    }

    /**
     * @dataProvider providerIsInInterval
     */
    public function testIsInInterval($value, $min, $max, $open_end_points, $expected)
    {
        $this->assertSame($expected, Number::isInInterval($value, $min, $max, $open_end_points));
    }
}
