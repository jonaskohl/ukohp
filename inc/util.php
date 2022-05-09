<?php

require_once __DIR__ . "/lang.php";

function footer_lastmod($file) {
  echo "<!--";var_dump($file);echo "-->";
  $datefmt = datefmt_create(
    i18nget("generic/lastmod/dtlang"),
    IntlDateFormatter::FULL,
    IntlDateFormatter::FULL,
    'Europe/Berlin',
    IntlDateFormatter::GREGORIAN,
    i18nget("generic/lastmod/fmt")
  );
  $modtime = filemtime($file);
  return i18nget("generic/lastmod", [
    "date" => datefmt_format($datefmt, $modtime),
    "en_ordinal" => date("S", $modtime)
  ]);
}
