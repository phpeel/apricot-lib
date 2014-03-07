<?php
namespace Phpingguo\ApricotLib\Tests\Common;

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
}
