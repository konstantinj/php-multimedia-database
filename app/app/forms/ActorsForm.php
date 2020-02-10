<?php

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Hidden;

class ActorsForm extends Form
{
    public function initialize(Actors $actors = null, $options = null)
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

        $name = new Text('name');
        $name->setLabel('Name');
        $name->setFilters(['striptags', 'string']);
        $name->addValidators([new \Phalcon\Validation\Validator\PresenceOf(['message' => 'Title is required'])]);
        $this->add($name);
    }
}
