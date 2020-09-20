<?php

namespace App\MyLibs;

//use Illuminate\Support\Facades\DB;
use App\Models\Timeline;
use App\Models\TLBody;

class Timeliner
{
    private static $_instance = null;

    protected  $timeline;


    public static function getInstance()
    {
        if (self::$_instance === null) {
            self::$_instance = new self;
        }

        return self::$_instance;
    }

    public function Invoice(\App\Models\Invoice $invoice)
    {
    }

    public function News(\App\Models\News $news)
    {

        $timeline = Timeline::where('news_id', '=', $news->id)->first();
        if (!$timeline) {
            $timeline = Timeline::create([
                'from' => '',
                'title' => 'Annoucement',
                'link' => 'announces',
                'linktext' => 'Show Me',
                'type' => 1,
                'news_id' => $news->id
            ]);
        }


        $text = "";
        $text = strip_tags($news->description);
        if (strlen($text) > 180) {
            if (preg_match('/^.{1,180}\b/s', $text, $match)) {
                $text = $match[0] . "...";
            }
        }


        $body = new TLBody(['body' => $text]);
        $timeline->body()->save($body);

        $this->timeline = $timeline;

        return $this;
    }

    public  function forUsers($users = null)
    {

        if ($users) {

            //DB::delete("DELETE FROM timelines WHERE id IN ( SELECT timeline_id FROM timeline_user WHERE news_id = " . $this->news_id . " )");
            foreach ($users as $user) {

                $user->timelines()->attach($this->timeline->id);
            }
        } else {

            $this->timeline->users()->detach();
        }

        return $this;
    }

    public  function forGroups($groups = null)
    {
        if ($groups) {

            //DB::delete("DELETE FROM timelines WHERE id IN ( SELECT timeline_id FROM group_timeline WHERE news_id = " . $this->news_id . " )");

            foreach ($groups as $group) {

                $group->timelines()->attach($this->timeline->id);
            }
        } else {
            $this->timeline->groups()->detach();
        }

        return $this;
    }
}
