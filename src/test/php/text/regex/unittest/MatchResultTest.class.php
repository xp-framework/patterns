<?php namespace text\regex\unittest;

use text\regex\MatchResult;
use unittest\TestCase;

class MatchResultTest extends TestCase {

  #[@test]
  public function can_create() {
    new MatchResult(0, []);
  }

  #[@test]
  public function empty_match_result_has_zero_length() {
    $this->assertEquals(0, MatchResult::$EMPTY->length());
  }

  #[@test]
  public function empty_match_result_has_no_groups() {
    $this->assertEquals([], MatchResult::$EMPTY->groups());
  }

  #[@test]
  public function empty_match_result_equality() {
    $this->assertEquals(MatchResult::$EMPTY, MatchResult::$EMPTY);
  }
}