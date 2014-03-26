<?php
namespace Phpingguo\ApricotLib\Caching;

use Phpingguo\ApricotLib\Common\Number;
use Phpingguo\ApricotLib\LibSupervisor;

/**
 * Alternative PHP Cache によるキャッシュ管理を仲介するクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class ApcAgent
{
    // ---------------------------------------------------------------------------------------------
    // import trait
    // ---------------------------------------------------------------------------------------------
    use TCacheVersion;
    
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $default_expire_time = 0;
    
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * ApcAgent クラスの新しいインスタンスを初期化します。
     */
    public function __construct()
    {
        if (get_loaded_extensions('apc') === false) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('APC extension does not loaded on this server.');
            // @codeCoverageIgnoreEnd
        }
        
        $this->setCallbackGetter(
            function ($ver_name) {
                return apc_fetch($ver_name);
            }
        );
        $this->setCallbackSetter(
            function ($ver_name, $version) {
                apc_store($ver_name, $version);
            }
        );
    }
    
    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * ApcAgent クラスのインスタンスを取得します。
     * 
     * @return ApcAgent 初回呼び出し時は生成したインスタンス。それ以降は生成済みのインスタンス。
     */
    public static function getInstance()
    {
        return LibSupervisor::getDiContainer()->get(__CLASS__);
    }
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * キャッシュデータのデフォルトの有効期間（秒数）を取得します。
     * 
     * @return Integer キャッシュデータのデフォルトの有効期間（秒数）
     */
    public function getDefaultExpireTime()
    {
        return $this->default_expire_time;
    }
    
    /**
     * キャッシュデータのデフォルトの有効期間（秒数）を設定します。
     *
     * @param Integer $expire_time [初期値=300] キャッシュデータのデフォルトの有効期間（秒数）
     */
    public function setDefaultExpireTime($expire_time = 300)
    {
        Number::isValidInt($expire_time) && $this->default_expire_time = $expire_time;
    }
    
    /**
     * 指定したキー名またはキー名の配列がキャッシュ上に存在するかどうかを調べます。
     * 
     * @param String $group_name                   キーのグループ名
     * @param String|Array $key                    キーの名前、またはそれらからなる配列
     * @param Boolean $is_converted [初期値=false] キーが完全名に変換済みかどうか
     * 
     * @return Boolean 全てのキーが存在する場合は true。それ以外の場合は false。
     */
    public function isExist($group_name, $key, $is_converted = false)
    {
        $key_data = ($is_converted === true) ? $key : $this->getConvertedKeyName($group_name, $key);
        $result   = apc_exists($key_data);
        
        return is_array($result) ? ($key_data === $result) : $result;
    }
    
    /**
     * キャッシュデータから指定したキー名またはキー名の配列に紐付く値を全て取得します。
     * 
     * @param String $group_name           キーのグループ名
     * @param String|Array $key            値を取得するキーの名前、またはそれらからなる配列
     * @param mixed $default [初期値=null] 値が一つでも存在しない場合に使う値
     * 
     * @return mixed 取得成功時は指定キーに該当する値。それ以外の場合は $default で指定した値。
     */
    public function get($group_name, $key, $default = null)
    {
        return apc_fetch($this->getConvertedKeyName($group_name, $key)) ?: $default;
    }
    
    /**
     * キャッシュデータに指定したキーとそれに紐付く値を設定します。
     * 
     * @param String $group_name            キーのグループ名
     * @param String $key                   値を設定するキーの名前
     * @param mixed $value                  新しく設定する値
     * @param Integer $expire [初期値=null] 設定したキーの有効期限（秒数）
     * 
     * @return Boolean キャッシュに保存できた場合は true。それ以外の場合は false。
     */
    public function set($group_name, $key, $value, $expire = null)
    {
        $key_name    = $this->generateKeyName($group_name, $key);
        $expire_time = Number::isValidInt($expire) ? $expire : $this->getDefaultExpireTime();
        
        return apc_store($key_name, $value, $expire_time);
    }
    
    /**
     * キャッシュデータから指定したキーとそれに紐付く値を削除します。
     * 
     * @param String $group_name キーのグループ名
     * @param String $key        削除するキーの名前
     * 
     * @return Boolean キャッシュから削除できた場合は true。それ以外の場合は false。
     */
    public function delete($group_name, $key)
    {
        return apc_delete($this->generateKeyName($group_name, $key));
    }
    
    /**
     * キャッシュデータから指定したグループに属する全てのキーとその値を削除します。
     *
     * @param String $group_name 削除するグループの名前
     */
    public function removeGroup($group_name)
    {
        apc_inc($this->getCacheVersionKey($group_name));
    }
    
    /**
     * キャッシュデータの内容を全て削除します。
     */
    public function clear()
    {
        apc_clear_cache('user');
    }
}
