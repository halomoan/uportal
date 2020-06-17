<?php

namespace App\MyLibs;

use Illuminate\Support\Facades\DB;
use \App\Timeline;
use \App\TLBody;


class Timeliner
{
    protected  $timeline_id;
    protected  $news_id;

    // public function  __construct($users = null, $groups = null)
    // {
    //     $this->users = $users;
    //     $this->groups = $groups;
    // }

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
        if (preg_match('/^.{1,180}\b/s', $news->description, $match)) {
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
            foreach ($users as $user) {
                $user->timelines()->attach($this->timeline_id);
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
