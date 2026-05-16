# ApiToken プラグイン for EC-CUBE 4

EC-CUBE 4 管理画面から Web API 用のパーソナルアクセストークンを生成するプラグインです。

## 概要

[Api42（Web API for EC-CUBE4）](https://github.com/EC-CUBE/api42) と連携し、OAuth2 クライアントごとにアクセストークン・リフレッシュトークンをワンクリックで生成できます。外部アプリケーションや自動化スクリプトから EC-CUBE の Web API を利用する際のトークン取得を簡略化します。

## 必要要件

| 項目 | バージョン |
|------|-----------|
| EC-CUBE | 4.3.x |
| PHP | 8.1 / 8.2 / 8.3 |
| Api42 プラグイン | ^4.3（必須・事前に有効化が必要） |

## インストール

### Composer 経由

```bash
composer require ec-cube/apitoken
bin/console eccube:plugin:enable --code ApiToken
```

### 管理画面から

1. 管理画面 > オーナーズストア > プラグインからインストール
2. Api42 プラグインを先に有効化する
3. ApiToken プラグインを有効化する

> **注意**: Api42 が有効化されていない状態で ApiToken を有効化しようとするとエラーになります。

## 使い方

1. 管理画面 > **設定 > API > トークン生成** を開く
2. OAuth2 クライアント一覧から対象のクライアントを選択
3. **トークン生成** ボタンをクリック
4. 表示されたアクセストークン・リフレッシュトークンをコピーして保存する

> **注意**: トークンはこの画面を閉じると再表示されません。必ず安全な場所に保存してください。

### トークン生成の前提条件

クライアントに以下が設定されていない場合、トークン生成ボタンは無効になります。

- `authorization_code` グラントが設定されていること
- リダイレクト URI が設定されていること

## 機能

- OAuth2 クライアント一覧の表示（クライアント ID・グラント・スコープ）
- アクセストークン（JWT）とリフレッシュトークンのワンクリック生成
- CSRF 保護
- クリップボードへのコピー機能

## 開発

### テスト実行

```bash
bin/phpunit app/Plugin/ApiToken/Tests
```

### 静的解析

```bash
vendor/bin/phpstan analyse app/Plugin/ApiToken --configuration=app/Plugin/ApiToken/phpstan.neon
```

### コードスタイル

```bash
vendor/bin/php-cs-fixer fix app/Plugin/ApiToken --config=app/Plugin/ApiToken/.php-cs-fixer.dist.php
```

## ライセンス

[LGPL-2.1](LICENSE)
