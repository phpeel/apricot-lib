<?php
namespace Phpingguo\ApricotLib\Common;

use Symfony\Component\Yaml\Parser;

/**
 * フレームワークで使用する共通の汎用処理を纏めたクラスです。
 * 
 * @final [継承禁止クラス]
 * @author hiroki sugawara
 */
final class General
{
    /**
     * 入力値を解析し、クロージャなら実行後の値を、それ以外はそのままの値を取得します。
     * 
     * @param mixed $value 解析対象となる変数
     * 
     * @return mixed 入力値の解析後の値
     */
    public static function getParsedValue($value)
    {
        return ($value instanceof \Closure) ? $value() : $value;
    }
    
    /**
     * 入力変数の値が全て指定した条件を満たしているかどうかをチェックします。
     *
     * @param Array $values        入力変数の値の配列
     * @param callable $conditions 入力変数の配列の要素の満たすべき条件
     *
     * @return Boolean 全ての入力値が指定した条件を満たしている場合は true。それ以外の場合は false。
     */
    public static function checkValueValid(array $values, callable $conditions)
    {
        foreach ($values as $value) {
            if (false === $conditions($value)) {
                return false;
            }
        }
        
        return (empty($values) === false);
    }
    
    /**
     * 指定したディレクトリにあるYamlファイルを解析した結果を取得します。
     *
     * @param String $dir_path  解析するYamlファイルが存在するディレクトリのファイルパス
     * @param String $yaml_name 解析するYamlファイルの拡張子なしのファイル名
     *
     * @throws \InvalidArgumentException 引数のうちいずれかが文字列ではなかった場合
     *
     * @return mixed|null 読み込み成功時はそのファイルの内容。それ以外の時は null。
     */
    public static function getParsedYamlFile($dir_path, $yaml_name)
    {
        if (String::isValid($dir_path, false) === false || String::isValid($yaml_name, false) === false) {
            throw new \InvalidArgumentException('$dir_path and $yaml_name accepts only string.');
        }
        
        $full_path = realpath($dir_path . DIRECTORY_SEPARATOR . $yaml_name . '.yml');
        
        return is_file($full_path) ? (new Parser())->parse(file_get_contents($full_path)) : null;
    }
}
