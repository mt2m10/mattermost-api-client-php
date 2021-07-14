<?php

/**
 * カスタム絵文字一括登録スクリプト
 *
 * imagesディレクトリ配下に格納された画像ファイルをMattermostのカスタム絵文字として登録する。
 * カスタム絵文字名はファイル名（拡張子を除く）で登録される。
 *
 * 前提条件
 * - Constants.php にシステム管理者のロールを持つユーザー名、パスワードが設定されていること
 * - images ディレクトリ配下にカスタム絵文字が格納されていること
 */

declare(strict_types=1);

require_once __DIR__ . '/config/Constants.php';
require_once __DIR__ . '/util/Curl.php';
require_once __DIR__ . '/util/Image.php';
require_once __DIR__ . '/api/User.php';
require_once __DIR__ . '/api/Emoji.php';

$expectCount = 0;
$actualCount = 0;

try
{
    // ログイン
    $user   = new User(Constants::username, Constants::password);
    $login  = $user->login();
    if (isset($login['error']))
    {
        $err = $login['error'];
        echo "Failed login. [message: {$err['message']}, id: {$err['id']}]" . PHP_EOL;

        exit();
    }

    echo "Success login. [username: {$login['user']['username']}]" . PHP_EOL;

    // カスタム絵文字取得
    $images      = glob('images/*');
    $expectCount = count($images);

    // カスタム絵文字登録
    $emoji = new Emoji($login['user'], $login['token']);
    foreach ($images as $image)
    {
        $created = $emoji->create(__DIR__ . '/' . $image);
        if (isset($created['error']))
        {
            $err = $created['error'];
            echo "Failed emoji created. [filename: {$err['name']}, message: {$err['message']}, id: {$err['id']}]" . PHP_EOL;

            continue;
        }

        $actualCount++;
    }
}
catch (Exception $e)
{
    echo $e->getMessage() . PHP_EOL;
}
finally
{
    echo 'Number of images: ' . $expectCount . PHP_EOL;
    echo 'Number of images registered: ' . $actualCount . PHP_EOL;
}
