# Content files

Content (`*.lc`) files live inside this directory.

You can use `%{VARNAME}` to insert a context variable and `@(I18NKEY)@` to insert a localized string. For details, see `template/README.md`.

## Sample

```HTML
<p>@(index/title)@</p>
<p>@(index/text)</p>
<p>@(index/textwithlink){
  "link": "<a href='myurl'>@(index/textwithlink/link)@</a>"
}@</p>
```
