<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of RSSFeed
 *
 * @author LikWee-PC
 */
class BlogPost
{
    var $date;
    var $ts;
    var $link;

    var $title;
    var $text;
}

class BlogFeed
{
    var $posts = array();

    function __construct($file_or_url)
    {
        $file_or_url = $this->resolveFile($file_or_url);
        if (!($x = simplexml_load_file($file_or_url)))
            return;

        foreach ($x->channel->item as $item)
        {
            $post = new BlogPost();
            $post->date  = (string) $item->pubDate;
            $post->ts    = strtotime($item->pubDate);
            $post->link  = (string) $item->link;
            $post->title = (string) $item->title;
            $post->text  = (string) $item->description;

            // Create summary as a shortened body and remove images,
            // extraneous line breaks, etc.
            $post->summary = $this->summarizeText($post->text);

            $this->posts[] = $post;
        }
    }

    private function resolveFile($file_or_url) {
        if (!preg_match('|^https?:|', $file_or_url)){
            $feed_uri = $_SERVER['DOCUMENT_ROOT'] .'/shared/xml/'. $file_or_url;
        }else{
            $feed_uri = $file_or_url;
        }

        return $feed_uri;
    }

    private function summarizeText($summary) {
        $summary = strip_tags($summary);

        // Truncate summary line to 100 characters
        $max_len = 100;
        if (strlen($summary) > $max_len)
            $summary = substr($summary, 0, $max_len) . '...';

        return $summary;
    }
}