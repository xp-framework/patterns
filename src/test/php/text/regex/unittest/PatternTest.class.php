<?php namespace text\regex\unittest;

use lang\{FormatException, IndexOutOfBoundsException};
use text\regex\Pattern;
use unittest\actions\RuntimeVersion;
use unittest\{Expect, Test, TestCase};

/**
 * Pattern test 
 *
 * @see   http://www.regular-expressions.info/unicode.html
 */
class PatternTest extends TestCase {

  #[Test]
  public function length() {
    $this->assertEquals(
      0, 
      Pattern::compile('ABC')->match('123')->length()
    );
  }

  #[Test]
  public function isMatched() {
    $this->assertTrue(Pattern::compile('a+')->matches('aaa'));
  }

  #[Test]
  public function isNotMatched() {
    $this->assertFalse(Pattern::compile('a+')->matches('bbb'));
  }

  #[Test]
  public function stringPrimitiveInput() {
    $this->assertEquals(0, Pattern::compile('.')->match('')->length());
    $this->assertEquals(1, Pattern::compile('.')->match('a')->length());
    $this->assertEquals(2, Pattern::compile('.')->match('ab')->length());
  }

  #[Test]
  public function caseInsensitive() {
    $this->assertEquals(
      1, 
      Pattern::compile('a', Pattern::CASE_INSENSITIVE)->match('A')->length()
    );
  }

  #[Test]
  public function groups() {
    $this->assertEquals(
      [['Hello']],
      Pattern::compile('H[ea]llo')->match('Hello')->groups()
    );
  }

  #[Test]
  public function groupsWithOneMatch() {
    $this->assertEquals(
      [['www.example.com', 'www.', 'www', 'com']],
      Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com')->groups()
    );
  }

  #[Test]
  public function groupsWithMultipleMatches() {
    $this->assertEquals(
      [
        ['www.example.com', 'www.', 'www', 'com'],
        ['example.org', '', '', 'org']
      ],
      Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com and example.org')->groups()
    );
  }

  #[Test]
  public function group() {
    $this->assertEquals(
      ['Hello'],
      Pattern::compile('H[ea]llo')->match('Hello')->group(0)
    );
  }

  #[Test]
  public function groupWithOneMatch() {
    $this->assertEquals(
      ['www.example.com', 'www.', 'www', 'com'],
      Pattern::compile('(([w]{3})\.)?example\.(com|net|org)')->match('www.example.com')->group(0)
    );
  }

  #[Test]
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

  #[Test, Expect(IndexOutOfBoundsException::class)]
  public function nonExistantGroup() {
    Pattern::compile('H[ea]llo')->match('Hello')->group(1);
  }

  #[Test]
  public function matchEmptyString() {
    $this->assertEquals([], Pattern::compile('.')->match('')->groups());
  }

  #[Test]
  public function equality() {
    $this->assertEquals(
      Pattern::compile('[a-z]+'),
      Pattern::compile('[a-z]+')
    );
  }

  #[Test]
  public function unequality() {
    $this->assertNotEquals(
      Pattern::compile('[a-z]+', Pattern::CASE_INSENSITIVE),
      Pattern::compile('[a-z]+')
    );
  }

  #[Test]
  public function stringRepresentation() {
    $this->assertEquals(
      'text.regex.Pattern</[a-z]+/i>',
      Pattern::compile('[a-z]+', Pattern::CASE_INSENSITIVE)->toString()
    );
  }

  #[Test, Expect(FormatException::class)]
  public function illegalPattern() {
    Pattern::compile('(');
  }

  #[Test]
  public function lazyCompilation() {
    $p= new Pattern('(');
    try {
      $p->matches('irrelevant');
      $this->fail('Expected exception not thrown', null, 'lang.FormatException');
    } catch (FormatException $expected) {
      // OK
    }
  }

  #[Test]
  public function multilineDotAll() {
    $m= Pattern::compile('BEGIN {(.+)}', Pattern::MULTILINE | Pattern::DOTALL)->match('BEGIN {
      print "Hello World";
    }');
    $this->assertEquals(1, $m->length());
    $group= $m->group(0);
    $this->assertEquals('print "Hello World";', trim($group[1]));
  }

  #[Test]
  public function replaceWhitespace() {
    $pattern= Pattern::compile('\s+');
    $this->assertEquals(
      'Hello World with far too much whitespace',
      $pattern->replaceWith(' ', 'Hello  World     with   far too    much whitespace')
    );
  }

  #[Test]
  public function replaceWithDollarBackReference() {
    $pattern= Pattern::compile('H[ae]ll[oO0]');
    $this->assertEquals(
      '[Hello] [Hall0]',
      $pattern->replaceWith('[$0]', 'Hello Hall0')
    );
  }

  #[Test]
  public function replaceWithDollarBackReferences() {
    $quoter= Pattern::compile('([^=]+)=([^ >]+)([ >]*)');
    $this->assertEquals(
      '<a href="http://example.com" title="Link">...</a>',
      $quoter->replaceWith('$1="$2"$3', '<a href=http://example.com title=Link>...</a>')
    );
  }

  #[Test]
  public function stringCast() {
    $this->assertEquals('/^begin/', (string)new Pattern('^begin'));
  }

  #[Test]
  public function stringCastWithFlag() {
    $this->assertEquals('/end$/i', (string)new Pattern('end$', Pattern::CASE_INSENSITIVE));
  }

  #[Test]
  public function stringCastWithFlags() {
    $this->assertEquals('/end$/iU', (string)new Pattern('end$', Pattern::CASE_INSENSITIVE | Pattern::UNGREEDY));
  }

  #[Test]
  public function php_bug_73947() {
    $this->assertEquals(
      ['http://domain', 'http', '', 'domain'],
      Pattern::compile('([a-z]+)://([^@]+@)?([a-z]+)')->match('http://domain')->group(0)
    );
  }
}