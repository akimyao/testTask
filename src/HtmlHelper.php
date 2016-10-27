<?php

namespace TestTask;


class HtmlHelper
{
    public static function generateAlert($msg)
    {
        $pre = '<div class="box alerts" id="alerts"><p class="alert-msg">';
        $post = '</p></div>';
        return $pre . $msg . $post;
    }

    public static function generateSuccess($msg)
    {
        $pre = '<div class="box success" id="alerts"><p class="success-msg">';
        $post = '</p></div>';
        return $pre . $msg . $post;
    }

    public static function setTagWith($tag, $innerHtml, $classContent = '')
    {
        $class = '';
        if (!empty($classContent)) {
            $class = " class=\"$classContent\"";
        }

        return "<$tag" . "$class>" . $innerHtml . "</$tag>";
    }
}