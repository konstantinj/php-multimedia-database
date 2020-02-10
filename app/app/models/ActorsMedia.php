<?php

use Phalcon\Mvc\Model;

class ActorsMedia extends Model
{
    public function initialize()
    {
        $this->belongsTo('actor_id', 'Actors', 'id',
            array('alias' => 'actor')
        );
        $this->belongsTo('media_id', 'Media', 'id',
            array('alias' => 'media')
        );
    }
}