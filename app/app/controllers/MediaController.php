<?php

use \Phalcon\Mvc\Controller;

// Videos from http://techslides.com/sample-files-for-development

class MediaController extends ControllerBase
{
    /**
     * @var \Monolog\Logger
     */
    protected $logger;

    /**
     * @param \Monolog\Logger $logger
     *
     * @return MediaController
     */
    public function setLogger(\Monolog\Logger $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    public function initialize()
    {

        if (!$this->logger) {
            $this->logger = $this->di->getShared('logger');
        }

        $this->tag->setTitle('Manage Media');
        parent::initialize();
    }

    public function indexAction($id = null)
    {
        if ($id) {
            $media = Media::findFirstById($id);

            if (!$media) {
                $this->flash->error('Media was not found');
                return $this->dispatcher->forward(['controller' => 'media', 'action' => 'index']);
            }
            // @TODO should happen in form and set as default but this issue occurs https://stackoverflow.com/questions/40410878/form-using-dropdown-doesnt-work-on-phalcon
            if (count($media->actors) > 0) {
                $names = [];
                /** @var Actor $actor */
                foreach ($media->actors as $actor) {
                    $names[] = $actor->name;
                }
                $media->actors = implode(', ', $names);
            } else {
                $media->actors = '';
            }

            $this->view->form = new MediaForm($media, ['edit' => true]);
        } else {
            $this->view->form = new MediaForm(null, ['edit' => true]);
        }

        $this->view->media = Media::find();
        if (!count($this->view->media)) {
            $this->flash->notice('The search did not find any media');
        }
    }

    public function view2Action($id)
    {
        $media = Media::findFirstById($id);
        $this->view->data = base64_encode($media->data);
        $this->view->type = $media->type;
        $this->view->width = $media->width;
        $this->view->height = $media->height;
    }

    public function viewAction($id)
    {
        $media = Media::findFirstById($id);
        $this->view->type = $media->type;
        $this->view->kind = substr($media->type, 0, strpos($media->type, '/'));
        $this->view->id = $media->id;
        $this->view->title = $media->title;
        $this->view->src = str_replace('view', 'data', $this->router->getRewriteUri());

        // @TODO find better solution for handling of video formats that don't play natively in the browser
        if (in_array($media->type, ['video/avi', 'video/x-flv'])) {
            $this->view->src = str_replace('data', 'ffmpegdata', $this->view->src);
            $this->view->type = 'video/mp4';
        }
    }

    protected function outputMedia(Media $media)
    {
        $size = strlen($media->data);
        $begin = 0;
        $end = $size;

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=\h*(\d+)-(\d*)[\D.*]?/i', $_SERVER['HTTP_RANGE'], $matches)) {
                $begin = intval($matches[0]);
                if (!empty($matches[1])) {
                    $end = intval($matches[1]);
                }
            }
        }

        if ($begin > 0 || $end < $size) {
            header('HTTP/1.0 206 Partial Content');
        } else {
            header('HTTP/1.0 200 OK');
        }

        header("Content-Type: $media->type");
        header('Accept-Ranges: bytes');
        header("Content-Length: $end-$begin");
        header("Content-Disposition: inline; filename=\"$media->title\"");
        header("Content-Range: bytes $begin-$end/$size");
        header('Content-Transfer-Encoding: binary');
        header('Connection: close');

        $cur = $begin;

        while ($cur < $end && (connection_status() == 0)) {
            echo substr($media->data, $cur, min(1024 * 16, $end - $cur));
            $cur += 1024 * 16;
        }

        exit;
    }

    public function dataAction($id)
    {
        $media = Media::findFirstById($id);
        $this->outputMedia($media);
    }

    public function ffmpegdataAction($id)
    {
        $media = Media::findFirstById($id);
        $type = substr($media->type, strpos($media->type, '/') + 1, strlen($media->type));

        $oldFile = '/tmp/temp.' . $type;
        $newFile = '/tmp/temp.mp4';

        file_put_contents($oldFile, $media->data);

        $ffmpeg = FFMpeg\FFMpeg::create([], $this->logger);
        $video = $ffmpeg->open($oldFile);
        $video->save(new FFMpeg\Format\Video\X264('aac'), $newFile);

        $media->type = str_replace($type, 'mp4', $media->type);
        $media->data = file_get_contents($newFile);

        unlink($oldFile);
        unlink($newFile);

        $this->outputMedia($media);
    }

    public function xmlAction($id = null)
    {
        if ($id) {
            $rows = Media::find('id = ' . (int) $id);
        } else {
            $rows = Media::find();
        }

        $xml = new SimpleXMLElement('<media/>');

        foreach ($rows as $row) {
            $item = $xml->addChild('item');
            $item->addAttribute('link', 'http://' . $_SERVER['HTTP_HOST'] . '/media/view/' . $row->id);

            foreach ($row as $k => $v) {
                if ($k == 'data') {
                    $data = $item->addChild($k, base64_encode($v));
                    $data->addAttribute('encoding', 'base64');
                } else {
                    $item->addChild($k, $v);
                }
            }
            $actors = $item->addChild('actors');
            foreach ($row->actors as $actorRow) {
                $actor = $actors->addChild('actor');
                $actor->addAttribute('link', 'http://' . $_SERVER['HTTP_HOST'] . '/actor/index/' . $actorRow->id);
                foreach ($actorRow as $k => $v) {
                    $actor->addChild($k, $v);
                }
            }
        }

        header('Content-type: text/xml');
        echo $xml->asXML();
        exit;
    }

    public function saveAction()
    {
        if (!$this->request->isPost()) {
            return $this->dispatcher->forward(['controller' => 'media', 'action' => 'index']);
        }

        $form = new MediaForm();
        $media = new Media();

        $data = $this->request->getPost();

        if (!empty($data['id'])) {
            ActorsMedia::find(sprintf('media_id = (%s)', (int) $data['id']))->delete();
        }

        if (!empty($data['actors'])) {
            $actorNames = explode(',', $data['actors']);

            $actors = [];
            foreach ($actorNames as $actorName) {
                $actorName = trim($actorName);

                // when there's an actor with the exact same name, don't create a new one
                $actor = Actors::findFirst("name = '$actorName'");

                if (!$actor) {
                    $actor = new Actors();
                    $actor->name = $actorName;
                }
                $actors[] = $actor;
            }
            $media->actors = $actors;
            unset($data['actors']);
        }

        if ($this->request->hasFiles() && strlen($this->request->getUploadedFiles()[0]->getTempName()) > 0) {
            $getID3 = new getID3();
            $fileinfo = $getID3->analyze($this->request->getUploadedFiles()[0]->getTempName());
            $data['duration'] = ceil($fileinfo['playtime_seconds']);
            $data['type'] = $fileinfo['mime_type'];
            $data['width'] = $fileinfo['video']['resolution_x'];
            $data['height'] = $fileinfo['video']['resolution_y'];

            if (!$data['title']) {
                $data['title'] = $this->request->getUploadedFiles()[0]->getName();
            }

            $media->data = file_get_contents($this->request->getUploadedFiles()[0]->getTempName());
        } else {
            if (empty($data['id'])) {
                $this->flash->error('No media uploaded');
                return $this->dispatcher->forward(['controller' => 'media', 'action' => 'index']);
            }

            $media->skipData(true);
        }

        if (!$form->isValid($data, $media)) {
            foreach ($form->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(['controller' => 'media', 'action' => 'index']);
        }

        if ($media->save() == false) {
            foreach ($media->getMessages() as $message) {
                $this->flash->error($message);
            }
            return $this->dispatcher->forward(['controller' => 'media', 'action' => 'index']);
        }

        $form->clear();
        $this->flash->success('Media was saved successfully');
        return $this->dispatcher->forward(['controller' => 'media', 'action' => 'index']);
    }

    public function deleteAction($ids)
    {
        ActorsMedia::find(sprintf('media_id IN (%s)', $ids))->delete();
        Media::find(sprintf('id IN (%s)', $ids))->delete();
        $this->response->redirect('media');
    }
}
