# Content files

Content (`*.lc`) files live inside this directory.

You can use `%{VARNAME}` to insert a context variable and `@(I18NKEY)@` to insert a localized string. For details, see `template/README.md`. To overwrite a context variable, use the `ctxset` command. You can use constants or localized strings as values.

## Sample

```HTML
!!ctxset "mykey" My constant value
!!ctxset "title" @(my/title/key)@
<p>@(index/title)@</p>
<p>@(index/text)</p>
<p>@(index/textwithlink){
  "link": "<a href='myurl'>@(index/textwithlink/link)@</a>"
}@</p>
```
