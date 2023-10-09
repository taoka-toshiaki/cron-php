# cron（クロン）先でcron（クロン）一元管理
### 制作経緯
- レンタルサーバー等のcronの制約を打破したいなと思った事があり制作に至る。  
尚、レンタルサーバーでは負荷がある処理はお断りしている場合があるので規約に沿って使用ください。  
# 使用方法
- cron.jsonに処理日時、実行コマンドを明記する。
- class_cron.phpに一分間隔で呼び出すようにcronに登録する。
- class_cron.phpを呼び出す際は参照するjsonファイルのパラメーターを渡す事。
- cron.jsonの週の項目は日曜日から始まり、フラグが１の時に起動する。
- cron.jsonの週以外の項目の設定はcrontabの入力方法と同じ関係にあります。
   
### 設置例
crontab 一分間隔
```bash
*/1 * * * * taoka cd /var/www;/usr/bin/php class_cron.php cron.json
```

cron.json 
```json
[
  {
          "m":"*",
          "d":"*",
          "H":"9,2,15",
          "i":"*/5",
          "w":[1,1,1,1,1,1,1],
          "command":"cd /var/www/html/example.com/lib;/usr/bin/php tw_news.php example"
  }
]
```
