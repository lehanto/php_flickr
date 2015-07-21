<?php

// Flickr APIKEY
define (APIKEY, "075c28cfad441cd802ce30cf808e611e");

// 検索ワード
$searchWord = "東京タワー";

// サムネイルサイズ(s.t.m.z.bよりサイズを設定)
$pictureSize = "b";

// XMLデータ取得用のベースURL
$url = "https://www.flickr.com/services/rest/?"
     . "method=flickr.photos.search"
     . "&format=rest"
     . "&api_key=".APIKEY
     . "&per_page=20"
     . "&license=0,1,2,3,4,5,6"
     . "&extras=owner_name"
     . "&text=".urlencode($searchWord);

// file_get_contentsの代わりにcURLを使用
// 初期化
$session = curl_init();
// アクセスするURL
curl_setopt($session, CURLOPT_URL, $url);
// ヘッダ文字列の出力
curl_setopt($session, CURLOPT_HEADER, false);
// curl_execの返り値を文字列にする
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
// 実行
$url = curl_exec($session);
// 終了
curl_close($session);

// XML文字列をパースしオブジェクトを取得
$xml = simplexml_load_string($url)
 or die("XMLパースエラー");

// ループ処理
$iLoop = 0;
echo "<table>";
foreach($xml->photos->photo as $photoValue){
    if( $iLoop == 0){
	    echo "<tr><td>";
    } else {
	    echo "<td>";
    }
    $farmId = $photoValue['farm'];
    $serverId = $photoValue['server'];
    $owner = $photoValue['owner'];
    $ownername = $photoValue['ownername'];
    $photoId = $photoValue['id'];
    $secret = $photoValue['secret'];
    $title = $photoValue['title'];
    $url = "https://farm".$farmId.".staticflickr.com/".$serverId."/".$photoId."_".$secret.".jpg";
    // Lightboxで表示(jQuery版)
    echo "<a href=\"".$url."\" title=\"Photo：&lt;a target=&quot;_blank&quot; href=&quot;https://www.flickr.com/photos/".$owner."/".$photoId."&quot;&gt;".$title." By ".$ownername."&lt;/a&gt;&lt;br /&gt;Powered By Flickr Service&lt;br /&gt;※テキストリンクよりFlickrの元ページへ移動します。\" class=\"lightbox\"><img src=\"https://farm{$farmId}.staticflickr.com/{$serverId}/{$photoId}_{$secret}_{$pictureSize}.jpg\" alt=\"Photo：".$title." By ".$ownername."\"></a>";
    $iLoop++;
    // 画像の列数を指定
    if( $iLoop == 4){
	    echo "</td></tr>";
	    $iLoop = 0;
    } else {
	    echo "</td>";
    }
}
if( $iLoop != 0 ){
	echo "</tr>";
}
echo "</table>\n";

?>
