# Mattermost API in PHP

PHPでMattermost APIをコールして色々操作するやつ。

## Feature

- カスタム絵文字の一括登録スクリプト
    - imagesディレクトリ配下に格納された画像ファイルをMattermostのカスタム絵文字として登録する。  
      カスタム絵文字名はファイル名（拡張子を除く）で登録される。
    - 前提条件
        - Constants.php にシステム管理者のロールを持つユーザー名、パスワードが設定されていること
        - images ディレクトリ配下にカスタム絵文字が格納されていること

## Requirement

- \>= PHP 8.0

## Usage

- config/Constants.php
    ```php
    // システム管理者のロールを持つユーザーを指定する
    const username = 'XXXXXXXXXX';
    const password = 'XXXXXXXXXX';

    // Mattermost のエンドポイント
    const baseApiUrl = 'https://your-mattermost-url.com/api/v4/';
    ```

- cmd
    ```sh
    php bulk-emoji-import.php 
    ```

## Note

[Mattermost API Reference](https://api.mattermost.com/)
