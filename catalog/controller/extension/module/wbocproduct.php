<?php
class ControllerExtensionModuleWbocProduct extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/wbocproducts');
		
		$this->load->model('catalog/product');
		$this->load->model('extension/module/wbocproduct');

		$this->load->model('tool/image');

		$data['products'] = array();

		$this->load->model('localisation/language');
		
		$data['code'] = $this->session->data['language'];
		$store_id = $this->config->get('config_store_id');
		
		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if($setting['option'] == 0) {
			if (!empty($setting['product'])) {
				$results = array();
				$products = array_slice($setting['product'], 0, (int)$setting['limit']);
				foreach ($products as $product_id) {
					$results[] = $this->model_catalog_product->getProduct($product_id);
				}
			}
			
		} else if ($setting['option']==1){
			if($setting['productfrom']==1){
				$data['filter_category_id'] = $setting['cate_id'];
				$results = $this->model_catalog_product->getProducts($data);
				
			} else if($setting['productfrom']==0) {
				if (!empty($setting['productcate'])) {
					$products = array_slice($setting['productcate'], 0, (int)$setting['limit']);
					foreach ($products as $product_id) {
						$results[] = $this->model_catalog_product->getProduct($product_id);
					}
				}			
			} else {
				if ($setting['input_specific_product']==0){
					$data['products'] = array();
					$filter_data = array(
						'filter_category_id' => $setting['cate_id'],
						'sort'  => 'p.date_added',
						'order' => 'DESC',
						'start' => 0,
						'limit' => $setting['limit'],
					);
					$results = $this->model_catalog_product->getProducts($filter_data);
						
				} else if ($setting['input_specific_product']==1){
					$filter_data = array(
					'sort'  => 'pd.name',
					'order' => 'ASC',
					'start' => 0,
					'limit' => $setting['limit']
					);
					$results = $this->model_extension_module_wbocproduct->getProductSpecialsCategory($filter_data, $setting['cate_id']);		
				} else if ($setting['input_specific_product']==2){
					$data['products'] = array();
					$results = $this->model_extension_module_wbocproduct->getBestSellerProductsCategory($setting['limit'], $setting['cate_id']);				
				} else{
					$data['products'] = array();
					$results = $this->model_extension_module_wbocproduct->getMostViewedProductsCategory($setting['limit'],  $setting['cate_id']);		
				}
			}
	
		} else {

			if ($setting['autoproduct']==0){
				$data['products'] = array();

				$filter_data = array(
					'sort'  => 'p.date_added',
					'order' => 'DESC',
					'start' => 0,
					'limit' => $setting['limit']
				);
				$results = $this->model_catalog_product->getProducts($filter_data);
					
			} else if ($setting['autoproduct']==1){
				$filter_data = array(
				'sort'  => 'pd.name',
				'order' => 'ASC',
				'start' => 0,
				'limit' => $setting['limit']
				);

				$results = $this->model_catalog_product->getProductSpecials($filter_data);
					
			} else if ($setting['autoproduct']==2){
					$data['products'] = array();

				$results = $this->model_catalog_product->getBestSellerProducts($setting['limit']);
				
			} else if ($setting['autoproduct']==3){
					$data['products'] = array();

				$results = $this->model_catalog_product->getPopularProducts($setting['limit']);
				
			} else{
				$data['products'] = array();

				$results = $this->model_extension_module_wbocproduct->getDealProducts($setting['limit']);		
				
			}		
		}


		/* Get new product */
		$this->load->model('catalog/product');

		$filter_data = array(
			'sort'  => 'p.date_added',
			'order' => 'DESC',
			'start' => 0,
			'limit' => 10
		);

		$new_results = $this->model_catalog_product->getProducts($filter_data);
		/* End */

		if ($results) {
			foreach ($results as $result) {
				$text_percent = '';
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $setting['width'], $setting['height']);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$price_num = $this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$price = false;
					$price_num= false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$special_num = $this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax'));
				} else {
					$special = false;
					$special_num = false;
				}
				
				if ($special_num && $price_num) {
					$percent =  round(($price_num-$special_num)/$price_num*100,0);
					$text_percent =  sprintf($this->language->get('text_label_sale'), $percent);
				}
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$date_end = false;
				if ($setting['countdown']){
					$date_end = $this->model_extension_module_wbocproduct->getSpecialCountdown($result['product_id']);
					if ($date_end === '0000-00-00') {
						$date_end = false;
					}
				}

				$webi_data['webi_images'] = array();
				$webi_results = $this->model_catalog_product->getProductImages($result['product_id']);

				

				foreach ($webi_results as $webi_result) {
					$webi_data['webi_images'][] = array('popup' => $this->model_tool_image->resize($webi_result['image'],$setting['width'], $setting['height']));
				}
				
				/* End */

				$is_new = false;
				if ($new_results) { 
					foreach($new_results as $new_r) {
						if($result['product_id'] == $new_r['product_id']) {
							$is_new = true;
						}
					}
				}

				if(strtotime($result['date_added']) > (time() - (60*60*24*10) )){
					    $is_new = true;
					} else {
					    $is_new = false;
				}

				if ($result['quantity'] <= 0) {
				$data['stock'] = $result['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $result['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}

				if($result['special'] > 0 AND $result['special'] != NULL ){
					$tag_per = ($result['special']*100)/$result['price'];
					$tag_per = round($tag_per);
					if($tag_per == 0){
					$tag_per = 1;
					}else{
					$tag_per = 100-$tag_per;
					}
					$tag = $result['price'] - $result['special'];
					}else{
					$tag = 0;
					$tag_per = 0;
					}
				
				$c_words = 50;
				$result['name'] = strlen($result['name']) > $c_words ? substr($result['name'],0,$c_words)."..." : $result['name'];
				
				$data['products'][] = array(
					'manufacturer'  => $result['manufacturer'],
					'manufacturer_id' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id']),
					'is_new'      => $is_new,
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 80) . '...',
					'price'       => $price,
					'tag_per'     => $tag_per,
					'quantity'    => $result['quantity'],
					'special'     => $special,
					'text_percent'  => $text_percent,
					'tax'         => $tax,
					'rating'      => $rating,
					// Add images Data 
					'webi_images' => $webi_data['webi_images'],
					//End
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id']),
					'date_end'    => $date_end,
					'is_new'      => $is_new,
					'manufacturer' => $result['manufacturer'],
					'manufacturers' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $result['manufacturer_id'])
				);
			}
		}
		$number_random = rand ( 1 , 1000 );
		$data['config_module'] = array(
				'name' => $setting['name'],
				'countdown' => (int) $setting['countdown'],
				'module_id' => $number_random
			);
			if (isset($setting['module_description'][$this->config->get('config_language_id')])) {
				$data['module_description'] = html_entity_decode($setting['module_description'][$this->config->get('config_language_id')]['description'], ENT_QUOTES, 'UTF-8');
				if ($data['module_description'] == '<p><br><p>') $data['module_description']= '';
		   }
			//echo '<pre>'; print_r($data['config_module']); die;

		if ($data['products']) {
			return $this->load->view('extension/module/wbocproduct', $data);
		}
		
	}
	
	
}