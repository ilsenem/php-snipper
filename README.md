# Snipper

Easy way to manage snippets from GitHub Gist service.

> Project is in development &mdash; only basic functionality is available

Snipper offers easy way to manage your code snippets directly from GitHub Gist service.
Create gist with `#<name>` in description (any part of description) and star it. Then
use your gist as code snippet by it's name without hash sign.

## Installation

```bash
composer global require snipper/snipper:@stable
```

## Usage

### Initialize application

You must provide GitHub personal access token to bypass API request limit. In current release no permissions required.

```bash
snipper init <token>
```

### Get snippet

Snipper search snippets only in your starred gists by hash tag as name of the snippet.

If there are several snippets with same name then Snipper ask you which one to use.

```bash
snipper get <snippet-name> # without hash sign
```

This command will try to get snippet with `#<snippet-name>` in **description** of the gist.

## LICENSE

[WTFPL](http://www.wtfpl.net/)
