# PHP API MockServer

REST API の Mock サーバーを気軽に立てるための仕組みです。

以下のような役割があります。

- 管理ツール経由でAPIのリソースを登録しておけば、API Mock として使用できる
  - 例えば、`/v1/users` というリソースを `GET` でアクセスした際のレスポンスを管理ツールで登録しておけば、`GET /v1/users` でアクセスすると、登録したレスポンスが返却される
- API Mock のリソースを REST API 経由で登録することができるので、API アクセスが必要な機能に対する UnitTest 等で利用できる
  - REST API 経由で Mock リソースを追加するためのパッケージは別途準備中(php-api-mock というパッケージになる予定)

## インストール方法

### homebrew 等を使ってローカル環境で実行

github からソースをダウンロードし、composer で必要なパッケージを取得、設定ファイルをコピーする

```sh
% git clone git@github.com:necomori/php-api-mockserver
% cd php-api-mockserver
% composer install
% cp config/.env.default config/.env
```

`config/.env` の 14 行目 `export SECURITY_SALT="__SALT__"` の `__SALT__` 部分を編集する。

### Docker for Mac を利用する

github からソースをダウンロードし、composer で必要なパッケージを取得、設定ファイルをコピーする(初回は、Docker image の build が実行されるが、2回目以降は build 済みイメージを利用するので起動は早くなる)

```sh
% git clone git@github.com:necomori/php-api-mockserver
% cd php-api-mockserver
% docker-composer up -d
% docker exec -it web composer install
% cp config/.env.default config/.env
```

`config/.env` の 14 行目 `export SECURITY_SALT="__SALT__"` の `__SALT__` 部分を編集する。

## 管理ツールの実行方法

### homebrew 等を使ってローカル環境で実行

以下のコマンドでサーバーを起動する。

```sh
% cd php-api-mockserver
% bin/cake server -H 0.0.0.0
```

`http://localhost:8756/resources` にアクセスすると、リソース管理画面が表示されるので、Mockアクセスで必要なリソースを追加/編集を行う

（注意) サーバーは、上記のコマンドを Ctrl+ c で終了すれば停止する

### Docker for Mac を利用する

以下のコマンドで Docker 環境を起動する。

```sh
% cd php-api-mockserver
% docker-compose up -d
```

`http://localhost:8756/resources` にアクセスすると、リソース管理画面が表示されるので、Mockアクセスで必要なリソースを追加/編集を行う

(注意）Docker 環境は `docker-compose down` コマンドを実行すれば終了する

## REST API経由のリソース追加

上記の方法でサーバーを起動しておいて、以下の REST API 経由でリソースの編集が可能。

|メソッド|リソース|動作|
|---|---|---|
|GET|/mocks|登録済みの Mock リソースの一覧を返却|
|GET|/mocks/:id|指定した id の Mock リソースを返却|
|POST|/mocks|新しい Mock リソースを追加|
|PUT(PATCH)|/mocks/:id|指定した id の Mock リソースを更新|
|DELETE|/mocks/:id|指定した id の Mock リソースを削除|

## 登録した Mock リソースのアクセス方法(1)

管理ツールもしくは REST API 経由で登録した Mock リソースは、登録した URL にアクセスすれば、ダミーのレスポンスを返却する。

例えば、以下のような Mock リソースを登録した場合、`http://localhost:8756/v1/users` に GET メソッドでアクセスすれば、`{"message":"OK"}` が返却される。（HTTP のステータスコードは 200 になる)

|項目|値|
|---|---|
|URL|/v1/users|
|Request Method|GET|
|Response|{"code":200,"body":{"message":"OK"}|

## 登録した Mock リソースのアクセス方法(2)

Mock リソースに対して、複数のレスポンスを登録しておくと、1回目は 200 OK だが、2回目のアクセス時には 500 Error を返すということが可能。

例えば、以下のような Mock リソースを登録した場合、`http://localhost:8756/v1/users` に GET メソッドでアクセスすれば、1回目は `{"message":"OK"}` が返却が、2回目は `{"message":"Error"}` が返却される。

|項目|値|
|---|---|
|URL|/v1/users|
|Request Method|GET|
|Response|[{"code":200,"body":{"message":"OK"}}, {"code":500,"body":{"message":"Error"}}]|
