<?php namespace text\regex\unittest;

use text\regex\CharacterClass;
use unittest\TestCase;

/**
 * TestCase
 *
 * @see      xp://text.regex.CharacterClass
 */
class CharacterClassTest extends TestCase {

  #[@test, @values(['hello', 'hello123', '123', 'HelloWorld'])]
  public function alnum($value) {
    $this->assertTrue(CharacterClass::$ALNUM->matches($value), $value);
  }

  #[@test, @values(['', '123.2', ' ', "Hello\0World", ''])]
  public function not_alnum($value) {
    $this->assertFalse(CharacterClass::$ALNUM->matches($value), $value);
  }

  #[@test, @values(['hello', 'hello123', '123', 'HelloWorld', 'A_B', '123_456'])]
  public function word($value) {
    $this->assertTrue(CharacterClass::$WORD->matches($value), $value);
  }

  #[@test, @values(['', 'Hello World'])]
  public function not_word($value) {
    $this->assertFalse(CharacterClass::$WORD->matches($value), $value);
  }

  #[@test, @values(['hello', 'HelloWorld'])]
  public function alpha($value) {
    $this->assertTrue(CharacterClass::$ALPHA->matches($value), $value);
  }

  #[@test, @values(['', '123', '_'])]
  public function not_alpha($value) {
    $this->assertFalse(CharacterClass::$ALPHA->matches($value), $value);
  }

  #[@test, @values([' ', "\t", '    ', "\t "])]
  public function blank($value) {
    $this->assertTrue(CharacterClass::$BLANK->matches($value), $value);
  }

  #[@test, @values(['', "\n", "\r", 'Hello World'])]
  public function not_blank($value) {
    $this->assertFalse(CharacterClass::$BLANK->matches($value), $value);
  }

  #[@test, @values(["\1", "\r\n", "\x7F", "\0\1\2\3"])]
  public function cntrl($value) {
    $this->assertTrue(CharacterClass::$CNTRL->matches($value), addcslashes($value, "\0..\377"));
  }

  #[@test, @values(['', ' ', 'Hello World', '123', "\0Hello"])]
  public function not_cntrl($value) {
    $this->assertFalse(CharacterClass::$CNTRL->matches($value), $value);
  }

  #[@test, @values(['0', '123'])]
  public function digit($value) {
    $this->assertTrue(CharacterClass::$DIGIT->matches($value), $value);
  }

  #[@test, @values(['', ' ', '123.2', '0xFF', 'Hello World'])]
  public function not_digit($value) {
    $this->assertFalse(CharacterClass::$DIGIT->matches($value), $value);
  }

  #[@test, @values(['0', '123', 'Hello', '.', 'ARG!!!', 'a[0]', '0xFF'])]
  public function graph($value) {
    $this->assertTrue(CharacterClass::$GRAPH->matches($value), $value);
  }

  #[@test, @values(['', ' ', "\r\n\t", "\0"])]
  public function not_graph($value) {
    $this->assertFalse(CharacterClass::$GRAPH->matches($value), $value);
  }

  #[@test, @values(['abc', 'z'])]
  public function lower($value) {
    $this->assertTrue(CharacterClass::$LOWER->matches($value), $value);
  }

  #[@test, @values(['', ' ', "\r\n\t", "\0", 'ABC', 'aBC', '123'])]
  public function not_lower($value) {
    $this->assertFalse(CharacterClass::$LOWER->matches($value), $value);
  }

  #[@test, @values(['0', '123', ' ', 'Hello', '.', 'ARG!!!', 'a[0]', '0xFF'])]
  public function print_($value) {
    $this->assertTrue(CharacterClass::$PRINT->matches($value), $value);
  }

  #[@test, @values(['', "\r\n\t", "\0"])]
  public function not_print($value) {
    $this->assertFalse(CharacterClass::$PRINT->matches($value), $value);
  }

  #[@test, @values([',', '.', '"!!"', '[', ']', '__'])]
  public function punct($value) {
    $this->assertTrue(CharacterClass::$PUNCT->matches($value), $value);
  }

  #[@test, @values(['', "\r\n\t", "\0", 'Hello World', '123'])]
  public function not_punct($value) {
    $this->assertFalse(CharacterClass::$PUNCT->matches($value), $value);
  }

  #[@test, @values([' ', "\r\n\t", "\r", "\n", "\r\n", "\x0b\x0c"])]
  public function space($value) {
    $this->assertTrue(CharacterClass::$SPACE->matches($value), $value);
  }

  #[@test, @values(['', "\0", 'Hello World', '123'])]
  public function not_space($value) {
    $this->assertFalse(CharacterClass::$SPACE->matches($value), $value);
  }

  #[@test, @values(['ABC', 'Z'])]
  public function upper($value) {
    $this->assertTrue(CharacterClass::$UPPER->matches($value), $value);
  }

  #[@test, @values(['', ' ', "\r\n\t", "\0", 'abc', 'aBC', '123'])]
  public function not_upper($value) {
    $this->assertFalse(CharacterClass::$UPPER->matches($value), $value);
  }

  #[@test, @values(['0', '123', 'FF', 'FE12AC', 'efefef', 'dead'])]
  public function xdigit($value) {
    $this->assertTrue(CharacterClass::$XDIGIT->matches($value), $value);
  }

  #[@test, @values(['', ' ', '123.2', '0xFF', 'Hello World'])]
  public function not_xdigit($value) {
    $this->assertFalse(CharacterClass::$XDIGIT->matches($value), $value);
  }
}