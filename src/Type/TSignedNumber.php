<?php
namespace Phpeel\ApricotLib\Type;

/**
 * 数値の符号の取り扱いに関する機能を集めたトレイトです。
 * 
 * @author hiroki sugawara
 */
trait TSignedNumber
{
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $allow_unsigned = false;
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 取得する値に符号なしであることを許すかどうかを設定します。
     * 
     * @param Boolean $unsigned [初期値=false] 取得する値に符号なしを許すかどうか
     */
    private function setAllowUnsigned($unsigned = false)
    {
        $this->allow_unsigned = ($unsigned === true);
    }
    
    /**
     * 取得する値に符号なしであることを許すかどうかを取得します。
     * 
     * @return Boolean 取得する値に符号なしであることを許す場合は true。それ以外の場合は false。
     */
    private function getAllowUnsigned()
    {
        return $this->allow_unsigned;
    }
}
