<?php

namespace TestTask;

/**
 * Class HtmlHelper
 *
 * Класс для упрощения работы с Html.
 *
 * @package TestTask
 */
class HtmlHelper
{
    /**
     * Создать сообщение об ошибке.
     *
     * @param $msg
     * @return string
     */
    public static function generateAlert($msg)
    {
        $pre = '<div class="box alerts" id="alerts"><p class="alert-msg">';
        $post = '</p></div>';
        return $pre . $msg . $post;
    }

    /**
     * Создать сообщение об успешном выполнении действия.
     *
     * @param $msg
     * @return string
     */
    public static function generateSuccess($msg)
    {
        $pre = '<div class="box success" id="alerts"><p class="success-msg">';
        $post = '</p></div>';
        return $pre . $msg . $post;
    }

    /**
     * Создать тег с заданным содержанием.
     *
     * @param string $tag наименование тега
     * @param string $innerHtml текст или html-код внутри тега
     * @param string $classContent атрибут class
     * @return string
     */
    public static function setTagWith($tag, $innerHtml, $classContent = '')
    {
        $class = '';
        if (!empty($classContent)) {
            $class = " class=\"$classContent\"";
        }

        return "<$tag" . "$class>" . $innerHtml . "</$tag>";
    }
}