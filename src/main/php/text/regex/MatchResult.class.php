<?php namespace text\regex;

use lang\IndexOutOfBoundsException;
use lang\Value;
use util\Objects;

/**
 * Represents a match result
 *
 * @see   xp://text.regex.Pattern#matches
 */
class MatchResult implements Value {
  protected $length  = 0;
  protected $matches = [];
  
  public static $EMPTY;
  
  static function __static() {
    self::$EMPTY= new self(0, []);
  }
  
  /**
   * Constructor
   *
   * @param   int length
   * @param   string[][] matches
   */
  public function __construct($length, $matches) {
    $this->length= $length;

    // Ensure empty patterns are not NULLed to ensure BC.
    // See https://bugs.php.net/bug.php?id=73947
    foreach ($matches as $group => $match) {
      $this->matches[$group]= [];
      foreach ($match as $i => $segment) {
        $this->matches[$group][$i]= (string)$segment;
      }
    }
  }
  
  /**
   * Return how many matches there where
   *
   * @return  int
   */
  public function length() {
    return $this->length;
  }

  /**
   * Returns all matched groups
   *
   * @return  string[][]
   */
  public function groups() {
    return $this->matches;
  }

  /**
   * Creates a hash code for this object
   *
   * @return  string
   */
  public function hashCode() {
    return 'M'.($this->matches ? Objects::hashOf($this->matches) : '0');
  }

  /**
   * Creates a string representation of this object
   *
   * @return  string
   */
  public function toString() {
    return nameof($this).'('.$this->length.') '.($this->matches ? \xp::stringOf($this->matches) : '<EMPTY>');
  }

  /**
   * Returns the matched group with the specified group offset
   *
   * @param   int offset
   * @return  string[]
   * @throws  lang.IndexOutOfBoundsException in case a group with the given offset does not exist
   */
  public function group($offset) {
    if (!isset($this->matches[$offset])) {
      throw new IndexOutOfBoundsException('No such group '.$offset);
    }
    return $this->matches[$offset];
  }
  
  /**
   * Compares this result to another value
   *
   * @param   var $value
   * @return  int
   */
  public function compareTo($value) {
    return $value instanceof self
      ? Objects::compare([$this->length, $this->matches], [$value->length, $value->matches])
      : 1
    ;
  }
}
