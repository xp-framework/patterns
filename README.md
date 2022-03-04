Pattern matching
================

[![Build status on GitHub](https://github.com/xp-framework/patterns/workflows/Tests/badge.svg)](https://github.com/xp-framework/patterns/actions)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.svg)](http://php.net/)
[![Supports PHP 8.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-8_0plus.svg)](http://php.net/)
[![Latest Stable Version](https://poser.pugx.org/xp-framework/patterns/version.png)](https://packagist.org/packages/xp-framework/patterns)

Regular expressions and text scanning

Example
-------
```php
use text\regex\Pattern;

$pattern= Pattern::compile('([w]{3}\.)?example\.(com|net|org)');
if ($pattern->matches($input)) {
  // Looks like an example domain
}

$result= $pattern->match($input);
$group= $result->group(0);  // [ "www.example.com", "www.", "www", "com" ]
```

Further reading
---------------
* [RFC #165: New text.regex package](https://github.com/xp-framework/rfc/issues/165)