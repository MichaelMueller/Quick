# Quick

A small, dependency-free application framework for PHP 7+.

Quick provides the plumbing that most small PHP tools and websites need — request
abstraction, routing to "app functions", HTTP responses, errors and logging — without
pulling in a full-stack framework. The same application code runs both behind a web
server and from the command line.

- Package: `michaelmueller/quick`
- Namespace: `Qck\` (PSR-4, mapped to `src/`)
- Requires: PHP >= 7.1
- License: Apache-2.0 (see `LICENSE.txt`)

## Installation

```bash
composer require michaelmueller/quick
```

## Quick start

An application is an `App` plus one or more *app functions* — classes implementing
`Qck\AppFunction`. `App::run()` picks the app function matching the current route and
invokes it.

```php
<?php
require_once __DIR__ . '/../vendor/autoload.php';

class HelloWorld implements \Qck\AppFunction
{
    public function run(\Qck\App $app)
    {
        $content = sprintf("Hello World. My name is %s.", $app->name());
        if ($app->request()->isHttpRequest())
            $content .= sprintf(" Your IP: %s", $app->request()->httpRequest()->ipAddress()->value());

        \Qck\HttpResponse::new()->createContent($content)->response()->send();
    }
}

\Qck\App::new("Demo App", HelloWorld::class)->setShowErrors(true)->run();
```

A runnable version of this lives in `src/public_html/index.php`:

```bash
php -S localhost:8000 -t src/public_html
```

The same file also works when executed directly (`php src/public_html/index.php`) —
the request abstraction detects the CLI environment and skips the HTTP-specific parts.

## Concepts

### App and routing

`App` is the entry point and a tiny service locator. It is constructed with an
application name and the fully qualified class name of the default app function, which
is also registered as the first route.

```php
\Qck\App::new("My App", Home::class)
    ->addRoute(Contact::class)             // route name defaults to the short class name
    ->addRoute(Legal::class, "imprint")    // explicit route name
    ->addAppFunctionNamespace("My\\App\\Functions") // resolve routes by convention
    ->setRouteParamName("q")               // default is "q"
    ->run();
```

Route resolution in `App::run()`:

1. The route name is read from the request parameter named by `routeParamName()`
   (default `q`). If absent, the first registered route — the default app function —
   is used.
2. The name is validated against a PHP-identifier pattern; anything else is rejected
   with HTTP 404.
3. Explicitly registered routes win. Otherwise each namespace added via
   `addAppFunctionNamespace()` is tried as `<namespace>\<route>` until a class exists.
4. The resolved class must exist and implement `Qck\AppFunction`, otherwise a
   `Qck\Exception` with HTTP status 404 is thrown.

`buildUrl($routeName, array $queryData = [])` builds a link back to a route, merging
the route parameter into the given query data.

Accessors available to app functions: `name()`, `request()`, `log()`, `httpResponse()`,
`routes()`, `currentRoute()`, `routeParamName()`.

### Requests

`RequestFactory` decides at runtime what kind of request is being served: if
`$_SERVER["argv"]` is missing (or not an array), it creates an `HttpRequest`, otherwise
a plain CLI `Request`.

`Request` exposes a uniform parameter bag regardless of origin:

```php
$request->has("id");
$request->get("id", $default);
$request->args();          // all parameters
$request->isHttpRequest(); // false for CLI
$request->httpRequest();   // null for CLI, $this for HttpRequest
```

For HTTP requests the parameters are `$_COOKIE`, `$_GET` and `$_POST` merged in that
order (later sources win); for CLI they are parsed with `parse_str()` from the first
command-line argument, so a script is called as:

```bash
php app.php "q=home&id=42"
```

`HttpRequest` adds `ipAddress()`, returning an `IpAddress`.

Parameters can also be injected programmatically — useful for tests and for
sub-requests. They take precedence over anything from the environment:

```php
$app->setUserArgs(["who" => "world"]);   // forwarded to the underlying Request
$request->setUserArgs(["who" => "world"]);
```

### IpAddress

`IpAddress` resolves the client address from `HTTP_CLIENT_IP`, then
`HTTP_X_FORWARDED_FOR`, then `REMOTE_ADDR`, and is stringable. Validation is opt-in:

```php
$ip = $request->httpRequest()->ipAddress()
    ->setValidationFlags(FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
    ->value();
```

Note that the first two headers are client-controlled; only trust them behind a proxy
that overwrites them.

### Responses

`HttpResponse` carries a status code and one `HttpContent` body. `HttpContent` holds a
string or any `Snippet`, plus a content type and charset, and offers constants for the
common ones (`CONTENT_TYPE_APPLICATION_JSON`, `CHARSET_UTF_8`, …). Status codes are
available as `HttpResponse::EXIT_CODE_*` constants.

```php
\Qck\HttpResponse::new()
    ->setReturnCode(\Qck\HttpResponse::EXIT_CODE_OK)
    ->createContent(json_encode($data))
    ->setContentType(\Qck\HttpContent::CONTENT_TYPE_APPLICATION_JSON)
    ->response()
    ->send();
```

`createContent()` returns the content, and `HttpContent::response()` returns the
response — hence the round trip before `send()`.

### Snippet

`Snippet` is the one-method interface for renderable content:
`toString($indent = null, $level = 0)`. `HttpContent` implements it, and any object
implementing it can be used as a response body.

### Errors and exceptions

`Qck\Exception` extends `\Exception` and collects multiple `Error` objects, each
optionally tied to an input key, instead of a single message string:

```php
\Qck\Exception::new()
    ->setHttpReturnCode(\Qck\HttpResponse::EXIT_CODE_UNPROCESSABLE_ENTITY)
    ->argError("Invalid value '%s'", "email", $value)  // sprintf args after the key
    ->error("Some other problem")
    ->throw();   // only throws if at least one error was collected
```

`error()`/`argError()` are chainable, `throw()` is a no-op while the error list is
empty, and `assert($condition, $error)` adds an error and throws when the condition is
false. `hasErrors()`, `errors()`, `httpReturnCode()` and `returnCode()` read the state
back.

`ErrorHandler` is installed automatically by the `App` constructor. It turns PHP errors
into `\ErrorException`s, sets `error_reporting(E_ALL)`, and for uncaught exceptions
clears the output buffer and sends the exception's HTTP status before rethrowing.
Diagnostics are off by default; `App::setShowErrors(true)` enables error display and
logging — keep it off in production.

Call `uninstall()` to put the previously active handlers back. It has to be explicit:
while installed, PHP holds a reference to the handler object, so the destructor does
not run before shutdown.

### Logging

`Log` is a topic-filtered logger. A message is only emitted if one of its topics has
been registered via `addTopic()`; `LogMessage::ALL` is added to every message, so
`addTopic(LogMessage::ALL)` turns everything on.

```php
$log = $app->log()->addTopic(\Qck\LogMessage::ALL);
$log->info("Processing file %s")->addArg($filename)->send();
$log->warn("Skipping %s")->addArg($filename)->send();
$log->error("Failed")->send();
```

Messages are built lazily: `info()`/`warn()`/`error()` return a `LogMessage` that is
only dispatched on `send()`. Each message is prefixed with timestamp, source
file:line and (optionally) topics — toggle with `setShowDateTime()`, `setShowFile()`,
`setShowTopics()` or `disableAdditionalInformation()`. Besides the severity, the
calling class name is added as a topic, so logging can be narrowed to one class.
`ERROR` messages go to `STDERR` on CLI; everything else is printed.

### Cmd

`Cmd` wraps `exec()` for running system commands and returns a `CmdOutput`:

```php
$out = \Qck\Cmd::new("git")->arg("log")->escapeArg($path)->run();
if ($out->successful())
    echo $out->output();
```

Use `escapeArg()` for anything derived from user input; `arg()` is passed through
verbatim.

### ComposerCodeBundler

`ComposerCodeBundler` walks all PSR-4 prefixes registered with the Composer autoloader
and concatenates the class files into a single PHP file — useful for deploying to
hosts where uploading a `vendor` tree is inconvenient. Files without a matching PSR-4
class, and files added via `addExcludedFile()`, are skipped; `addPhpExtension()` widens
the set of processed extensions beyond `php`.

`src/cmd/bundleCode.php` is a ready-made CLI app around it. Run it from a project that
depends on Quick:

```bash
php vendor/michaelmueller/quick/src/cmd/bundleCode.php
```

It writes `srcBundle/<projectName>.php` in the project root, creating the directory if
needed. Note that it bundles *every* registered PSR-4 prefix, so the output includes
the dependencies' classes as well.

## Repository layout

```
src/
  App.php                  application, service locator and router
  AppFunction.php          interface implemented by routable actions
  Request.php              CLI/base request, parameter bag
  HttpRequest.php          HTTP request, adds ipAddress()
  RequestFactory.php       picks HttpRequest vs. Request
  IpAddress.php            client IP resolution and validation
  HttpResponse.php         status code, headers, send()
  HttpContent.php          body, content type, charset
  Snippet.php              renderable-content interface
  Exception.php            multi-error exception
  Error.php                single error, optionally tied to an input key
  ErrorHandler.php         error/exception handler installed by App
  Log.php, LogMessage.php  topic-filtered logging
  Cmd.php, CmdOutput.php   system command execution
  ComposerCodeBundler.php  bundle PSR-4 sources into one file
  cmd/bundleCode.php       CLI entry point for the bundler
  public_html/index.php    "Hello World" demo app
pull_push.sh               commit/pull/push helper used for checkpoint commits
```

## Status

Early-stage and evolving; the API is not stable and there is no test suite yet.
Known limitations: `Log` has no file or channel backend (messages go to stdout, errors
to stderr on CLI), CLI parameters are read only from a single query-string style
argument, and `HttpResponse` carries exactly one content body.
