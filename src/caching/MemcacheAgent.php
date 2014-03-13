<?php
namespace Phpingguo\ApricotLib\Caching;

use Phpingguo\ApricotLib\Common\Arrays;
use Phpingguo\ApricotLib\Common\General;
use Phpingguo\ApricotLib\Common\Number;
use Phpingguo\ApricotLib\Common\String;
use Phpingguo\ApricotLib\LibrarySupervisor;

/**
 * Memcache によるキャッシュデータ管理を仲介するクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class MemcacheAgent
{
    // ---------------------------------------------------------------------------------------------
    // import trait
    // ---------------------------------------------------------------------------------------------
    use TraitCacheVersion;
    
    // ---------------------------------------------------------------------------------------------
    // private fields
    // ---------------------------------------------------------------------------------------------
    private $obj_memcache = null;
    private $setting_data = [];
    
    // ---------------------------------------------------------------------------------------------
    // constructor / destructor
    // ---------------------------------------------------------------------------------------------
    /**
     * MemcacheAgent クラスの新しいインスタンスを初期化します。
     */
    public function __construct()
    {
        // Memcacheは存在しない場合でも他のライブラリとは異なり例外はスローしない
        if (class_exists('Memcache', false)) {
            $this->setMemcache(new \Memcache());
            $this->setCallbackGetter(
                function ($ver_name) {
                    return $this->getMemcache()->get($ver_name);
                }
            );
            $this->setCallbackSetter(
                function ($ver_name, $version) {
                    $this->getMemcache()->set($ver_name, $version);
                }
            );
        }
    }
    
    // ---------------------------------------------------------------------------------------------
    // public class methods
    // ---------------------------------------------------------------------------------------------
    /**
     * MemcacheAgent クラスのインスタンスを取得します。
     * 
     * @return MemcacheAgent 初回呼び出し時は生成したインスタンス。それ以降は生成済みのインスタンス。
     */
    public static function getInstance()
    {
        return LibrarySupervisor::getDiContainer()->get(__CLASS__);
    }
    
    // ---------------------------------------------------------------------------------------------
    // public member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * Memcache が使用可能であるかどうかを調べます。
     * 
     * @return Boolean 使用可能の場合は true。それ以外の場合は false。
     */
    public function isEnabled()
    {
        return (($this->obj_memcache instanceof \Memcache) === true);
    }

    /**
     * Memcache のサーバークラスタリング設定を行います。
     *
     * @param String $setting_dir [初期値=null]  クラスタリング設定ファイルがあるディレクトリのパス
     * @param String $setting_file [初期値=null] クラスタリング設定ファイルの名前
     * 
     * @return Boolean クラスタリング設定が正常に終了した場合は true。それ以外の場合は false。
     */
    public function setClustering($setting_dir = null, $setting_file = null)
    {
        if ($this->isEnabled() === false) {
            // @codeCoverageIgnoreStart
            return false;
            // @codeCoverageIgnoreEnd
        }
        
        $this->setSettingData($this->loadSetting($setting_dir, $setting_file));
        
        $cluster_list  = Arrays::getValue($this->getSettingData(), 'Clusters', []);
        $server_weight = empty($cluster_list) ? 0 : (int)(100 / count($cluster_list));
        
        /** @noinspection PhpUnusedParameterInspection */
        return Arrays::eachWalk(
            $cluster_list,
            function ($server_data, $key, $result) use ($server_weight) {
                $host = Arrays::getValue($server_data, 'Host', null);
                $port = Arrays::getValue($server_data, 'Port', null);
                
                return (bool)($result | $this->getMemcache()->addserver($host, $port, true, $server_weight));
            }
        );
    }

    /**
     * キャッシュデータから指定したキーに該当する値を取得します。
     * 
     * @param String $group_name           キーのグループ名
     * @param String $key                  値を取得するキーの名前
     * @param mixed $default [初期値=null] 値が存在しない場合に代用する値
     * 
     * @return mixed 取得成功時は指定キーに該当する値。それ以外の場合は $default で指定した値。
     */
    public function get($group_name, $key, $default = null)
    {
        return $this->getMemcache()->get($this->getConvertedKeyName($group_name, $key)) ?: $default;
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
        $default_expire  = Arrays::getValue($this->getSettingData(), 'ExpireTime', 300);
        $key_name    = $this->generateKeyName($group_name, $key);
        $expire_time = Number::isValidInt($expire) ? $expire : $default_expire;
        
        return $this->getMemcache()->set($key_name, $value, 0, $expire_time);
    }

    /**
     * キャッシュデータから指定したキーとそれに紐付く値を削除します。
     * 
     * @param String $group_name        キーのグループ名
     * @param String $key [初期値=null] 削除するキーの名前
     * 
     * @return Boolean キャッシュから削除できた場合は true。それ以外の場合は false。
     */
    public function delete($group_name, $key = null)
    {
        return $this->getMemcache()->delete($this->generateKeyName($group_name, $key));
    }

    /**
     * キャッシュデータから指定したグループに属する全てのキーとその値を削除します。
     *
     * @param String $group_name 削除するグループの名前
     */
    public function removeGroup($group_name)
    {
        $this->getMemcache()->increment($this->getCacheVersion($group_name));
    }

    /**
     * キャッシュデータの内容を全て削除します。
     */
    public function clear()
    {
        $this->getMemcache()->flush();
    }
    
    // ---------------------------------------------------------------------------------------------
    // private member methods
    // ---------------------------------------------------------------------------------------------
    /**
     * 生成済みの Memcache のインスタンスを取得します。
     * 
     * @throws \RuntimeException 初期化されていない場合
     * @return \Memcache 生成済みのインスタンス
     */
    private function getMemcache()
    {
        if ($this->isEnabled() === false) {
            // @codeCoverageIgnoreStart
            throw new \RuntimeException('Memcache instance is not exists.');
            // @codeCoverageIgnoreEnd
        }
        
        return $this->obj_memcache;
    }

    /**
     * Memcache のインスタンスを設定します。
     *
     * @param \Memcache $memcache 新しく設定する Memcache のインスタンス
     */
    private function setMemcache(\Memcache $memcache)
    {
        $this->obj_memcache = $memcache;
    }

    /**
     * キャッシュに関する設定データを取得します。
     * 
     * @return Array キャッシュに関する設定データ
     */
    private function getSettingData()
    {
        return $this->setting_data;
    }

    /**
     * キャッシュに関する設定データを設定します。
     *
     * @param Array $setting_data 新しく設定するキャッシュに関するデータ
     */
    private function setSettingData(array $setting_data)
    {
        $this->setting_data = $setting_data;
    }

    /**
     * キャッシュに関する設定ファイルを読み込みます。
     *
     * @param String $directory_path キャッシュ設定ファイルがあるディレクトリのパス
     * @param String $file_name      キャッシュ設定ファイルの名前
     * 
     * @return Array キャッシュに関する設定データ
     */
    private function loadSetting($directory_path, $file_name)
    {
        $parsed_data = General::getParsedYamlFile(
            $this->getLoadDirectoryPath($directory_path),
            $this->getLoadYamlFileName($file_name)
        );
        
        return is_array($parsed_data) ? $parsed_data : [];
    }

    /**
     * 読み込むキャッシュ設定ファイルが存在するディレクトリのファイルパスを取得します。
     *
     * @param String $directory_path キャッシュ設定ファイルが存在するディレクトリのファイルパス
     * 
     * @return String 読み込むキャッシュ設定ファイルが存在するディレクトリのファイルパス
     */
    private function getLoadDirectoryPath($directory_path)
    {
        return String::isValid($directory_path) ? $directory_path : LibrarySupervisor::getConfigPath();
    }

    /**
     * 読み込むキャッシュ設定ファイルの拡張子を含まないファイル名を取得します。
     *
     * @param String $file_name キャッシュ設定ファイルの拡張子を含まないファイル名
     * 
     * @return String 読み込むキャッシュ設定ファイルの拡張子を含まないファイル名
     */
    private function getLoadYamlFileName($file_name)
    {
        return String::isValid($file_name) ? $file_name : 'library_memcache_clustering';
    }
}
