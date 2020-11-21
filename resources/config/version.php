<?php
require(__DIR__ .'/../../vendor/autoload.php');

use Carbon\Carbon;
$tag  = exec('git describe --tags --abbrev=0');
if(empty($tag)) {
    $tag = '-.-.-';
}
$hash = trim(exec('git log --pretty="%h" -n1 HEAD'));
$date = Carbon::parse(trim(exec('git log -n1 --pretty=%ci HEAD')));
return [
    'tag' => $tag,
    'date' => $date,
    'hash' => $hash,
    'string' => sprintf('%s-%s (%s)',$tag, $hash, $date->format('d/m/y H:i')),
];