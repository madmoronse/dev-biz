<?php
class ControllerCommonHeader extends Controller {
	protected function index() {

		$this->load->model('module/redirects');
		$this->model_module_redirects->tryRedirects();
		$this->data['title'] = $this->document->getTitle();

		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');

		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}

		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';

		}

		$this->language->load('common/header');
		$this->data['og_url'] = (isset($this->request->server['HTTPS']) ? HTTPS_SERVER : HTTP_SERVER) . substr($this->request->server['REQUEST_URI'], 1, (strlen($this->request->server['REQUEST_URI'])-1));
		$this->data['og_image'] = $this->document->getOgImage();

		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', 'ajax=1', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
		$this->data['text_checkout'] = $this->language->get('text_checkout');
		$this->data['text_page'] = $this->language->get('text_page');

		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');


		/** banner timer **/

			$deadLine='2018-07-02 08:00:00';
			$timeLeft=strtotime($deadLine)-time();

			$daysLeft = floor($timeLeft/86400);
			$hoursLeft = floor(($timeLeft%86400)/3600);
			$minutesLeft = floor(($timeLeft%3600)/60);

			if($daysLeft>4) $dayLeft_text=' дней ';
			if($daysLeft<5) $dayLeft_text=' дня ';
			if($daysLeft==1) $dayLeft_text=' день ';
			

			if($hoursLeft==1 or $hoursLeft==21) $hoursLeft_text=' час ';
			if(($hoursLeft>1 and $hoursLeft<5) or ($hoursLeft>21 and $hoursLeft<24)) $hoursLeft_text=' часа ';
			if(($hoursLeft>4 and $hoursLeft<21) or $hoursLeft==0) $hoursLeft_text=' часов ';

			$minLeft_TMP=substr($minutesLeft,-1);

			if ($minLeft_TMP<10) $minutesLeft_text=' минут ';
			if ($minLeft_TMP<5) $minutesLeft_text=' минуты ';
			if ($minLeft_TMP<2) $minutesLeft_text=' минута ';
			if ($minLeft_TMP<1) $minutesLeft_text=' минут ';

			if ($minutesLeft<15 and $minutesLeft>10) $minutesLeft_text=' минут ';

			if ($daysLeft>0) {

				$bTimer=$daysLeft . $dayLeft_text . $hoursLeft . $hoursLeft_text . $minutesLeft . $minutesLeft_text;
				//if ($hoursLeft==0) $bTimer=$daysLeft . $dayLeft_text . $minutesLeft . $minutesLeft_text;

			} else {
				$bTimer=$hoursLeft . $hoursLeft_text . $minutesLeft . $minutesLeft_text;
				if ($daysLeft<0) $bTimer='акция закончена';
			}


			$this->data['banner_timer'] = $bTimer;

		/** banner timer **/


		// Daniel's robot detector
		$status = true;

		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}

		// A dirty hack to try to set a cookie for the multi-store feature
		$this->load->model('setting/store');

		$this->data['stores'] = array();

		if ($this->config->get('config_shared') && $status) {
			$this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();

			$stores = $this->model_setting_store->getStores();

			foreach ($stores as $store) {
				$this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			}
		}

		// Search
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}

		// Menu
		if (isset($this->request->get['path'])) {
			$parts = explode('_', (string)$this->request->get['path']);
		} else {
			$parts = array();
		}

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->data['categories'] = array();

		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			if ($category['top']) {
				// Level 2
				$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				$сategoryDesign = $this->model_catalog_category->getCategoryDesign($category['category_id']);
				
				/*if ($category['category_id'] == 1163){
					$children_data[] = array(
						'name'  => "Скидка 20%",
						'href'  => $this->url->link('product/category', 'path=10013408' )
					);
					
					$children_data[] = array(
						'name'  => "Скидка 30%",
						'href'  => $this->url->link('product/category', 'path=10013409' )
					);
					
					$children_data[] = array(
						'name'  => "Скидка 40%",
						'href'  => $this->url->link('product/category', 'path=10013410' )
					);
					
					$children_data[] = array(
						'name'  => "Скидка 50%",
						'href'  => $this->url->link('product/category', 'path=10013411' )
					);
				}*/
				
				foreach ($children as $child) {
					//Будем вычислять кол-во товаров в категориях только если это кол-во надо показывать
					if ($this->config->get('config_product_count')) {
						$data = array(
							'filter_category_id'  => $child['category_id'],
							'filter_sub_category' => true
						);

						$product_total = $this->model_catalog_product->getTotalProducts($data);
					}

					$children_data[] = array(
						'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);
				}

				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'active'   => in_array($category['category_id'], $parts),
					'column'   => $category['column'] ? $category['column'] : 1,
					'menu_button_background_color'   => $сategoryDesign['menu_button_background_color'] ? $сategoryDesign['menu_button_background_color'] : '',
					'menu_button_text_color'   => $сategoryDesign['menu_button_text_color'] ? $сategoryDesign['menu_button_text_color'] : '',
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}
	/* отключено, потому что новинки теперь это категория
    $this->data['categories'][] = array(
      'name'     => 'Новинки',
      'children' => array(),
      'active'   => true,
      'column'   => 1,
      'href'     => $this->url->link('product/category', 'path=' . 7608)
    );*/

	
//    $this->data['categories'][] = array(
//      'name'     => 'Скидки',
//      'children' => array(),
//      'active'   => true,
//      'column'   => 1,
//      'href'     => $this->url->link('product/category', 'withdiscount=' . 1)
//    );
		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart',
			'module/geoip'
		);
		
		
		$this->load->model('setting/setting');
        $this->data['slim_banner'] = $this->model_setting_setting->getSetting('slim_banner');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default2/template/common/header.tpl';
		}


    	$this->render();
	}
	
	public function slimbannerhit(){
        $customer_group_id = $this->customer->getCustomerGroupId();
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($customer_group_id != 2) && ($customer_group_id != 3) && ($customer_group_id != 4) ) {
            $this->load->model('design/slim_banner');
            $this->model_design_slim_banner->setHit();

        }

    }
	
}
?>
