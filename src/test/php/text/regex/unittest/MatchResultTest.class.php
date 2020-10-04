<?php namespace text\regex\unittest;

use text\regex\MatchResult;
use unittest\{Test, TestCase};

class MatchResultTest extends TestCase {

  #[Test]
  public function can_create() {
    new MatchResult(0, []);
  }

  #[Test]
  public function empty_match_result_has_zero_length() {
    $this->assertEquals(0, MatchResult::$EMPTY->length());
  }

  #[Test]
  public function empty_match_result_has_no_groups() {
    $this->assertEquals([], MatchResult::$EMPTY->groups());
  }

  #[Test]
  public function empty_match_result_equality() {
    $this->assertEquals(MatchResult::$EMPTY, MatchResult::$EMPTY);
  }
}