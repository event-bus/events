<?php

use Aztech\Events\Util\Trie\Trie;

require_once __DIR__ . '/../../vendor/autoload.php';

$category = 'cat.sub.test';
$filter = '#.mismatch';

echo 'Checking ' . $category . ' against filter ' . $filter . PHP_EOL;

$trie = new Trie($filter);

if ($trie->matches($category)) {
    echo 'Match found ! Yeah !' . PHP_EOL;
}
else {
    echo 'No match.' . PHP_EOL;
}

echo PHP_EOL;
