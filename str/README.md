# Language files

Language (`*.txt`) files live inside this directory.

Each entry lives on a seperate line. Key and value are seperated by `=`. The value may contain additional `=` characters. Empty lines (and those only containing whitespace) will be ignored.

You can use `%{VARNAME}` to create an entry point for a dynamic value.

## Sample

```
index/title=Sample
index/text=This is some sample text
index/textwithlink=You can visit %{link}.
index/textwithlink/link=this cool page
```
