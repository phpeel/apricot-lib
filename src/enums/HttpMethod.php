<?php
namespace Phpingguo\ApricotLib\Enums;

use Phpingguo\ApricotLib\Type\Enum\Enum;

/**
 * HTTPメソッドの種類を示します。
 * 
 * @final [列挙型属性]
 * @author hiroki sugawara
 */
final class HttpMethod extends Enum
{
    /** [HTTP/1.0 以上] サーバーからリソースを取得することを要求する */
    const GET     = 'GET';
    
    /** [HTTP/1.0 以上] サーバーへリソースを送信した */
    const POST    = 'POST';
    
    /** [HTTP/1.0 以上] ヘッダフィールドのみを返すようにサーバーに要求する */
    const HEAD    = 'HEAD';
    
    /** [HTTP/1.0 以上] サーバーの対象リソースをクライアントから送信されたものに置き換える */
    const PUT     = 'PUT';
    
    /** [HTTP/1.0 以上] サーバーから対象リソースを削除する */
    const DELETE  = 'DELETE';
    
    /** [HTTP/1.1 以上] サーバーで利用可能なメソッドの一覧を取得する */
    const OPTIONS = 'OPTIONS';
    
    /** [HTTP/1.1 以上] サーバーに対するリクエストを追跡する */
    const TRACE   = 'TRACE';
    
    /** [HTTP/1.1 以上] プロキシサーバーをトンネル接続することを要求する */
    const CONNECT = 'CONNECT';
    
    /** [HTTP/1.1 以上] サーバーの対象リソースの一部をクライアントから送信されたものに置き換える */
    const PATCH   = 'PATCH';
}
