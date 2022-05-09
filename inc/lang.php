<?php

$_validlangs = array_map(function($i) {
  return basename($i, ".txt");
}, glob(__DIR__ . "/../str/*.txt"));

$prefLocales = array_reduce(
  explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']),
  function ($res, $el) {
    list($l, $q) = array_merge(explode(';q=', $el), [1]);
    $res[$l] = (float)$q;
    return $res;
  },
  []
);
arsort($prefLocales);
$prefLocales = array_filter($prefLocales, function($k) use ($_validlangs) {
  return in_array($k, $_validlangs);
}, ARRAY_FILTER_USE_KEY);

$_currentlang = array_keys($prefLocales)[0] ?? "de";
$_currentlangfile = getlangfile();

if (!empty($_GET["lang"])) {
  setlang($_GET["lang"]);
}

function getcurrentlocale() {
  global $_currentlang;
  return $_currentlang;
}

function setlang($lang) {
  global $_currentlang, $_currentlangfile, $_validlangs;
  if (!in_array($lang, $_validlangs)) {
    //echo '<div class="scriptwarning" style="background:pink;color:maroon;border:2px solid maroon;padding:8px;margin:8px;font:14pt sans-serif"><strong>WARNING:</strong> Specified language is invalid</div>';
    return;
  }
  $_currentlang = $lang;
  $_currentlangfile = getlangfile();
}

function getlangfile() {
  global $_currentlang;
  $f = file_get_contents(__DIR__ . "/../str/$_currentlang.txt");
  $lns = explode("\n", $f);
  $data = array_map(function($i) {
    return explode("=", $i, 2);
  }, array_values(array_filter($lns, function($i) {
    return trim($i) !== "";
  })));
  $data = array_combine(array_column($data, 0), array_column($data, 1));
  return $data;
}

function i18nget($key, $vars = []) {
  global $_currentlangfile;
  $str = $_currentlangfile[$key] ?? null;
  if ($str === null)
    return null; //"{{{ $key }}}";
  
  $str = str_replace(array_map(function($i) {
    return "%{" . $i . "}";
  }, array_keys($vars)), array_values($vars), $str);
  
  $str = html_entity_decode($str);
  
  $str = htmlify_string($str);
  
  $str = str_replace(array_map(function($i) {
    return "%RAW{" . $i ."}";
  }, array_keys($vars)), array_values($vars), $str);
  
  return $str;
}

function langselect($pagename = "") {
  global $_validlangs, $_currentlang;
  $langoptions = array_map(function($l) use ($_currentlang, $pagename) {
    if ($l === $_currentlang)
      return '<span>' . htmlspecialchars(strtoupper($l)) . '</span>';
    else
      return '<a href="?lang=' . htmlspecialchars($l) . '&amp;page=' . htmlentities(rawurlencode($pagename)) . '">' . htmlspecialchars(strtoupper($l)) . '</a>';
  }, $_validlangs);
  return implode("\n", [
    '<div id="langselect">',
      file_get_contents(__DIR__ . "/../img/_inc_lang.svg"),
      ...$langoptions,
    '</div>'
  ]);
}

function htmlify_string($str) {
  $str = htmlentities($str);
  if (substr($str, 0, 5) === "RICH:") {
    $str = substr($str, 5);
    $str = preg_replace([
      '@//(.*?)//@',
      '@\*\*(.*?)\*\*@',
      '@__(.*?)__@',
      '@~~(.*?)~~@',
      '@&gt;&gt;(.*?)&lt;&lt;@',
      '@!!(.*?)!!@',
      '@\^\^(.*?)\^\^@',
      '@;;(.*?);;@',
    ], [
      '<i>$1</i>',
      '<b>$1</b>',
      '<u>$1</u>',
      '<s>$1</s>',
      '<ins>$1</ins>',
      '<mark>$1</mark>',
      '<sup>$1</sup>',
      '<sub>$1</sub>',
    ], $str);
  }
  return $str;
}
