<?php
class ControllerExtensionModuleWbtoprateproduct extends Controller {
	public function index($setting) {
		$this->load->language('extension/module/wbtoprateproduct');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/owl.carousel.css');
		$this->document->addStyle('catalog/view/javascript/jquery/swiper/css/owl.theme.css');
		$this->document->addScript('catalog/view/javascript/jquery/swiper/js/owl.carousel.min.js');

		$data['products'] = array();

		if (!$setting['limit']) {
			$setting['limit'] = 4;
		}

		if (!empty($setting['product'])) {

			$products = array_slice($setting['product'], 0, (int)$setting['limit']);

			foreach ($products as $product_id) {
				$product_info = $this->model_catalog_product->getProduct($product_id);

				if ($product_info) {
					//Winter Infotech Start
					$options = array();
					foreach ($this->model_catalog_product->getProductOptions($product_id) as $option) {
						$product_option_value_data = array();
						foreach ($option['product_option_value'] as $option_value) {
							if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
								if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
									$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
									$raw_price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency'],null,false);
								} else {
									$price = false;
									$raw_price = false;
								}
								$product_option_value_data[] = array(
									'product_option_value_id' => $option_value['product_option_value_id'],
									'option_value_id'         => $option_value['option_value_id'],
									'name'                    => $option_value['name'],
									'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
									'price'                   => $price,
									'raw_price'               => $raw_price,
									'price_prefix'            => $option_value['price_prefix']
								);						
							}
						}
						$options[] = array(
							'product_id'           => $product_id,
							'product_option_id'    => $option['product_option_id'],
							'type'                 => $option['type'],
							'product_option_value' => $product_option_value_data,
							'name'                 => $option['name'],
							'value'                => $option['value'],
							'required'             => $option['required']
						); 
					}
					if (!is_null($product_info['special']) && (float)$product_info['special'] >= 0) {
						$discount = "-".round((($product_info['price'] - $product_info['special']) * 100) / $product_info['price'])."%";
					}
					else {
						$discount = false;
					}
					//Winter Infotech End
					if ($product_info['image']) {
						$image = $this->model_tool_image->resize($product_info['image'], $setting['width'], $setting['height']);
					} else {
						$image = $this->model_tool_image->resize('placeholder.png', $setting['width'], $setting['height']);
					}

					if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$price = false;
					}

					if ((float)$product_info['special']) {
						$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					} else {
						$special = false;
					}

					if ($this->config->get('config_tax')) {
						$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
					} else {
						$tax = false;
					}

					if ($this->config->get('config_review_status')) {
						$rating = $product_info['rating'];
					} else {
						$rating = false;
					}



					 /*Additional images start*/
                              
                            $more_images['images'] = array();
                            
                            $results = $this->model_catalog_product->getProductImages($product_info['product_id']);
                            
                            foreach ($results as $result){
                                    $more_images['images'][]=array(
                                        'popup_more' => $this->model_tool_image->resize($result['image'],$setting['width'], $setting['height'])
                                    );
                                    //print_r($more_images);
                            }
                            $more_images['product_id_images']=$product_info['product_id'];
                            
                    /*Additional images end*/


					$data['products'][] = array(
						'manufacturer'  => $product_info['manufacturer'],
						'manufacturer_id' => $this->url->link('product/manufacturer/info', 'manufacturer_id=' . $product_info['manufacturer_id']),
						'product_id'  => $product_info['product_id'],
						'quantity'    => $product_info['quantity'],
						'thumb'       => $image,
						'name'        => $product_info['name'],
						'description' => utf8_substr(strip_tags(html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
						'price'       => $price,
						'special'     => $special,
						'options'     => $options,
						'tax'         => $tax,
						'rating'      => $rating,
						  // Add images Data 
                                'more_images' => $more_images, //Additional images
                           //End
						'href'        => $this->url->link('product/product', 'product_id=' . $product_info['product_id'])
					);
				}
			}
		}

		if ($data['products']) {
			return $this->load->view('extension/module/wbtoprateproduct', $data);
		}
	}
}