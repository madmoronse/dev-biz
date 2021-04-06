<?php  
class ControllerModuleSlideshow extends Controller {
	protected function index($setting) {
		
		if ($setting['banner_id'] == 6) {
				
			$customer_group_id = $this->customer->getCustomerGroupId();
		
			static $module = 0;
			
			$this->load->model('design/banner');
			$this->load->model('tool/image');
			$this->document->addScript('catalog/view/javascript/jquery/slick/slick.min.js');
			$this->document->addScript('js/neos_main.min.js?v2');
			$this->document->addStyle('catalog/view/javascript/jquery/slick/slick.css');
			$this->document->addStyle('catalog/view/javascript/jquery/slick/slick-theme.css?v2');
			// Добавлен slick вместо nivo-slider by NEOS
			// $this->document->addScript('catalog/view/javascript/jquery/nivo-slider/jquery.nivo.slider.pack.js');
			
			// if (file_exists('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/slideshow.css')) {
			// 	$this->document->addStyle('catalog/view/theme/' . $this->config->get('config_template') . '/stylesheet/slideshow.css');
			// } else {
			// 	$this->document->addStyle('catalog/view/theme/default/stylesheet/slideshow.css');
			// }
			
			$this->data['width'] = $setting['width'];
			$this->data['height'] = $setting['height'];
			
			$this->data['banners'] = array();
			
			if (isset($setting['banner_id'])) {
		
				$results = $this->model_design_banner->getBanner($setting['banner_id']);
				
				foreach ($results as $result) {
					if (file_exists(DIR_IMAGE . $result['image']) || 
						file_exists(DIR_IMAGE . $result['video'])) {
						/** Neos - Allow gifs - Start */
						$extension = pathinfo($result['image']);
						$extension = $extension['extension'];
						if ($extension == 'gif') {
							$image_url = HTTP_SERVER . 'image/' . $result['image'];
						} else {
							$image_url = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
						}
						$video_url = trim($result['video']);
						$video_url = (!empty($video_url)) ? HTTP_SERVER . 'image/' . $video_url : $video_url;
						/** Neos - Allow gifs - End */
						$this->data['banners'][] = array(
							'banner_image_id' => $result['banner_image_id'],
							'title' => $result['title'],
							'link'  => $result['link'],
							'image' => $image_url,
							'video' => $video_url
						);
					}
				}
			}
	
			$this->data['module'] = $module++;
							
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/slideshow.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/module/slideshow.tpl';
			} else {
				$this->template = 'default/template/module/slideshow.tpl';
			}
	
			$this->render();
		}
	}

	public function countFollows()
	{
		if (!isset($this->request->post['link']) || !isset($this->request->post['banner_image_id'])) return false;
		$banner_image_id = (int) $this->request->post['banner_image_id'];
		require_once DIR_APPLICATION . '../neos_debug/vendor/autoload.php';

		PHPCount::AddHit('banner_image-' . $banner_image_id);
	}
}
?>