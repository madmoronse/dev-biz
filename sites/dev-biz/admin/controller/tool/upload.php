<?php 
class ControllerToolUpload extends Controller {

    protected $video_save_path;

    public function __construct($registry) {
        $this->video_save_path = DIR_IMAGE . 'video/';
        parent::__construct($registry);
    }
	public function index() {		
        $this->language->load('tool/upload');

        $this->data['breadcrumbs'] = array();
        $this->document->setTitle($this->language->get('heading_title'));
		
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['info'] = $this->language->get('info');
        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),       		
            'separator' => false
        );

        $this->data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('tool/upload', 'token=' . $this->session->data['token'], 'SSL'),
            'separator' => ' :: '
        );
        $this->template = 'tool/upload.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
		$this->response->setOutput($this->render());
	}
	
	public function uploadVideo() {
		$this->language->load('tool/upload');
		
        if ($this->user->hasPermission('modify', 'tool/upload')) {
            require_once DIR_APPLICATION . '../neos_debug/vendor/autoload.php';
            try {
                $storage = new \Upload\Storage\FileSystem($this->video_save_path);
                $file = new \Upload\File('video', $storage);
            } catch (\Exception $e){
                $this->response->setOutput(json_encode(array('error' => array($e->getMessage()))));
                return false;
            }
            // Validate file upload
            // MimeType List => http://www.iana.org/assignments/media-types/media-types.xhtml
            $file->addValidations(array(
                new \Upload\Validation\Extension('mp4'),
                new \Upload\Validation\Mimetype('video/mp4'),
                new \Upload\Validation\Size('10M')
            ));
            if ($this->request->get['force_upload'] && file_exists($this->video_save_path . $file->getNameWithExtension())) {
                unlink($this->video_save_path . $file->getNameWithExtension());
            }
            // Try to upload file
            try {
                // Success!
                $file->upload();
                $this->response->setOutput(json_encode(
                    array(
                        'success' => true, 
                        'link' => HTTP_CATALOG . 'image/video/' . $file->getNameWithExtension(),
                        'video' => 'video/' . $file->getNameWithExtension()
                        )
                ));
            } catch (\Exception $e) {
                // Fail!
                $errors = $file->getErrors();
                if (empty($errors)) {
                    $errors = array($e->getMessage());
                }
                $this->response->setOutput(json_encode(array('error' => $errors)));
            }
            
		} else {
			$this->response->setOutput(json_encode(array('error' => array($this->language->get('error_permission')))));
		}
    }
    
    public function deleteVideo() {
		$this->language->load('tool/upload');
		
        if ($this->user->hasPermission('modify', 'tool/upload')) {
            $filename = basename($this->request->post['filename']);
            $success = false;
            if (file_exists($this->video_save_path . $filename)) {
                $success = unlink($this->video_save_path . $filename);
            } else {
               $success = true; 
            }
            if (!$success) {
                $this->response->setOutput(json_encode(array('error' => $this->language->get('error_delete'))));
            } else {
                $this->response->setOutput(json_encode(array('message' => $this->language->get('success_delete'))));
            }
		} else {
			$this->response->setOutput(json_encode(array('error' => array($this->language->get('error_permission')))));
		}
	}
}
?>