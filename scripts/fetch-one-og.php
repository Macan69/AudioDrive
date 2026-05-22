<?php
$url = $argv[1] ?? exit(1);
$ctx = stream_context_create(['http' => ['timeout' => 20, 'header' => "User-Agent: Mozilla/5.0\r\n"]]);
$html = @file_get_contents($url, false, $ctx);
if ($html && preg_match('/og:image" content="(https:\/\/www\.loudsound\.ru\/upload\/iblock[^"]+\.jpg)"/i', $html, $m)) {
    echo $m[1];
}
