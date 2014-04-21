<?php
/**
 * Class Article
 *
 * @author NMC <admin@nmc-lab.com>
 */

class Article
{
    public $title;
    public $id;
    public $date;
    public $url;
    public $htmlpage;
    public $keywords;

    private $attributes = array('title', 'id', 'date', 'url', 'htmlpage', 'keywords');

    public function __Construct($record)
    {
        if ($record != null && is_array($record)) {
            foreach ($this->attributes as $attribute) {
                if (isset($record[$attribute])) {
                    if($attribute == 'date'){
                        $this->date = new DateTime($record['date']);
                    }else{
                        $this->$attribute = $record[$attribute];
                    }
                }
            }
        }
    }

    public function getURL($hostname){
        $link = $this->getYear() .'/'. $this->getMonth() . '/' . $this->url;

        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
        return sprintf($protocol . $hostname . '/%s', $link);
    }

    public function getImgPath($img){
        return 'articles/' . $this->url . '/' . $img;
    }

    public function getYear(){
        return $this->date->format('Y');
    }

    public function getMonth(){
        return $this->date->format('m');
    }

    public function getDate(){
        return $this->date->format('Y-m-d');
    }

    public function getHTMLPagePath()
    {

        return 'articles' . DIRECTORY_SEPARATOR . $this->getYear() . DIRECTORY_SEPARATOR . $this->getMonth() . DIRECTORY_SEPARATOR . $this->htmlpage;
    }

}