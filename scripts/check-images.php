<?php
foreach (glob(__DIR__.'/../public/images/products/*.jpg') as $f) {
    $i = @getimagesize($f);
    echo basename($f).' '.($i ? "{$i[0]}x{$i[1]} {$i['mime']}" : 'INVALID')."\n";
}
