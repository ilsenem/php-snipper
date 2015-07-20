# Snipper

Easy way to manage snippets from GitHub Gist service.

> Project is in development &mdash; only basic functionality is available

# Installation

```bash
composer global require snipper/snipper:@stable
```

# Usage

### Initialize application

You must provide GitHub personal access token to bypass API request limit. In current release no permissions required.

```bash
snipper init <token>
```

### Get snippet

Snipper search snippets only in your starred gists by hash tag as name of the snippet.

```bash
snipper get <snippet-name>
```

This command will try to get snippet with `#<snippet-name>` in **description** of the gist.

## LICENSE

Copyright (c) 2015 Ilya Nemytchenko <ilya.nemytchenko@gmail.com>

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
