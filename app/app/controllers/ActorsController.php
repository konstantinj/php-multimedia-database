<?php

use \Phalcon\Mvc\Controller;

class ActorsController extends ControllerBase
{
    public function initialize()
    {
        $this->tag->setTitle('Manage Actors');
        parent::initialize();
    }

    public function indexAction($id = null)
    {
        if ($id) {
            $actor = Actors::findFirstById($id);

            if (!$actor) {
                $this->flash->error('Actor was not found');
                return $this->dispatcher->forward(['controller' => 'actors', 'action' => 'index']);
            }

            $this->view->form = new ActorsForm($actor, ['edit' => true]);
        } else {
            $this->view->form = new ActorsForm(null, ['edit' => true]);
        }

        $this->view->actors = Actors::find();
        if (!count($this->view->actors)) {
            $this->flash->notice('The search did not find any Actors');
        }
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['controller' => 'actors', 'action' => 'index']);
        }

        $form = new ActorsForm();
        $actor = new Actors();

        $data = $this->request->getPost();

        if (!$form->isValid($data, $actor)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(['controller' => 'actors', 'action' => 'index']);
        }

        if ($actor->save() == false) {
            foreach ($actor->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(['controller' => 'actors', 'action' => 'index']);
        }

        $form->clear();
        $this->flash->success('Actor was created successfully');
        return $this->dispatcher->forward(['controller' => 'actors', 'action' => 'index']);
    }

    public function deleteAction($ids)
    {
        ActorsMedia::find(sprintf('actor_id IN (%s)', $ids))->delete();
        Actors::find(sprintf('id IN (%s)', $ids))->delete();
        $this->response->redirect('actors');
    }
}