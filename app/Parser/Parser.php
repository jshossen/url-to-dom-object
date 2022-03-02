<?php
namespace Scrapper\Parser;

use PHPHtmlParser\Dom;
use PHPHtmlParser\Options;

class Parser{
        
    public $url = null;
    public $dom = null;
    public $data = array();
    public function __construct($url){
        $this->url = $url;
    }

    public function run(){
        $str = $this->getUrlData($this->url);
        $this->dom = new Dom;
        $this->dom->setOptions(
            // this is set as the global option level.
            (new Options())
                ->setremoveScripts(false)
        );
        $this->dom->setOptions(
            // this is set as the global option level.
            (new Options())
                ->setwhitespaceTextNode(false)
        );
        $this->dom->loadStr($str);

        $data['url'] = $this->url;
        $data['data'] = $this->recursivelyGetData($this->dom->find('html')[0]);
        // var_dump($data);
        echo json_encode($data);
    }

    public function getTitle(){
        $title = $this->dom->find('head title');
        return $title->text;
    }
        
    public function recursivelyGetData($element){
        // echo '<pre>';
        // var_dump($element->getTag()->isSelfClosing());die();
        // var_dump($element->getAttributes());die();
        $data = array();
        $data['tag'] = $element->getTag()->name();
        $data['endTag'] = !$element->getTag()->isSelfClosing();
        $data['text'] = $element->text;
        $data['attributes'] = $element->getAttributes();
        $data['children'] = array();
        foreach($element->find('*') as $child){
            $data['children'][] = $this->recursivelyGetData($child);
        }
        return $data;
    }

    public function getUrlData($url){
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}