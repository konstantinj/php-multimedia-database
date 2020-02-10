<?php

use Phalcon\Mvc\Model;

class Media extends Model
{
    public $id;
    public $title;
    public $type;
    public $duration;
    public $width;
    public $height;
    public $data;

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'ActorsMedia',
            'media_id',
            'actor_id',
            'Actors',
            'id',
            array('alias' => 'actors')
        );
    }

    public function skipData($mode = false)
    {
        if ($mode) {
            $this->skipAttributes(['data',]);
        } else {
            $this->skipAttributes([]);
        }
    }
}