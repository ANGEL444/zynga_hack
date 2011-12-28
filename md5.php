<?php

$key = $argv[1];

echo "\nKEY  : ".$key;
echo "\nMD5  : ".md5($key);
echo "\nSHA1 : ".sha1($key);
echo "\nCRC32: ".crc32($key);
echo "\n\n";

?>
