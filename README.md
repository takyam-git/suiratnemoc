#Suiratnemoc
* FuelPHPで作ってる日報用ツール

##インストール
* まだ出来上がってないけどな！
* cloneのオプションいつも忘れるのでメモメモ
* $ git clone --recursive git://github.com/takyam-git/suiratnemoc.git
* $ php oil refine install
* $ cd fuel/app/config/development
* $ mysql -u **** -p
 * CREATE DATABASE `mydbname`;
 * USE suiratnemoc;
 * GRANT ALL ON `mydbname`.* TO 'mydbuser'@'localhost' IDENTIFIED BY '**********';
* $ cp db.php.sample db.php
 * vi db.php
* $ php oil refine migrate

###nginx conf sample
      1 server {
      2     listen 80;
      3     server_name  local.suiratnemoc;
      4
      5     server_tokens off;
      6     gzip on;
      7     autoindex on;
      8
      9     access_log /path/to/suiratnemoc/logs/access.log;
     10     error_log /path/to/suiratnemoc/logs/error.log notice;
     11
     12     rewrite_log on;
     13
     14     charset utf-8;
     15
     16     root /path/to/suiratnemoc/public;
     17     index index.php;
     18
     19     location / {
     20         try_files $uri /index.php?$uri$args;
     21     }
     22
     23     location ~ .*\.php$ {
     24         fastcgi_pass  127.0.0.1:9000;
     25         fastcgi_param SCRIPT_FILENAME  $document_root$fastcgi_script_name;
     26         #fastcgi_param FUEL_ENV production;
     27         include fastcgi_params;
     28     }
     29
     30     location ~ /\. {
     31         access_log off;
     32         log_not_found off;
     33         deny all;
     34     }
     35 }
     36


##TODO
* カテゴリの作成
 * +ユーザーカテゴリとグローバルカテゴリを持つ+
  * +ユーザーカテゴリはそのユーザーだけのカテゴリ+
  * +グローバルカテゴリは全体に対して持たせるカテゴリ+
  * +管理者が必要最低限のカテゴリを設定し、各ユーザーはそれで足りない分を自分専用のカテゴリとして作るイメージ+
 * +UIは何となくできたのでアプリとの連携+
  * +ローカル/グローバルカテゴリ追加時の保存処理+
  * +ローカル/グローバルカテゴリ編集時の保存処理+
  * +お気に入りに追加した時/お気に入りを並び替えた時の保存処理+
   * +これは10秒に１回程度の間隔でsetIntervalして、変更が発生していたら保存する感じ+
    * +autosave.coffeeで実装+
* カレンダーページ
 * +カテゴリ情報を連携+
* サマリーの作成
 * イベントのテーブルビュー
 * CSVによる一定期間のイベント一覧の出力
 * カテゴリ毎の時間合計の出力
 * CSVによる一定期間のカテゴリ別の時間合計の出力
* 日報メール機能の作成
 * 選んだ日付のイベントを元にメールを作成・送信する
 * テンプレート（件名・本文・To,cc,bcc）設定が可能
* 他のユーザーのスケジュールを見れる権限
 * Readonly権限で見れるようにする
 * 各ユーザーの設定で、どの範囲で公開するか決定する
 * とはいえ管理者ユーザーグループは見れるようにする
* プロフィール変更機能
 * パスワードの変更が出来るように
 * メールアドレスの変更出来るように
* 新規登録時メールで通知する
 * メール中のリンククリック→認証　つける？
* CSRF対策
 * Token埋め込んでないから諸々埋め込む
* トップページの作成
 * 別にいらない気はする
* ウェブサイトの作成
 * マニュアルとか
* icalの読み込みに対応
 * 現状の仕様では時間の重複が無いようになってる（UI的に）
 * 既に10:00-11:00にイベントが登録されていた場合に10:30-11:30等の重複するイベントが来た場合にどうするか。