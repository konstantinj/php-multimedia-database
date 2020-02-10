<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\File;

class MediaForm extends Form
{
    public function initialize(Media $media = null, $options = null)
    {
        if (!isset($options['edit'])) {
            $element = new Text('id');
            $this->add(
                $element->setLabel('Id')
            );
        } else {
            $this->add(
                new Hidden('id')
            );
        }

        $data = new File('data');
        $data->setLabel('video/avi, video/mp4, video/x-flv, video/webm, image/jpeg, image/png');
        $this->add($data);

        $title = new Text('title');
        $title->setLabel('Title');
        $title->setFilters(['striptags', 'string']);
        $this->add($title);

        $actors = new Text('actors');
        $actors->setLabel('Actors');
        $actors->setFilters(['striptags', 'string']);
        $this->add($actors);

        $type = new Hidden('type');
        $this->add($type);

        $duration = new Hidden('duration');
        $this->add($duration);

        $width = new Hidden('width');
        $this->add($width);

        $height = new Hidden('height');
        $this->add($height);
    }
}
