Pattern matching
================

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-framework/patterns.svg)](http://travis-ci.org/xp-framework/patterns)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Requires PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
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