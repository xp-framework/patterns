<?php namespace text\regex;

use lang\{FormatException, IllegalArgumentException};

/**
 * Scanner
 *
 * @see      php://sscanf
 * @see      http://www.kernel.org/doc/man-pages/online/pages/man3/scanf.3.html 
 */
class Scanner implements Matcher {
  protected $pattern= [];
  
  /**
   * Creates a new character class instance
   *
   * @param   string pattern
   * @throws  lang.FormatException
   */
  public function __construct($pattern) {
    $this->pattern= [];
    for ($i= 0, $s= strlen($pattern); $i < $s; $i++) {
      if ('%' === $pattern[$i]) {
        if (++$i >= $s) {
          throw new IllegalArgumentException('Not enough input at position '.($i - 1));
        }
        switch ($pattern[$i]) {
          case '%': $this->pattern[]= '20%'; break; 
          case 'd': $this->pattern[]= '11+-0123456789'; break;
          case 'x': $this->pattern[]= '11x0123456789abcdefABCDEF'; break;
          case 'f': $this->pattern[]= '11+-0123456789.'; break;
          case 's': $this->pattern[]= "01\1\2\3\4\5\6\7\10\11\12\13\14\15\16\17\20\21\22\23\24\25\26\27\30\31\32\33\34\35\36\37\40"; break;
          case '[': {   // [^a-z]: everything except a-z, [a-z]: only a-z, []01]: only "[", "0" and "1"
            if ($i+ 1 >= $s) {
              throw new FormatException('Unmatched "]" in format string');
            } else if ('^' === $pattern[$i + 1]) {
              $match= '01';
              $i++;
            } else {
              $match= '11';
            }
            if (false === ($p= strpos($pattern, ']', $i + (']' === $pattern[$i + 1] ? 2 : 0)))) {
              throw new FormatException('Unmatched "]" in format string');
            }
            $seq= substr($pattern, $i+ 1, $p- $i- 1);
            for ($j= 0, $t= strlen($seq); $j < $t; $j++) {
              if ($j < $t - 2 && '-' === $seq[$j + 1]) {
                $match.= implode('', range($seq[$j], $seq[$j + 2]));
                $j+= 2;
              } else {
                $match.= $seq[$j];
              }
            }
            $this->pattern[]= $match;
            $i+= $t+ 1;
            break;
          }
          default: {
            throw new FormatException('Bad scan character "'.$pattern[$i].'"');
          }
        }
      } else {
        $p= strcspn($pattern, '%', $i);
        $this->pattern[].= '20'.substr($pattern, $i, $p);
        $i+= $p- 1;
      }
    }
  }
  
  /**
   * Checks whether a given string matches this character class
   *
   * @param   string input
   * @return  bool
   */
  public function matches($input) {
    $o= 0;
    $matches= 0;
    foreach ($this->pattern as $match) {
      switch ($match[0]) {
        case '0': $l= strcspn($input, substr($match, 2), $o); break;
        case '1': $l= strspn($input, substr($match, 2), $o); break;
        case '2': $s= strlen($match)- 2; $l= substr($match, 2) === substr($input, $o, $s) ? $s : 0; break;
      }
      if (0 === $l) break;
      $matches++;
      $o+= $l;
    }
    return $matches > 0;
  }

  /**
   * Returns match results
   *
   * @param   string input
   * @return  text.regex.MatchResult
   */
  public function match($input) {
    $matches= [0 => ''];
    $o= 0;
    foreach ($this->pattern as $match) {
      switch ($match[0]) {
        case '0': $l= strcspn($input, substr($match, 2), $o); break;
        case '1': $l= strspn($input, substr($match, 2), $o); break;
        case '2': $s= strlen($match)- 2; $l= substr($match, 2) === substr($input, $o, $s) ? $s : 0; break;
      }
      if (0 === $l) break;
      $matched= substr($input, $o, $l);
      $matches[0].= $matched;
      $match[1] && $matches[]= $matched;
      $o+= $l;
    }
    
    if ('' === $matches[0]) return MatchResult::$EMPTY;
    return new MatchResult(sizeof($matches)- 1, [$matches]);
  }
}