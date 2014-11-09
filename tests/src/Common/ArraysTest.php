<?php
namespace Phpeel\ApricotLib\Tests\Common;

use Phpeel\ApricotLib\Common\Arrays;

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
    
    public function providerGetValue()
    {
        return [
            [ [ 'a' => 0, 'b' => 1 ], 'a', null, 0 ],
            [ [ 'a' => 0, 'b' => 1 ], 'b', null, 1 ],
            [ [ 'a' => 0, 'b' => 1 ], 'c', null, null ],
            [ [ 'a' => 0, 'b' => 1 ], 'd', -1, -1 ],
            [ [ 'a' => 0, 'b' => 1 ], 0, null, null ],
            [ [ 'a' => 0, 'b' => 1 ], 0.0, null, null ],
            [ [ 'a' => 0, 'b' => 1 ], 0.1, null, null ],
            [ [ 'a' => 0, 'b' => 1 ], '0', null, null ],
            [ [ 'a' => 0, 'b' => 1 ], '0.0', null, null ],
            [ [ 'a' => 0, 'b' => 1 ], '0.1', null, null ],
            [ [ 'a' => 0, 'b' => 1 ], null, null, null ],
            [ [ 'a' => 0, 'b' => 1 ], true, null, null ],
            [ [ 'a' => 0, 'b' => 1 ], false, null, null ],
            [ [ 'a' => 0, 'b' => 1 ], [], null, null ],
            [ [ 'a' => 0, 'b' => 1 ], '', null, null ],
        ];
    }

    /**
     * @dataProvider providerGetValue
     */
    public function testGetValue($list, $key, $default, $expected)
    {
        $this->assertSame($expected, Arrays::getValue($list, $key, $default));
    }
    
    public function providerItemControl()
    {
        return [
            [ true, true, 'foo bar', 'name', true, true, true, true ],
            [ false, true, 'foo bar', 'name', false, false, false, true ],
            [ true, false, 'foo bar', 'name', true, true, false, false ],
            [ true, true, 'hoge hoge', null, true, true, true, true ],
            [ false, true, 'hoge hoge', null, false, false, false, true ],
            [ true, false, 'hoge hoge', null, true, true, false, false ],
        ];
    }
    
    /**
     * @dataProvider providerItemControl
     */
    public function testItemControl($add_condition, $remove_condition, $item, $key, $add, $added, $remove, $removed)
    {
        $list = [];
        
        $this->assertSame($add, Arrays::addWhen($add_condition, $list, $item, $key));
        $this->assertSame($added, Arrays::isValid($list));
        $this->assertSame($added ? $item : null, Arrays::getValue($list, $key ?: 0));
        
        $this->assertSame($remove, Arrays::removeWhen($remove_condition, $list, $key ?: 0));
        $this->assertSame($removed, Arrays::isEmpty($list));
        $this->assertSame($removed ? null : $item, Arrays::getValue($list, $key ?: 0));
    }
    
    public function providerFindValue()
    {
        return [
            [ [ 'name' => 'hoge hoge' ], 'name', null, 'hoge hoge' ],
            [ [ 1 => [ 'name' => 'hoge hoge' ] ], 1, null, [ 'name' => 'hoge hoge' ] ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1 => 2 => 3 => name', null, 'hoge hoge' ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1　=>　2 => 3 => name', null, 'hoge hoge' ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1 => 2', null, [ 3 => [ 'name' => 'hoge hoge' ] ] ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1 => 2 => 3 => age', 'NaN', 'NaN' ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1 => 2 => 4', 'NaN', 'NaN' ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1 => 2 => 3 => null', 'NaN', 'NaN' ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], '1 => null => 3', 'NaN', 'NaN' ],
            [ [ 1 => [ 2 => [ 3 => [ 'name' => 'hoge hoge' ] ] ] ], 'false => null => 3', 'NaN', 'NaN' ],
        ];
    }

    /**
     * @dataProvider providerFindValue
     */
    public function testFindValue($list, $find, $default, $expected)
    {
        $this->assertSame($expected, Arrays::findValue($list, $find, $default));
    }
    
    public function providerPutValue()
    {
        return [
            [ [], '1=>2=>name', '1=>2=>name', 'hoge hoge', null, 'hoge hoge' ],
            [ [], '1 => 2 => name', '1=>2=>name', 'hoge hoge', null, 'hoge hoge' ],
            [ [ 1 => [ 3 => [ 'name' => 'foo bar' ] ] ], '1=>2=>name', '1=>3=>name', 'hoge hoge', null, 'foo bar' ],
            [ [ 1 => [ 3 => [ 'name' => 'foo bar' ] ] ], '1=>2=>name', '1=>2=>name', 'hoge hoge', null, 'hoge hoge' ]
        ];
    }

    /**
     * @dataProvider providerPutValue
     */
    public function testPutValue($list, $put_key, $find_key, $item, $default, $expected)
    {
        $this->assertTrue(Arrays::putValue($list, $put_key, $item));
        $this->assertSame($expected, Arrays::findValue($list, $find_key, $default));
    }
    
    public function providerAddEach()
    {
        $closure = function () {
            return true;
        };
        
        return [
            [
                [ 'a', 'b', 'c' ], true, $closure,
                [ 'd', 'e', 'f' ], true,
                [ 'a', 'b', 'c', 'd', 'e', 'f' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure,
                [ 'x' => 'd', 'y' => 'e', 'z' => 'f' ], true,
                [ 'a', 'b', 'c', 'x' => 'd', 'y' => 'e', 'z' => 'f' ]
            ], [
                [ 'a', 'b', 'c' ], false, $closure,
                [ 'd', 'e', 'f' ], false,
                [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], false, $closure,
                [ 'x' => 'd', 'y' => 'e', 'z' => 'f' ], false,
                [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, '', false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, '0', false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, 0, false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, 0.0, false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, null, false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, true, false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, false, false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, [], false, [ 'a', 'b', 'c' ]
            ], [
                [ 'a', 'b', 'c' ], true, $closure, new \stdClass(), false, [ 'a', 'b', 'c' ]
            ],
        ];
    }
    
    /**
     * @dataProvider providerAddEach
     */
    public function testAddEach($list, $loop, $add, $new, $expected, $after)
    {
        $this->assertSame($expected, Arrays::addEach($loop, $list, $add, $new));
        $this->assertSame($after, $list);
    }
    
    public function providerRemoveEach()
    {
        return [
            [ [ 'a', 'b', 'c' ], true, function ($v) {
                return ($v === 'b');
            }, true, [ 0 => 'a', 2 => 'c' ] ],
            [ [ 'a', 'b', 'c' ], true, function ($v) {
                return ($v === 'd');
            }, false, [ 'a', 'b', 'c' ] ],
            [ [ 'a', 'b', 'c' ], false, function () {
                return true;
            }, false, [ 'a', 'b', 'c' ] ],
        ];
    }

    /**
     * @dataProvider providerRemoveEach
     */
    public function testRemoveEach($list, $loop, $remove, $expected, $after)
    {
        $this->assertSame($expected, Arrays::removeEach($loop, $list, $remove));
        $this->assertSame($after, $list);
    }
    
    public function providerCopyWhen()
    {
        return [
            [ true, [ 'a', 'b', 'c' ], [ 'e', 'f', 'g' ], true, [ 'e', 'f', 'g' ] ],
            [ true, [ 'a', 'b', 'c' ], '', false, [ 'a', 'b', 'c' ] ],
            [ true, [ 'a', 'b', 'c' ], function () {
                return [ 'e', 'f', 'g' ];
            }, true, [ 'e', 'f', 'g' ] ],
            [ true, [ 'a', 'b', 'c' ], function () {
                return '';
            }, false, [ 'a', 'b', 'c' ] ],
        ];
    }

    /**
     * @dataProvider providerCopyWhen
     */
    public function testCopyWhen($condition, $to, $from, $expected, $after)
    {
        $this->assertSame($expected, Arrays::copyWhen($condition, $to, $from));
        $this->assertSame($after, $to);
    }
    
    public function providerMergeWhen()
    {
        return [
            [ true, [ 'a', 'b', 'c' ], [ 'e', 'f', 'g' ], true, [ 'e', 'f', 'g' ] ],
            [ true, [ [ 'a' ], 'b', 'c' ], [ [ 'e' ], 'f', 'g' ], true, [ [ 'e' ], 'f', 'g' ] ],
            [ true, [ [ 0 => 'a' ], 'b', 'c' ], [ [ 1 => 'e' ], 'f', 'g' ], true, [ [ 'a', 'e' ], 'f', 'g' ] ],
            [ true, [ 'a', 'b', 'c' ], [ 3 => 'e', 4 => 'f', 5 => 'g' ], true, [ 'a', 'b', 'c', 'e', 'f', 'g' ] ],
            [ true, [ 'a', 'b', 'c' ], '', false, [ 'a', 'b', 'c' ] ],
            [ true, [ 'a', 'b', 'c' ], function () {
                return [ 'e', 'f', 'g' ];
            }, true, [ 'e', 'f', 'g' ] ],
            [ true, [ 'a', 'b', 'c' ], function () {
                return [ 3 => 'e', 4 => 'f', 5 => 'g' ];
            }, true, [ 'a', 'b', 'c', 'e', 'f', 'g' ] ],
            [ true, [ 'a', 'b', 'c' ], function () {
                return  '';
            }, false, [ 'a', 'b', 'c' ] ],
        ];
    }
    
    /**
     * @dataProvider providerMergeWhen
     */
    public function testMergeWhen($condition, $target, $merged, $expected, $after)
    {
        $this->assertSame($expected, Arrays::mergeWhen($condition, $target, $merged));
        $this->assertSame($after, $target);
    }
    
    public function providerFiltering()
    {
        return [
            [ [ 'a', 'b', 'c', 'd' ], function ($value) {
                return ($value !== 'c');
            }, false, [ 0 => 'a', 1 => 'b', 3 => 'd' ] ],
            [ [ 'a', 'b', 'c', 'd' ], function ($value) {
                return ($value !== 'c');
            }, true, [ 0 => 'a', 1 => 'b', 2 => 'd' ] ],
            [ [ 'a', '', 'c', 'd' ], 'trim', false, [ 0 => 'a', 2 => 'c', 3 => 'd' ] ],
            [ [ 'a', '', 'c', 'd' ], 'trim', true, [ 0 => 'a', 1 => 'c', 2 => 'd' ] ],
            [ [ 0, 1, false, 100, null, true ], null, false, [ 1 => 1, 3 => 100, 5 => true ] ],
            [ [ 0, 1, false, 100, null, true ], null, true, [ 0 => 1, 1 => 100, 2 => true ] ],
        ];
    }

    /**
     * @dataProvider providerFiltering
     */
    public function testFiltering($list, $filter, $is_reindex, $expected)
    {
        $this->assertSame($expected, Arrays::filter($list, $filter, $is_reindex));
    }
    
    public function providerClear()
    {
        return [
            [ [ 'a', 'b', 'c' ] ],
        ];
    }

    /**
     * @dataProvider providerClear
     */
    public function testClear($list)
    {
        Arrays::clear($list);
        $this->assertEmpty($list);
    }
}
