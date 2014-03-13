<?php
namespace Phpingguo\ApricotLib\Tests\Type\Generics;

use Phpingguo\ApricotLib\Enums\Variable;
use Phpingguo\ApricotLib\Type\Generics\GenericList;

class GenericListTest extends \PHPUnit_Framework_TestCase
{
    public function provider()
    {
        return [
            [ Variable::INTEGER, [ 1, 10, 100, 1000 ], null, 10000, 1, null ],
            [ Variable::INTEGER, [ 1, 10, 100, 1000 ], 'abc', 10000, 1, null ],
            [ Variable::INTEGER, [ 1, 10, 100, 1000 ], null, '', 1, 'DomainException' ],
        ];
    }

    /**
     * @dataProvider provider
     */
    public function test($type_name, $collection, $key, $item, $unset, $exception)
    {
        isset($exception) && $this->setExpectedException($exception);
        
        $list = new GenericList($type_name, $collection);
        
        $this->assertInstanceOf('Phpingguo\ApricotLib\Type\Generics\GenericList', $list);
        $this->assertSame($collection, $list->toArray());

        if (isset($key)) {
            $list[$key] = $item;
        } else {
            $list[] = $item;
            $key    = $list->count() - 1;
        }
        
        $this->assertArrayHasKey($key, $list);
        $this->assertSame($item, $list[$key]);
        
        if (isset($unset)) {
            unset($list[$unset]);
            
            $this->assertArrayNotHasKey($unset, $list);
        }
        
        foreach ($list as $l_key => $l_value) {
            $this->assertSame($collection[$l_key], $l_value);
        }
    }
}
