<?php

namespace App\MyLibs;

use Illuminate\Support\Facades\DB;
use \App\Timeline;
use \App\TLBody;


class Timeliner
{
    private static $_instance = null;

    protected  $timeline_id;
    protected  $news_id;

    public static function getInstance ()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public  function News(\App\News $news)
    {
               
        $timeline = Timeline::create([
            'from' => '',
            'title' => 'Annoucement',
            'link' => 'announce',
            'linktext' => 'Show Me',
            'type' => 1
        ]);


        $text = "";
        if (preg_match('/^.{1,180}\b/s', $news->title, $match)) {
            $text = $match[0] . "...";
        }


        $body = new TLBody(['body' => $text]);
        $timeline->body()->save($body);

        $this->timeline_id = $timeline->id;
        $this->news_id = $news->id;

        return $this;
    }

    public  function forUsers($users = null)
    {
        if ($users) {

            DB::delete("DELETE FROM timelines WHERE id IN ( SELECT timeline_id FROM timeline_user WHERE news_id = " . $this->news_id . " )");
            foreach ($users as $user) {
                $user->timelines()->attach($this->timeline_id, ['news_id' => $this->news_id]);
            }
        }

        return $this;
    }

    public  function forGroups($groups = null)
    {
        if ($groups) {

            DB::delete("DELETE FROM timelines WHERE id IN ( SELECT timeline_id FROM group_timeline WHERE news_id = " . $this->news_id . " )");

            foreach ($groups as $group) {
                $group->timelines()->attach($this->timeline_id, ['news_id' => $this->news_id]);
            }
        }

        return $this;
    }
}