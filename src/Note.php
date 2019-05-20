<?php namespace arnehendriksen\EvernoteHelpers;

class Note
{

    public static $date;
    public static $title;
    public static $author;
    public static $content;
    public static $compiled;

    /**
     * Create a new note, based on the provided attributes.
     *
     * @param array $attributes
     * @return string
     */
    public static function create($attributes = [])
    {
        self::$date = (isset($attributes['date']) ? $attributes['date'] : date('Ymd').'T'.date('His').'Z');
        self::$title = (isset($attributes['title']) ? $attributes['title'] : 'Untitled');
        self::$author = (isset($attributes['author']) ? $attributes['author'] : 'EvernoteNote');
        self::$content = (isset($attributes['content']) ? $attributes['content'] : '');

        return self::compile();
    }

    /**
     * Add a subheading to the note contents.
     *
     * @param $subheading
     */
    public static function addSubheading($subheading)
    {
        $content = '';
        if (self::$content) {
            $content = '<div><br /></div>';
        }
        $content .= '<div><b>'.$subheading.'</b></div>';
        self::appendContent($content);
    }

    /**
     * Add a todo to the note contents.
     *
     * @param $todo
     * @param bool $checked
     * @param null $url
     */
    public static function addTodo($todo, $checked = false, $url = null)
    {
        $todo = ($url ? '<a href="'.$url.'">'.$todo.'</a>' : $todo);
        $content = '<div><en-todo checked="'.($checked ? 'true' : 'false').'" />'.$todo.'<br /></div>';
        self::appendContent($content);
    }

    /**
     * Append new content.
     *
     * @param $content
     */
    public static function appendContent($content)
    {
        self::$content .= $content;
    }

    /**
     * Prepend new content.
     *
     * @param $content
     */
    public static function prependContent($content)
    {
        self::$content = $content . self::$content;
    }

    /**
     * Compile the source for the .enex file.
     *
     * @return string
     */
    public static function compile()
    {
        $date = self::$date;
        $title = self::$title;
        $author = self::$author;
        $content = self::$content;

        $compiled = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE en-export SYSTEM "http://xml.evernote.com/pub/evernote-export3.dtd">
<en-export export-date="$date" application="EvernoteNote">
<note>
<title>$title</title>
<content>
<![CDATA[<!DOCTYPE en-note SYSTEM "http://xml.evernote.com/pub/enml2.dtd"><en-note>
$content
</en-note>]]>
</content>
<created>$date</created>
<updated>$date</updated>
<note-attributes><author>$author</author><source></source><reminder-order>0</reminder-order></note-attributes>
</note>
</en-export>
EOT;
        self::$compiled = $compiled;

        return $compiled;
    }
}
