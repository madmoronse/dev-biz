<?php 
class ControllerTotalSocialDiscount extends Controller { 
	private $error = array(); 
	 
	public function index() { 
		$this->load->language('total/social_discount');

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && ($this->validate())) {
			$this->model_setting_setting->editSetting('social_discount', $this->request->post);
		
			$this->session->data['success'] = $this->language->get('text_success');
			
			if (isset($this->request->post['apply']) && $this->request->post['apply'] == '1') {
				$this->redirect($this->url->link('total/social_discount', 'token=' . $this->session->data['token'], 'SSL'));
			} else {
				$this->redirect($this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'));
			}
		}
		
		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_discount_method'] = $this->language->get('entry_discount_method');
		$this->data['entry_discount_method_help'] = $this->language->get('entry_discount_method_help');
		
		$this->data['entry_discount_type'] = $this->language->get('entry_discount_type');
		$this->data['enty_social_discount_type_0'] = $this->language->get('enty_social_discount_type_0');
		$this->data['enty_social_discount_type_1'] = sprintf($this->language->get('enty_social_discount_type_1'), $this->config->get('config_currency'));
		
		$this->data['entry_discount_lifetime'] = $this->language->get('entry_discount_lifetime');
		$this->data['entry_discount_lifetime_help'] = $this->language->get('entry_discount_lifetime_help');
		
		$this->data['entry_discount_active_mark'] = $this->language->get('entry_discount_active_mark');
		$this->data['entry_discount_active_mark_help'] = $this->language->get('entry_discount_active_mark_help');
		
		$this->data['entry_discount_value'] = $this->language->get('entry_discount_value');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		
		$this->data['entry_discount_integration'] = $this->language->get('entry_discount_integration');
		$this->data['entry_discount_integration_help'] = $this->language->get('entry_discount_integration_help');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_apply'] = $this->language->get('button_apply');
		
 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

   		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_total'),
			'href'      => $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL'),      		
      		'separator' => ' :: '
   		);
		
   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('total/social_discount', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
		
		$this->data['action'] = $this->url->link('total/social_discount', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/total', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['social_discount_status'])) {
			$this->data['social_discount_status'] = $this->request->post['social_discount_status'];
		} else {
			$this->data['social_discount_status'] = $this->config->get('social_discount_status');
		}

		if (isset($this->request->post['social_discount_sort_order'])) {
			$this->data['social_discount_sort_order'] = $this->request->post['social_discount_sort_order'];
		} else {
			$this->data['social_discount_sort_order'] = $this->config->get('social_discount_sort_order');
		}

		if (isset($this->request->post['social_discount_lifetime'])) {
			$this->data['social_discount_lifetime'] = $this->request->post['social_discount_lifetime'];
		} else {
			if ($this->config->get('social_discount_lifetime') != null) {
				$this->data['social_discount_lifetime'] = $this->config->get('social_discount_lifetime');
			} else {
				$this->data['social_discount_lifetime'] = 0;
			}
		}
		
		if (isset($this->request->post['social_discount_discount_method'])) {
			$this->data['social_discount_discount_method'] = $this->request->post['social_discount_discount_method'];
		} else {
			$this->data['social_discount_discount_method'] = $this->config->get('social_discount_discount_method');
		}
		
		$this->data['discount_methods'] = array (
			0 => 'Скидка с основной цены',
			1 => 'Скидка с акции',
		);
		
		if (isset($this->request->post['social_discount_discount_type'])) {
			$this->data['social_discount_discount_type'] = $this->request->post['social_discount_discount_type'];
		} else {
			$this->data['social_discount_discount_type'] = (int)$this->config->get('social_discount_discount_type');
		}
		
		switch ($this->data['social_discount_discount_type']) {
		case 0:
			$this->data['social_discount_type_sign'] = '%';
			break;
		case 1:
			$this->data['social_discount_type_sign'] = $this->config->get('config_currency');
			break;
		}
		
		if (isset($this->request->post['social_discount_active_mark'])) {
			$this->data['social_discount_active_mark'] = $this->request->post['social_discount_active_mark'];
		} else {
			if ($this->config->get('social_discount_active_mark') != null) {
				$this->data['social_discount_active_mark'] = $this->config->get('social_discount_active_mark');
			} else {
				$this->data['social_discount_active_mark'] = $this->language->get('entry_default_discount_active_mark');
			}
		}
		
		if (isset($this->request->post['social_discount_vk_like_enabled'])) {
			$this->data['social_discount_vk_like_enabled'] = $this->request->post['social_discount_vk_like_enabled'];
		} else {
			$this->data['social_discount_vk_like_enabled'] = $this->config->get('social_discount_vk_like_enabled');
		}
		
		if (isset($this->request->post['social_discount_vk_like_value'])) {
			$this->data['social_discount_vk_like_value'] = $this->request->post['social_discount_vk_like_value'];
		} else {
			$this->data['social_discount_vk_like_value'] = $this->config->get('social_discount_vk_like_value');
		}
		
		if (isset($this->request->post['social_discount_vk_share_enabled'])) {
			$this->data['social_discount_vk_share_enabled'] = $this->request->post['social_discount_vk_share_enabled'];
		} else {
			$this->data['social_discount_vk_share_enabled'] = $this->config->get('social_discount_vk_share_enabled');
		}
		
		if (isset($this->request->post['social_discount_vk_share_value'])) {
			$this->data['social_discount_vk_share_value'] = $this->request->post['social_discount_vk_share_value'];
		} else {
			$this->data['social_discount_vk_share_value'] = $this->config->get('social_discount_vk_share_value');
		}
		
		if (isset($this->request->post['social_discount_fb_like_enabled'])) {
			$this->data['social_discount_fb_like_enabled'] = $this->request->post['social_discount_fb_like_enabled'];
		} else {
			$this->data['social_discount_fb_like_enabled'] = $this->config->get('social_discount_fb_like_enabled');
		}
		
		if (isset($this->request->post['social_discount_fb_like_value'])) {
			$this->data['social_discount_fb_like_value'] = $this->request->post['social_discount_fb_like_value'];
		} else {
			$this->data['social_discount_fb_like_value'] = $this->config->get('social_discount_fb_like_value');
		}
		
		if (isset($this->request->post['social_discount_gp_like_enabled'])) {
			$this->data['social_discount_gp_like_enabled'] = $this->request->post['social_discount_gp_like_enabled'];
		} else {
			$this->data['social_discount_gp_like_enabled'] = $this->config->get('social_discount_gp_like_enabled');
		}
		
		if (isset($this->request->post['social_discount_gp_like_value'])) {
			$this->data['social_discount_gp_like_value'] = $this->request->post['social_discount_gp_like_value'];
		} else {
			$this->data['social_discount_gp_like_value'] = $this->config->get('social_discount_gp_like_value');
		}
		
		if (isset($this->request->post['social_discount_mm_like_enabled'])) {
			$this->data['social_discount_mm_like_enabled'] = $this->request->post['social_discount_mm_like_enabled'];
		} else {
			$this->data['social_discount_mm_like_enabled'] = $this->config->get('social_discount_mm_like_enabled');
		}
		
		if (isset($this->request->post['social_discount_mm_like_value'])) {
			$this->data['social_discount_mm_like_value'] = $this->request->post['social_discount_mm_like_value'];
		} else {
			$this->data['social_discount_mm_like_value'] = $this->config->get('social_discount_mm_like_value');
		}
		
		if (isset($this->request->post['social_discount_ok_like_enabled'])) {
			$this->data['social_discount_ok_like_enabled'] = $this->request->post['social_discount_ok_like_enabled'];
		} else {
			$this->data['social_discount_ok_like_enabled'] = $this->config->get('social_discount_ok_like_enabled');
		}
		
		if (isset($this->request->post['social_discount_ok_like_value'])) {
			$this->data['social_discount_ok_like_value'] = $this->request->post['social_discount_ok_like_value'];
		} else {
			$this->data['social_discount_ok_like_value'] = $this->config->get('social_discount_ok_like_value');
		}
		
		if (isset($this->request->post['social_discount_tw_like_enabled'])) {
			$this->data['social_discount_tw_like_enabled'] = $this->request->post['social_discount_tw_like_enabled'];
		} else {
			$this->data['social_discount_tw_like_enabled'] = $this->config->get('social_discount_tw_like_enabled');
		}
		
		if (isset($this->request->post['social_discount_tw_like_value'])) {
			$this->data['social_discount_tw_like_value'] = $this->request->post['social_discount_tw_like_value'];
		} else {
			$this->data['social_discount_tw_like_value'] = $this->config->get('social_discount_tw_like_value');
		}
		
		if (isset($this->request->post['social_discount_integration_addthis_enabled'])) {
			$this->data['social_discount_integration_addthis_enabled'] = $this->request->post['social_discount_integration_addthis_enabled'];
		} else {
			$this->data['social_discount_integration_addthis_enabled'] = $this->config->get('social_discount_integration_addthis_enabled');
		}
		
		if (isset($this->request->post['social_discount_integration_pluso_enabled'])) {
			$this->data['social_discount_integration_pluso_enabled'] = $this->request->post['social_discount_integration_pluso_enabled'];
		} else {
			$this->data['social_discount_integration_pluso_enabled'] = $this->config->get('social_discount_integration_pluso_enabled');
		}
		
		/* SOCIAL BUTTONS CODE */
		if (isset($this->request->post['social_discount_use_internal_buttons'])) {
			$this->data['social_discount_use_internal_buttons'] = $this->request->post['social_discount_use_internal_buttons'];
		} else {
			if ($this->config->get('social_discount_use_internal_buttons') != null) {
				$this->data['social_discount_use_internal_buttons'] = $this->config->get('social_discount_use_internal_buttons');
			} else {
				$this->data['social_discount_use_internal_buttons'] = 1;
			}
		}
		
		if (isset($this->request->post['social_discount_vk_button_code'])) {
			$this->data['social_discount_vk_button_code'] = $this->request->post['social_discount_vk_button_code'];
		} else {
			if ($this->config->get('social_discount_vk_button_code') != null) {
				$this->data['social_discount_vk_button_code'] = $this->config->get('social_discount_vk_button_code');
			} else {
				$this->data['social_discount_vk_button_code'] = '';
			}
		}
		
		if (isset($this->request->post['social_discount_fb_button_code'])) {
			$this->data['social_discount_fb_button_code'] = $this->request->post['social_discount_fb_button_code'];
		} else {
			if ($this->config->get('social_discount_fb_button_code') != null) {
				$this->data['social_discount_fb_button_code'] = $this->config->get('social_discount_fb_button_code');
			} else {
				$this->data['social_discount_fb_button_code'] = <<<HTML
<div class="fb-like" data-send="false" data-layout="button_count"  data-width="150" data-show-faces="true"></div>
HTML;
			}
		}
		
		if (isset($this->request->post['social_discount_gp_button_code'])) {
			$this->data['social_discount_gp_button_code'] = $this->request->post['social_discount_gp_button_code'];
		} else {
			if ($this->config->get('social_discount_gp_button_code') != null) {
				$this->data['social_discount_gp_button_code'] = $this->config->get('social_discount_gp_button_code');
			} else {
				$this->data['social_discount_gp_button_code'] = <<<HTML
<!-- Place this tag where you want the +1 button to render. -->
<div class="g-plusone" data-callback="plusone_share" data-annotation="inline" data-width="300"></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  window.___gcfg = {lang: 'ru'};

  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script>
HTML;
			}
		}
		
		if (isset($this->request->post['social_discount_mm_button_code'])) {
			$this->data['social_discount_mm_button_code'] = $this->request->post['social_discount_mm_button_code'];
		} else {
			if ($this->config->get('social_discount_mm_button_code') != null) {
				$this->data['social_discount_mm_button_code'] = $this->config->get('social_discount_mm_button_code');
			} else {
				$this->data['social_discount_mm_button_code'] = <<<HTML
<a target="_blank" class="mrc__plugin_uber_like_button" href="http://connect.mail.ru/share" data-mrc-config="{'cm' : '1', 'sz' : '20', 'st' : '1', 'tp' : 'mm'}">Нравится</a>
<script src="http://cdn.connect.mail.ru/js/loader.js" type="text/javascript" charset="UTF-8"></script>
HTML;
			}
		}
		
		if (isset($this->request->post['social_discount_ok_button_code'])) {
			$this->data['social_discount_ok_button_code'] = $this->request->post['social_discount_ok_button_code'];
		} else {
			if ($this->config->get('social_discount_ok_button_code') != null) {
				$this->data['social_discount_ok_button_code'] = $this->config->get('social_discount_ok_button_code');
			} else {
				$this->data['social_discount_ok_button_code'] = <<<HTML
<div id="ok_shareWidget"></div>
<script>
!function (d, id, did, st) {
  var js = d.createElement("script");
  js.src = "http://connect.ok.ru/connect.js";
  js.onload = js.onreadystatechange = function () {
  if (!this.readyState || this.readyState == "loaded" || this.readyState == "complete") {
	if (!this.executed) {
	  this.executed = true;
	  setTimeout(function () {
		OK.CONNECT.insertShareWidget(id,did,st);
	  }, 0);
	}
  }};
  d.documentElement.appendChild(js);
}(document,"ok_shareWidget",document.URL,"{width:145,height:30,st:'oval',sz:20,ck:1}");
</script>
HTML;
			}
		}
		
		if (isset($this->request->post['social_discount_tw_button_code'])) {
			$this->data['social_discount_tw_button_code'] = $this->request->post['social_discount_tw_button_code'];
		} else {
			if ($this->config->get('social_discount_tw_button_code') != null) {
				$this->data['social_discount_tw_button_code'] = $this->config->get('social_discount_tw_button_code');
			} else {
				$this->data['social_discount_tw_button_code'] = <<<HTML
<a href="https://twitter.com/share" class="twitter-share-button" >Tweet</a>
<script type="text/javascript" charset="utf-8">
window.twttr = (function (d,s,id) {
var t, js, fjs = d.getElementsByTagName(s)[0];
if (d.getElementById(id)) return; js=d.createElement(s); js.id=id;
js.src="//platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs);
return window.twttr || (t = { _e: [], ready: function(f){ t._e.push(f) } });
}(document, "script", "twitter-wjs"));
</script>
HTML;
			}
		}
		/*
		$anyProduct = $this->db->query('SELECT product_id FROM ' . DB_PREFIX . 'product LIMIT 0,1');
		if ($anyProduct->row) {
			$this->data['social_discount_preview_product'] = '/index.php?route=product/product&product_id=' . $anyProduct->row['product_id'];
		} else {
			$this->data['social_discount_preview_product'] = false;
		}
		*/
		$this->template = 'total/social_discount.tpl';
		$this->children = array(
			'common/header',
			'common/footer'
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'total/social_discount')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>