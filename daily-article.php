<?php
require "env-read.php";
function createLog($data){
    file_put_contents("logs.txt", "\n$data", FILE_APPEND);
}
createLog("--------------------------------------------------------");
createLog(date('Y-m-d h:i:sa'));
createLog("--------------------------------------------------------");
use Orhanerday\OpenAi\OpenAi;
if (isset($_GET['pass']) && $_GET['pass'] == getenv("DAILY_PASS")){
    $url = "https://wiadomosci.gazeta.pl/wiadomosci/0,114884.html";

    $html = file_get_contents($url);

    if ($html === false) {
        createLog("Nie udało się pobrać strony.");
    }

    $dom = new DOMDocument();
    @$dom->loadHTML($html);

    $xpath = new DOMXPath($dom);

    $query = "//li[@class='entry'][.//time[contains(text(), '".date('d.m.Y', strtotime('yesterday'))."')]]";

    $entries = $xpath->query($query);

    $to_search = [];
    foreach ($entries as $entry) {
        $links = $entry->getElementsByTagName('a');
        
        if ($links->length > 0) {
            $firstLink = $links->item(0);
            $href = $firstLink->getAttribute('href');
            $to_search[] = $href;
        }
    }
    require 'vendor/autoload.php';
    $articles = [];
    foreach ($to_search as $url){
        $doc = new DOMDocument();

        libxml_use_internal_errors(true);

        $doc->loadHTMLFile($url);

        $xpath = new DOMXPath($doc);

        $section = $xpath->query('//section[@class="main_wrapper"]')->item(0);

        $h1 = $xpath->query('.//h1', $section)->item(0);
        $art_paragraphs = $xpath->query('.//p[@class="art_paragraph"]', $section);

        if ($h1) {
            $article_title = $h1->textContent;
        } else {
            echo 'Nie znaleziono elementu <h1>';
        }

        $article_content="";
        foreach($art_paragraphs as $paragraph){
            $article_content .= $paragraph->textContent." ";
        }
        $articles[] = [$article_title, $article_content];
        //print_r($articles);

        libxml_clear_errors();
    }
    require __DIR__ . '/vendor/autoload.php';

    $open_ai = new OpenAi(getenv("CHATGPT_API_KEY"));

    $message = 'wczuj się w rolę dziennikarza i bazując na podanych artykółach napisz krótki opis tego co wydarzyło się w polityce ostatniego dnia, poruszone tematy oddziel podwójnym enterem:';
    foreach($articles as $article){
        if(strlen($message.$article[0].$article[1]) < 36000){
            $message .= "article title: ".$article[0]."article content: ".$article[1];
        }
    }
    createLog("message length: ".strlen($message));

    $chat = $open_ai->chat([
    'model' => 'gpt-3.5-turbo-16k',
    'messages' => [
        [
            "role" => "user",
            "content" => $message,
        ],
    ],
    'temperature' => 1.0,
    'max_tokens' => 4000,
    'frequency_penalty' => 0,
    'presence_penalty' => 0,
    ]);


    var_dump($chat);
    $d = json_decode($chat);
    $id = $d->id;
    $content = $d->choices[0]->message->content;
    $used_tokens = $d->usage->total_tokens;
    $content_to_logs = "\nid=$id \ncontent=$content \nused_tokens=$used_tokens";
    createLog($content_to_logs);
    require("db/db_connect.php");
    $date = date('Y-m-d', strtotime('yesterday')); // YYYY-MM-DD
    $date_with_hours = date('Y-m-d H:i:s', strtotime('yesterday')); // YYYY-MM-DD HH:MM:SS
    $thumbnail = "./media/img/" . "daily" . ".png";

    $insert = "INSERT INTO `articles`(`public`, `title`, `title_url`, `thumbnail`, `content`, `author`, `release_date`) VALUES (1, 'codzienna prasówka {$date}', 'codzienna-prasowka-{$date}', '$thumbnail', '$content', 'ChatGPT', '$date_with_hours')";

    if(!mysqli_query($db, $insert)){
        createLog(mysqli_error($db));
    };
    include("db/db_close.php");
}else{
    createLog("Wrong password");
}
?>