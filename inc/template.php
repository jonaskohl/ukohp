<?php

require_once __DIR__ . "/lang.php";
require_once __DIR__ . "/util.php";
require_once __DIR__ . "/lcfile.php";

function template_get_templates() {
  return array_map(function($i) {
    return basename($i, ".t");
  }, glob(__DIR__ . "/../template/*.t"));
}

function template_is_valid_template($template) {
  return in_array($template, template_get_templates());
}

function template_evaluate($name, $context = []) {
  if (!template_is_valid_template($name))
    return '<strong>[ERROR]</strong> Invalid template: ' . htmlentities($name) . '<br>';
  
  $filename = realpath(__DIR__ . "/../template/" . $name . ".t");
  
  $contents = file_get_contents($filename);
  
  $context["locale"] = $context["locale"] ?? getcurrentlocale();
  $context["internal:lastmod"] = footer_lastmod($filename);
  $context["internal:langselect"] = langselect($context["page"] ?? "");
  
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
  
  $contents = lcfile_evaluate($contents, $context);
  
  return $contents;
}
