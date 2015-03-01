<?php
namespace Phpeel\ApricotLib\Tests\Common;

use Phpeel\ApricotLib\Common\String;
use Phpeel\ApricotLib\LibSupervisor;

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
        $enum_namespace = 'Phpeel\\ApricotLib\\Enums\\';
        
        return [
            [ $enum_namespace, LibSupervisor::ENUM_CHARSET, 'Phpeel\ApricotLib\Enums\Charset', true, null ],
            [ $enum_namespace, LibSupervisor::ENUM_VARIABLE, 'Phpeel\ApricotLib\Enums\Variable', true, null ],
            [ $enum_namespace, null, null, true, 'InvalidArgumentException' ],
            [ null, LibSupervisor::ENUM_CHARSET, null, true, 'InvalidArgumentException' ],
            [ null, LibSupervisor::ENUM_VARIABLE, null, true, 'InvalidArgumentException' ],
            [ null, null, null, true, 'InvalidArgumentException' ],
            [ true, true, null, true, 'InvalidArgumentException' ],
            [ false, false, null, true, 'InvalidArgumentException' ],
            [ [], [], null, true, 'InvalidArgumentException' ],
            [ [ 'a' ], [ 'a' ], null, true, 'InvalidArgumentException' ],
            [ '', '', null, true, 'InvalidArgumentException' ],
            [ 0, 0, null, true, 'InvalidArgumentException' ],
            [ 0.0, 0.0, null, true, 'InvalidArgumentException' ],
            [ 0.1, 0.1, null, true, 'InvalidArgumentException' ],
            [ '0', '0', null, true, 'InvalidArgumentException' ],
            [ '0.0', '0.0', null, true, 'InvalidArgumentException' ],
            [ '0.1', '0.1', null, true, 'InvalidArgumentException' ],
            [ new \stdClass(), new \stdClass(), null, true, 'InvalidArgumentException' ],
            [ $enum_namespace, 0, null, false, 'InvalidArgumentException' ],
            [ $enum_namespace, 0.0, null, false, 'InvalidArgumentException' ],
            [ $enum_namespace, 0.1, null, false, 'InvalidArgumentException' ],
            [ $enum_namespace, 1, null, false, 'InvalidArgumentException' ],
            [ $enum_namespace, 10000, null, false, 'InvalidArgumentException' ],
            [ $enum_namespace, '0', 'Phpeel\ApricotLib\Enums\0', false, null ],
            [ $enum_namespace, '0.0', 'Phpeel\ApricotLib\Enums\0.0', false, null ],
            [ $enum_namespace, '0.1', 'Phpeel\ApricotLib\Enums\0.1', false, null ],
            [ $enum_namespace, '1', 'Phpeel\ApricotLib\Enums\1', false, null ],
            [ $enum_namespace, '10000', 'Phpeel\ApricotLib\Enums\10000', false, null ],
        ];
    }

    /**
     * @dataProvider providerGetEnumFullName
     */
    public function testGetEnumFullName($namespace, $enum_name, $expected, $is_strict, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $this->assertSame($expected, String::concat($namespace, $enum_name, $is_strict));
    }
}
