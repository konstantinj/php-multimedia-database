<?php

use Phalcon\Mvc\Model;

class Actors extends Model
{
    public $id;
    public $name;

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'ActorsMedia',
            'actor_id',
            'media_id',
            'Media',
            'id',
            array('alias' => 'media')
        );
    }
}