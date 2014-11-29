<?php
namespace Phpeel\ApricotLib\Tests\Type\Generics;

use Phpeel\ApricotLib\Enums\Variable;
use Phpeel\ApricotLib\Type\Generics\GenericList;

class GenericListTest extends \PHPUnit_Framework_TestCase
{
    const GENERIC_LIST_CLASS_PATH = 'Phpeel\ApricotLib\Type\Generics\GenericList';

    public function provider()
    {
        return [
            [Variable::INTEGER, [1, 10, 100, 1000], null, 10000, null],
            [Variable::INTEGER, [1, 10, 100, 1000], 'abc', 10000, null],
            [Variable::INTEGER, [1, 10, 100, 1000], null, '', 'DomainException'],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function testAdd($type_name, $collection, $key, $item, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);

        $list = new GenericList($type_name, $collection);

        $this->assertInstanceOf(self::GENERIC_LIST_CLASS_PATH, $list);
        $this->assertSame($collection, $list->toArray());

        if (isset($key)) {
            $list[$key] = $item;
        } else {
            $list[] = $item;
            $key    = $list->count() - 1;
        }

        $this->assertSame($item, $list[$key]);
        $this->assertNotContains($item, $collection);
        $this->assertArrayHasKey($key, $list);
        $this->assertNotSameSize($collection, $list);
        $this->assertCount(count($collection) + 1, $list);
    }

    public function providerRemove()
    {
        return [
            [Variable::INTEGER, [1, 10, 100, 1000], 0, null],
            [Variable::INTEGER, [1, 10, 100, 1000], 1, null],
            [Variable::INTEGER, [1, 10, 100, 1000], 3, null],
        ];
    }

    /**
     * @dataProvider providerRemove
     */
    public function testRemove($type_name, $collection, $unset, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);

        $list = new GenericList($type_name, $collection);

        $this->assertInstanceOf(self::GENERIC_LIST_CLASS_PATH, $list);
        $this->assertSame($collection, $list->toArray());

        $remove_item = $list[$unset];
        unset($list[$unset]);

        $this->assertSame($remove_item, $collection[$unset]);
        $this->assertContains($remove_item, $collection);
        $this->assertArrayNotHasKey($unset, $list);
        $this->assertNotSameSize($collection, $list);
        $this->assertCount(count($collection) - 1, $list);
    }

    public function providerPointer()
    {
        return [
            [Variable::INTEGER, [1, 10, 100, 1000]],
            [Variable::STRING, ['a', 'b', 'c', 'd']],
            [Variable::TEXT, ['1', 'a', '100', 'あ']],
        ];
    }

    /**
     * @dataProvider providerPointer
     */
    public function testPointer($type_name, $collection)
    {
        $list = new GenericList($type_name, $collection);

        $this->assertInstanceOf(self::GENERIC_LIST_CLASS_PATH, $list);

        foreach ($list as $l_key => $l_value) {
            $this->assertSame($collection[$l_key], $l_value);
        }
    }
}
