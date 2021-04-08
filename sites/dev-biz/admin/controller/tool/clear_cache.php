<?php 
class ControllerToolClearCache extends Controller {
	private $error = array();
	
	public function index() {
        if ($this->validate()){
			$this->data['home'] = $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL');
				$this->data['files'] = $this->clearCache();
            $this->data['success'] = 'Кэш очищен';
            $this->template = 'tool/clear_cache.tpl';
            $this->children = array(
                'common/header',
                'common/footer',
            );
            $this->response->setOutput($this->render());
        }
	}

    private function clearCache() {
        $returnFiles = "";

        if (file_exists('../filter_cache/')) {
            $files = array();
            foreach (glob('../filter_cache/*') as $file) {
                $files[] = $file;
                unlink($file);
            }
            $returnFiles .= "<div>Удалено файлов кэша фильтра: " . count($files) . "</div>";
            unset($files);
        }



        if (file_exists('../cache/product/')) {
            $files = array();
            foreach (glob('../cache/product/*') as $dir) {
                foreach (glob($dir.'/*') as $file) {
                    $files[] = $file;
                    unlink($file);
                }
                rmdir($dir);
            }
            $returnFiles .= "<div>Удалено файлов кэша товаров: " . count($files) . "</div>";
            unset($files);
        }


        if (file_exists('../cache/categories/')) {
            $files = array();
            foreach (glob('../cache/categories/*') as $file) {
                $files[] = $file;
                unlink($file);
            }
            $returnFiles .= "<div>Удалено файлов кэша категорий: " . count($files) . "</div>";
            unset($files);
        }
		
        if (file_exists('../system/cache/')) {
            $files = array();
            foreach (glob('../system/cache/*seo*') as $file) {
                $files[] = $file;
                unlink($file);
            }
            $returnFiles .= "<div>Удалено файлов кэша системы: " . count($files) . "</div>";
            unset($files);
        }


        return $returnFiles;
    }

	private function validate() {
		if (!$this->user->hasPermission('modify', 'tool/clear_cache')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>