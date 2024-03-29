Pattern matching changelog
==========================

## ?.?.? / ????-??-??

## 9.1.0 / 2022-03-04

* Made compatible with XP 11, see xp-framework/core#300 - @thekid

## 9.0.0 / 2020-04-11

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  . Rewrote code base, grouping use statements
  (@thekid)

## 8.0.2 / 2020-04-05

* Fix "Undefined variable $cmp" when comparing `MatchResult` instances
  (@thekid)

## 8.0.1 / 2020-04-04

* Made compatible with XP 10 - @thekid

## 8.0.0 / 2017-09-24

* **Heads up**: Dropped PHP 5.5 support - @thekid
* Added compatibility with XP 9.0+ - @thekid

## 7.1.1 / 2017-01-16

* Added BC for PHP 7.2: Empty optional patterns should not be NULLed
  See https://bugs.php.net/bug.php?id=73947
  (@thekid) 

## 7.1.0 / 2016-08-29

* Added version compatibility with XP 8 - @thekid

## 7.0.0 / 2016-02-22

* **Adopted semantic versioning. See xp-framework/rfc#300** - @thekid 
* Added version compatibility with XP 7 - @thekid

## 6.6.0 / 2014-12-08

* Extracted from the XP Framework's core - @thekid