<?php

namespace PHPStatic;

class Template {
  public $template;
  public $data;

  public function __construct ($template) {
    $this->template = $template;
  }

  public static function make ($path) {
    return new self($path);
  }

  public function withData ($data = []) {
    $this->data = $data;
    return $this;
  }
}