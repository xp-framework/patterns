<?php namespace text\regex\unittest;

use lang\FormatException;
use text\regex\{CharacterClass, Scanner};
use unittest\{Expect, Test, TestCase};

class ScannerTest extends TestCase {

  #[Test]
  public function int() {
    $scanner= new Scanner('%d');
    $this->assertEquals(['123', '123'], $scanner->match('123')->group(0));
  }

  #[Test]
  public function negativeInt() {
    $scanner= new Scanner('%d');
    $this->assertEquals(['-123', '-123'], $scanner->match('-123')->group(0));
  }

  #[Test]
  public function hex() {
    $scanner= new Scanner('%x');
    $this->assertEquals(['FF', 'FF'], $scanner->match('FF')->group(0));
  }

  #[Test]
  public function hexWith0XPrefix() {
    $scanner= new Scanner('%x');
    $this->assertEquals(['0xFF', '0xFF'], $scanner->match('0xFF')->group(0));
  }

  #[Test]
  public function float() {
    $scanner= new Scanner('%f');
    $this->assertEquals(['123.20', '123.20'], $scanner->match('123.20')->group(0));
  }

  #[Test]
  public function negativeFloat() {
    $scanner= new Scanner('%f');
    $this->assertEquals(['-123.20', '-123.20'], $scanner->match('-123.20')->group(0));
  }

  #[Test]
  public function string() {
    $scanner= new Scanner('%s');
    $this->assertEquals(['Hello', 'Hello'], $scanner->match('Hello')->group(0));
  }

  #[Test]
  public function percentsSign() {
    $scanner= new Scanner('%d%%');
    $this->assertEquals(['100%', '100'], $scanner->match('100%')->group(0));
  }

  #[Test]
  public function stringDoesNotMatchSpace() {
    $scanner= new Scanner('%s');
    $this->assertEquals(['Hello', 'Hello'], $scanner->match('Hello World')->group(0));
  }

  #[Test]
  public function scanEmptyString() {
    $scanner= new Scanner('%s');
    $this->assertEquals([], $scanner->match('')->groups());
    $this->assertEquals(0, $scanner->match('')->length());
  }

  #[Test]
  public function characterSequence() {
    $scanner= new Scanner('%[a-z ]');
    $this->assertEquals(['hello world', 'hello world'], $scanner->match('hello world')->group(0));
  }

  #[Test]
  public function characterSequenceWithMinus() {
    $scanner= new Scanner('%[a-z-]');
    $this->assertEquals(['hello-world', 'hello-world'], $scanner->match('hello-world')->group(0));
  }

  #[Test]
  public function characterSequenceExcludes() {
    $scanner= new Scanner('%[^ ]');
    $this->assertEquals(['0123', '0123'], $scanner->match('0123 are numbers')->group(0));
  }

  #[Test]
  public function characterSequenceWithBracket() {
    $scanner= new Scanner('%[][0-9.]');
    $this->assertEquals(['[0..9]', '[0..9]'], $scanner->match('[0..9]')->group(0));
  }

  #[Test]
  public function serialNumberExample() {
    $scanner= new Scanner('SN/%d');
    $this->assertEquals(['SN/2350001', '2350001'], $scanner->match('SN/2350001')->group(0));
  }

  #[Test]
  public function serialNumberExampleNotMatching() {
    $scanner= new Scanner('SN/%d');
    $this->assertEquals(0, $scanner->match('/NS2350001')->length());
  }

  #[Test]
  public function authorParsingExample() {
    $scanner= new Scanner("%d\t%s %s");
    $this->assertEquals(["24\tLewis Carroll", '24', 'Lewis', 'Carroll'], $scanner->match("24\tLewis Carroll")->group(0));
  }

  #[Test]
  public function fileNameExample() {
    $scanner= new Scanner('file_%[^.].%d.%s');
    $this->assertEquals(['file_hello.0124.gif', 'hello', '0124', 'gif'], $scanner->match('file_hello.0124.gif')->group(0));
  }

  #[Test, Expect(FormatException::class)]
  public function unclosedBrackets() {
    new Scanner('%[');
  }

  #[Test, Expect(FormatException::class)]
  public function unknownScanCharacter() {
    new Scanner('%@');
  }
}