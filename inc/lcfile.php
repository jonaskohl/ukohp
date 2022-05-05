<?php

require_once __DIR__ . "/lang.php";

const LC_REG_I18N = '/@\((.*?)\)(\{.*?\})?@/s';

function lcfile_get_lcfiles() {
  return array_map(function($i) {
    return basename($i, ".lc");
  }, glob(__DIR__ . "/../content/*.lc"));
}

function lcfile_is_valid_lcfile($name) {
  return in_array($name, lcfile_get_lcfiles());
}

function lcfile_get($name) {
  if (!lcfile_is_valid_lcfile($name))
    return '<strong>[ERROR]</strong> Invalid content file: ' . htmlentities($name) . '<br>';
  
  return file_get_contents(realpath(__DIR__ . "/../content/" . $name . ".lc"));
}

function lcfile_evaluate($contents, $context = []) {
  $contents = preg_replace_callback(LC_REG_I18N, function($m) {
    $innercontext = [];
    if (!empty($m[2])) {
      $innercontext = json_decode($m[2], true) ?? [];
      $innercontext = array_map("lcfile_evaluate", $innercontext);
    }
    
    return i18nget($m[1], $innercontext);
  }, $contents);
  
  $contents = str_replace(array_map(function($i) {
    return "%{" . $i . "}";
  }, array_keys($context)), array_map("htmlentities", array_values($context)), $contents);
  
  $contents = str_replace(array_map(function($i) {
    return "%URL{" . $i . "}";
  }, array_keys($context)), array_map("htmlentities", array_map("rawurlencode", array_values($context))), $contents);
  
  $contents = str_replace(array_map(function($i) {
    return "%RAWURL{" . $i . "}";
  }, array_keys($context)), array_map("rawurlencode", array_values($context)), $contents);
  
  $contents = str_replace(array_map(function($i) {
    return "%RAW{" . $i ."}";
  }, array_keys($context)), array_values($context), $contents);
  
  return $contents;
}
