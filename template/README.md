# Template files

Template (`*.t`) files live inside this directory.

You can use `%{VARNAME}` to insert a context variable and `@(I18NKEY)@` to insert a localized string.

## Variable formats

In addition to `%{VARNAME}` you can also change the format the variable is inserted as. No modifier means standard HTML escaping.

|Expression        |Meaning                             |
|------------------|------------------------------------|
|`%RAW{VARNAME}`   |No HTML escaping, print value as-is.|
|`%URL{VARNAME}`   |HTML and URL escaping.              |
|`%RAWURL{VARNAME}`|URL escaping only.                  |

## I18N formats

Localization strings also allow `%{VARNAME}` interpolation. To provide these values to the I18N engine, you can use the following syntax:

```
@(I18NKEY){<JSON>}@
```

## Sample

```HTML
<!doctype html>
<html lang="%{locale}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>%{title}</title>
    <link rel="stylesheet" href="/~jkohl/css/main.css">
  </head>
  <body>
    <div id="wrapper">
      %RAW{internal:langselect}
      <h1>%{title}</h1>
      %RAW{content}
    </div>
  </body>
</html>
```
