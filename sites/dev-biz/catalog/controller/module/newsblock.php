<?php  
class ControllerModuleNewsblock extends Controller {

	protected function index($setting) {
	    $this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
        $this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

        $this->language->load('module/newsblock');
        $this->data['text_more'] = $this->language->get('text_more');
      	$this->data['text_title'] = $this->language->get('text_title');
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['newsblock_module'] = $this->config->get('newsblock_module');

        $this->load->model('pavblog/blog');
        $this->load->model('tool/image');
        $blogs = explode(',', $this->data['newsblock_module'][1]['selectedNews']);
            foreach ($blogs as $key=>$blog) {
                $this->data['news'][] = $this->model_pavblog_blog->getInfo($blog);
                $this->data['news'][$key]['image'] = $this->model_tool_image->resize($this->data['news'][$key]['image'], 280, 214 ,'w');

            }

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/newsblock.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/newsblock.tpl';
        } else {
            $this->template = 'default/template/module/newsblock.tpl';
        }
		
		$this->render();
	}
}
?>