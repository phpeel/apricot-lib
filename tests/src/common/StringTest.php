<?php
namespace Phpingguo\ApricotLib\Tests\Common;

use Phpingguo\ApricotLib\Common\String;
use Phpingguo\ApricotLib\Enums\LibEnumName;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function providerRemoveNamespace()
    {
        return [
            [ LibEnumName::VARIABLE, 'Variable' ],
            [ LibEnumName::CHARSET, 'Charset' ],
            [ LibEnumName::HTTP_METHOD, 'HttpMethod' ],
            [ 'stdClass', 'stdClass' ],
            [ '', '' ],
            [ true, '' ],
            [ false, '' ],
            [ 0, '' ],
            [ 0.0, '' ],
            [ 0.1, '' ],
            [ '0', '' ],
            [ '0.0', '' ],
            [ '0.1', '' ],
            [ null, '' ],
            [ [], '' ],
            [ new \stdClass(), '' ],
        ];
    }

    /**
     * @dataProvider providerRemoveNamespace
     */
    public function testRemoveNamespace($class_name, $expected_name)
    {
        $this->assertSame($expected_name, String::removeNamespace($class_name));
    }
    
    public function providerIsContains()
    {
        return [
            [ 'hoge', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'hoge', [ 'hoge', 'foo', 'bar' ], false, true ],
            [ 'HOGE', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'HOGE', [ 'hoge', 'foo', 'bar' ], false, false ],
            [ 'foo', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'foo', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'FOO', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'FOO', [ 'hoge', 'foo', 'bar' ], false, false ],
            [ 'bar', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'bar', [ 'hoge', 'foo', 'bar' ], false, true ],
            [ 'BAR', [ 'hoge', 'foo', 'bar' ], true, true ],
            [ 'BAR', [ 'hoge', 'foo', 'bar' ], false, false ],
            [ '', [ 'hoge', 'foo', 'bar' ], true, false ],
            [ '', [ 'hoge', 'foo', 'bar' ], false, false ],
            [ 0, [ 'hoge', 'foo', 'bar' ], true, false ],
            [ 0, [ 'hoge', 'foo', 'bar' ], false, false ],
            [ 0.0, [ 'hoge', 'foo', 'bar' ], true, false ],
            [ 0.0, [ 'hoge', 'foo', 'bar' ], false, false ],
            [ 0.1, [ 'hoge', 'foo', 'bar' ], true, false ],
            [ 0.1, [ 'hoge', 'foo', 'bar' ], false, false ],
            [ '0', [ 'hoge', 'foo', 'bar' ], true, false ],
            [ '0.0', [ 'hoge', 'foo', 'bar' ], true, false ],
            [ '0.1', [ 'hoge', 'foo', 'bar' ], true, false ],
            [ true, [ 'hoge', 'foo', 'bar' ], true, false ],
            [ true, [ 'hoge', 'foo', 'bar' ], false, false ],
            [ false, [ 'hoge', 'foo', 'bar' ], true, false ],
            [ false, [ 'hoge', 'foo', 'bar' ], false, false ],
            [ null, [ 'hoge', 'foo', 'bar' ], true, false ],
            [ null, [ 'hoge', 'foo', 'bar' ], false, false ],
            [ [], [ 'hoge', 'foo', 'bar' ], true, false ],
            [ [], [ 'hoge', 'foo', 'bar' ], false, false ],
            [ new \stdClass(), [ 'hoge', 'foo', 'bar' ], true, false ],
            [ new \stdClass(), [ 'hoge', 'foo', 'bar' ], false, false ],
        ];
    }

    /**
     * @dataProvider providerIsContains
     */
    public function testIsContains($haystack, $needle_list, $ignore_case, $expected)
    {
        $this->assertSame($expected, String::isContains($haystack, $needle_list, $ignore_case));
    }
    
    public function providerIsNotRegexMatched()
    {
        return [
            [ 'abc def ghi', '/[c-e]/', 0, true ],
            [ 'abc def ghi', '/[c-e]/', 1, false ],
            [ 'abc def ghi', '/[c-e]/', false, true ],
        ];
    }

    /**
     * @dataProvider providerIsNotRegexMatched
     */
    public function testIsNotRegexMatched($search, $regex, $not_expected, $result)
    {
        $this->assertSame($result, String::isNotRegexMatched($search, $regex, $not_expected));
    }
}
