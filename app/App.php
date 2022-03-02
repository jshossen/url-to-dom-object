<?php
namespace Scrapper;

use Scrapper\Parser\Parser;

class App{
        
    public $url = null;
    public function __construct($url){
        $this->url = $url;
    }

    public function run(){
        $parser = new Parser($this->url);
        $parser->run();
    }
        
}