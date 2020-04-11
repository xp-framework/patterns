<?php namespace text\regex\unittest;

use lang\{FormatException, IndexOutOfBoundsException};
use text\regex\Pattern;
use unittest\TestCase;
use unittest\actions\RuntimeVersion;

/**
 * Pattern test 
 *
 * @see   http://www.regular-expressions.info/unicode.html
 */
class PatternTest extends TestCase {

  #[@test]
  public function length() {
    $this->assertEquals(
      0, 
      Pattern::compile('ABC')->match('123')->length()
    );
  }

  #[@test]
  public function isMatched() {
    $this->assertTrue(Pattern::compile('a+')->matches('aaa'));
  }

  #[@test]
  public function isNotMatched() {
    $this->assertFalse(Pattern::compile('a+')->matches('bbb'));
  }

  #[@test]
  public function stringPrimitiveInput() {
    $this->assertEquals(0, Pattern::compile('.')->match('')->length());
    $this->assertEquals(1, Pattern::compile('.')->match('a')->length());
    $this->assertEquals(2, Pattern::compile('.')->match('ab')->length());
  }

  #[@test]
  public function caseInsensitive() {
    $this->assertEquals(
      1, 
      Pattern::compile('a', Pattern::CASE_INSENSITIVE)->match('A')->length()
    );
  }

  #[@test]
  public function groups() {
    $this->assertEquals(
      [['Hello']],
      Pattern::compile('H[ea]llo')->match('Hello')->groups()
    );
  }

  #[@test]
  public function groupsWithOneMatch() {
    $this->assertEquals(
      [['www.example.com', 'www.', 'www', 'com']],
      Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com')->groups()
    );
  }

  #[@test]
  public function groupsWithMultipleMatches() {
    $this->assertEquals(
      [
        ['www.example.com', 'www.', 'www', 'com'],
        ['example.org', '', '', 'org']
      ],
      Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com and example.org')->groups()
    );
  }

  #[@test]
  public function group() {
    $this->assertEquals(
      ['Hello'],
      Pattern::compile('H[ea]llo')->match('Hello')->group(0)
    );
  }

  #[@test]
  public function groupWithOneMatch() {
    $this->assertEquals(
      ['www.example.com', 'www.', 'www', 'com'],
      Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com')->group(0)
    );
  }

  #[@test]
  public function groupWithMultipleMatches() {
    $match= Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com and example.org');
    $this->assertEquals(
      ['www.example.com', 'www.', 'www', 'com'],
      $match->group(0)
    );
    $this->assertEquals(
      ['example.org', '', '', 'org'],
      $match->group(1)
    );
  }

  #[@test, @expect(IndexOutOfBoundsException::class)]
  public function nonExistantGroup() {
    Pattern::compile('H[ea]llo')->match('Hello')->group(1);
  }

  #[@test]
  public function matchEmptyString() {
    $this->assertEquals([], Pattern::compile('.')->match('')->groups());
  }

  #[@test]
  public function equality() {
    $this->assertEquals(
      Pattern::compile('[a-z]+'),
      Pattern::compile('[a-z]+')
    );
  }

  #[@test]
  public function unequality() {
    $this->assertNotEquals(
      Pattern::compile('[a-z]+', Pattern::CASE_INSENSITIVE),
      Pattern::compile('[a-z]+')
    );
  }

  #[@test]
  public function stringRepresentation() {
    $this->assertEquals(
      'text.regex.Pattern</[a-z]+/i>',
      Pattern::compile('[a-z]+', Pattern::CASE_INSENSITIVE)->toString()
    );
  }

  #[@test, @expect(FormatException::class)]
  public function illegalPattern() {
    Pattern::compile('(');
  }

  #[@test]
  public function lazyCompilation() {
    $p= new Pattern('(');
    try {
      $p->matches('irrelevant');
      $this->fail('Expected exception not thrown', null, 'lang.FormatException');
    } catch (FormatException $expected) {
      // OK
    }
  }

  #[@test]
  public function multilineDotAll() {
    $m= Pattern::compile('BEGIN {(.+)}', Pattern::MULTILINE | Pattern::DOTALL)->match('BEGIN {
      print "Hello World";
    }');
    $this->assertEquals(1, $m->length());
    $group= $m->group(0);
    $this->assertEquals('print "Hello World";', trim($group[1]));
  }

  #[@test]
  public function replaceWhitespace() {
    $pattern= Pattern::compile('\s+');
    $this->assertEquals(
      'Hello World with far too much whitespace',
      $pattern->replaceWith(' ', 'Hello  World     with   far too    much whitespace')
    );
  }

  #[@test]
  public function replaceWithDollarBackReference() {
    $pattern= Pattern::compile('H[ae]ll[oO0]');
    $this->assertEquals(
      '[Hello] [Hall0]',
      $pattern->replaceWith('[$0]', 'Hello Hall0')
    );
  }

  #[@test]
  public function replaceWithDollarBackReferences() {
    $quoter= Pattern::compile('([^=]+)=([^ >]+)([ >]*)');
    $this->assertEquals(
      '<a href="http://example.com" title="Link">...</a>',
      $quoter->replaceWith('$1="$2"$3', '<a href=http://example.com title=Link>...</a>')
    );
  }

  #[@test]
  public function stringCast() {
    $this->assertEquals('/^begin/', (string)new Pattern('^begin'));
  }

  #[@test]
  public function stringCastWithFlag() {
    $this->assertEquals('/end$/i', (string)new Pattern('end$', Pattern::CASE_INSENSITIVE));
  }

  #[@test]
  public function stringCastWithFlags() {
    $this->assertEquals('/end$/iU', (string)new Pattern('end$', Pattern::CASE_INSENSITIVE | Pattern::UNGREEDY));
  }

  #[@test]
  public function php_bug_73947() {
    $this->assertEquals(
      ['http://domain', 'http', '', 'domain'],
      Pattern::compile('([a-z]+)://([^@]+@)?([a-z]+)')->match('http://domain')->group(0)
    );
  }
}