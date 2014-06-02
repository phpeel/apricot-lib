<?php
namespace Phpingguo\ApricotLib\Tests\Common;

use Phpingguo\ApricotLib\Common\String;
use Phpingguo\ApricotLib\LibSupervisor;

class StringTest extends \PHPUnit_Framework_TestCase
{
    public function providerRemoveNamespace()
    {
        return [
            [ LibSupervisor::getEnumFullName(LibSupervisor::ENUM_VARIABLE), 'Variable' ],
            [ LibSupervisor::getEnumFullName(LibSupervisor::ENUM_CHARSET), 'Charset' ],
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
    
    public function providerUnionDirectoryPath()
    {
        return [
            [ 'tests', null ],
            [ 'hogehoge', 'RuntimeException' ],
            [ '', 'RuntimeException' ],
            [ null, null ],
            [ true, 'RuntimeException' ],
            [ false, 'RuntimeException' ],
            [ 0, 'RuntimeException' ],
            [ 0.0, 'RuntimeException' ],
            [ 0.1, 'RuntimeException' ],
            [ '0', 'RuntimeException' ],
            [ '0.0', 'RuntimeException' ],
            [ '0.1', 'RuntimeException' ],
            [ [], 'RuntimeException' ],
            [ new \stdClass(), 'RuntimeException' ]
        ];
    }

    /**
     * @dataProvider providerUnionDirectoryPath
     */
    public function testUnionDirectoryPath($sub_directory, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);

        $base_path = LibSupervisor::getBasePath();
        $expected  = $base_path;
        
        if (String::isValid($sub_directory)) {
            $full_path = realpath($base_path . DIRECTORY_SEPARATOR . $sub_directory);
            is_dir($full_path) && $expected = $full_path;
        }
        
        $this->assertSame($expected, String::unionDirectoryPath($base_path, $sub_directory));
    }

    public function providerUnionFilePath()
    {
        return [
            [ 'composer.json', null ],
            [ 'hogehoge', 'RuntimeException' ],
            [ '', 'RuntimeException' ],
            [ null, 'RuntimeException' ],
            [ true, 'RuntimeException' ],
            [ false, 'RuntimeException' ],
            [ 0, 'RuntimeException' ],
            [ 0.0, 'RuntimeException' ],
            [ 0.1, 'RuntimeException' ],
            [ '0', 'RuntimeException' ],
            [ '0.0', 'RuntimeException' ],
            [ '0.1', 'RuntimeException' ],
            [ [], 'RuntimeException' ],
            [ new \stdClass(), 'RuntimeException' ]
        ];
    }

    /**
     * @dataProvider providerUnionFilePath
     */
    public function testUnionFilePath($sub_filename, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);

        $base_path = LibSupervisor::getBasePath();
        $expected  = $base_path;

        if (String::isValid($sub_filename)) {
            $full_path = realpath($base_path . DIRECTORY_SEPARATOR . $sub_filename);
            is_file($full_path) && $expected = $full_path;
        }

        $this->assertSame($expected, String::unionFilePath($base_path, $sub_filename));
    }
    
    public function providerGetEnumFullName()
    {
        $enum_namespace = 'Phpingguo\\ApricotLib\\Enums\\';
        
        return [
            [ $enum_namespace, LibSupervisor::ENUM_CHARSET, 'Phpingguo\ApricotLib\Enums\Charset', null ],
            [ $enum_namespace, LibSupervisor::ENUM_VARIABLE, 'Phpingguo\ApricotLib\Enums\Variable', null ],
            [ $enum_namespace, null, null, 'InvalidArgumentException' ],
            [ null, LibSupervisor::ENUM_CHARSET, null, 'InvalidArgumentException' ],
            [ null, LibSupervisor::ENUM_VARIABLE, null, 'InvalidArgumentException' ],
            [ null, null, null, 'InvalidArgumentException' ],
            [ true, true, null, 'InvalidArgumentException' ],
            [ false, false, null, 'InvalidArgumentException' ],
            [ [], [], null, 'InvalidArgumentException' ],
            [ [ 'a' ], [ 'a' ], null, 'InvalidArgumentException' ],
            [ '', '', null, 'InvalidArgumentException' ],
            [ 0, 0, null, 'InvalidArgumentException' ],
            [ 0.0, 0.0, null, 'InvalidArgumentException' ],
            [ 0.1, 0.1, null, 'InvalidArgumentException' ],
            [ '0', '0', null, 'InvalidArgumentException' ],
            [ '0.0', '0.0', null, 'InvalidArgumentException' ],
            [ '0.1', '0.1', null, 'InvalidArgumentException' ],
            [ new \stdClass(), new \stdClass(), null, 'InvalidArgumentException' ],
        ];
    }

    /**
     * @dataProvider providerGetEnumFullName
     */
    public function testGetEnumFullName($namespace, $enum_name, $expected, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($expected, String::concat($namespace, $enum_name));
    }
}
