<?php namespace text\regex;

/**
 * Represents a regular expression pattern
 *
 * @test  xp://net.xp_framework.unittest.text.PatternTest
 * @see   php://preg
 */
class Pattern extends \lang\Object implements Matcher {
  const 
    CASE_INSENSITIVE = 0x0001,
    MULTILINE        = 0x0002,
    DOTALL           = 0x0004,
    EXTENDED         = 0x0008,
    ANCHORED         = 0x0010,
    DOLLAR_ENDONLY   = 0x0020,
    ANALYSIS         = 0x0040,
    UNGREEDY         = 0x0080,
    UTF8             = 0x0100;
    
  protected static $flags= [
    self::CASE_INSENSITIVE => 'i',
    self::MULTILINE        => 'm',
    self::DOTALL           => 's',
    self::EXTENDED         => 'x',
    self::ANCHORED         => 'A',
    self::DOLLAR_ENDONLY   => 'D',
    self::ANALYSIS         => 'S',
    self::UNGREEDY         => 'U',
    self::UTF8             => 'u',
  ];
  
  protected
    $regex    = '',
    $utf8     = false;

  /**
   * Constructor
   *
   * @param   string regex
   * @param   int flags default 0 bitfield of pattern flags
   */
  public function __construct($regex, $flags= 0) {
    $modifiers= '';
    foreach (self::$flags as $bit => $str) {
      if ($flags & $bit) $modifiers.= $str;
    }
    $this->utf8= (bool)($flags & self::UTF8);
    $this->regex= '/'.str_replace('/', '\/', $regex).'/'.$modifiers;
  }
  
  /**
   * Creates a string representation of this object
   *
   * @return  string
   */
  public function toString() {
    return nameof($this).'<'.$this->regex.'>';
  }

  /**
   * String cast overloading
   *
   * @return  string
   */
  public function __toString() {
    return $this->regex;
  }

  /**
   * Returns whether a given object is equal to this
   *
   * @param   lang.Generic cmp
   * @return  bool
   */
  public function equals($cmp) {
    return $cmp instanceof self && $cmp->regex === $this->regex;
  }

  /**
   * Returns a hashcode for this pattern
   *
   * @return  string
   */
  public function hashCode() {
    return 'R'.$this->regex;
  }

  /**
   * Returns whether given input is matched.
   *
   * @param   string input
   * @return  bool
   * @throws  lang.FormatException
   */  
  public function matches($input) {
    if ($input instanceof \lang\types\String) {
      $n= preg_match($this->regex, (string)$input->getBytes($this->utf8 ? 'utf-8' : 'iso-8859-1'));
    } else {
      $n= preg_match($this->regex, (string)$input);
    }
    if (false === $n || PREG_NO_ERROR != preg_last_error()) {
      $e= new \lang\FormatException('Pattern "'.$this->regex.'" matching error');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $n != 0;
  }

  /**
   * Returns how many times a given input is matched.
   *
   * @param   string input
   * @return  text.regex.MatchResult
   * @throws  lang.FormatException
   */  
  public function match($input) {
    if ($input instanceof \lang\types\String) {
      $n= preg_match_all($this->regex, (string)$input->getBytes($this->utf8 ? 'utf-8' : 'iso-8859-1'), $m, PREG_SET_ORDER);
    } else {
      $n= preg_match_all($this->regex, (string)$input, $m, PREG_SET_ORDER);
    }
    if (false === $n || PREG_NO_ERROR != preg_last_error()) {
      $e= new \lang\FormatException('Pattern "'.$this->regex.'" matching error');
      \xp::gc(__FILE__);
      throw $e;
    }
    return new MatchResult($n, $m);
  }

  /**
   * Performs a replacement
   *
   * @param   string replacement
   * @param   string input
   * @return  string
   * @throws  lang.FormatException
   */  
  public function replaceWith($replacement, $input) {
    if ($input instanceof \lang\types\String) {
      $r= preg_replace($this->regex, $replacement, (string)$input->getBytes($this->utf8 ? 'utf-8' : 'iso-8859-1'));
    } else {
      $r= preg_replace($this->regex, $replacement, (string)$input);
    }
    if (false === $r || PREG_NO_ERROR != preg_last_error()) {
      $e= new \lang\FormatException('Pattern "'.$this->regex.'" matching error');
      \xp::gc(__FILE__);
      throw $e;
    }
    return $r;
  }
  
  /**
   * Compiles a pattern and returns the object
   *
   * @param   string regex
   * @param   int flags default 0 bitfield of pattern flags
   * @return  text.regex.Pattern
   * @throws  lang.FormatException
   */
  public static function compile($regex, $flags= 0) {
    $self= new self($regex, $flags);

    // Compile and test pattern
    $n= preg_match($self->regex, '');
    if (false === $n || PREG_NO_ERROR != preg_last_error()) {
      $e= new \lang\FormatException('Pattern "'.$regex.'" not well-formed');
      \xp::gc(__FILE__);
      throw $e;
    }

    return $self;
  }
}
