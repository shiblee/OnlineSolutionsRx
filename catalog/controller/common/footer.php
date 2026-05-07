<?php

class ControllerCommonFooter extends Controller {

	public function index() {

		$this->load->language('common/footer');



		$this->load->model('catalog/information');



		$data['informations'] = array();



		$data['footer_left'] = $this->load->controller('common/footer_left');

		$data['footer_right'] = $this->load->controller('common/footer_right');

		$data['ftop_full'] = $this->load->controller('common/ftop_full');

		$data['fbottom_full'] = $this->load->controller('common/fbottom_full');



		foreach ($this->model_catalog_information->getInformations() as $result) {

			if ($result['bottom']) {

				$data['informations'][] = array(

					'title' => $result['title'],

					'href'  => $this->url->link('information/information', 'information_id=' . $result['information_id'])

				);

			}

		}



		$data['contact'] = $this->url->link('information/contact');

		$data['return'] = $this->url->link('account/return/add', '', true);

		// $data['sitemap'] = $this->url->link('information/sitemap');
		$data['sitemap'] = 'https://www.onlinesolutionsrx.com/sitemap.xml';


		$data['tracking'] = $this->url->link('information/tracking');

		$data['manufacturer'] = $this->url->link('product/manufacturer');

		$data['voucher'] = $this->url->link('account/voucher', '', true);

		$data['affiliate'] = $this->url->link('affiliate/login', '', true);

		$data['special'] = $this->url->link('product/special');

		$data['account'] = $this->url->link('account/account', '', true);

		$data['order'] = $this->url->link('account/order', '', true);

		$data['wishlist'] = $this->url->link('account/wishlist', '', true);

		$data['newsletter'] = $this->url->link('account/newsletter', '', true);
		$data['about-us'] = $this->url->link('information/information', 'information_id=4');
		$data['delivery-Information'] = $this->url->link('information/information', 'information_id=6');
		$data['privacy-policy'] = $this->url->link('information/information', 'information_id=3');
		$data['terms-conditions'] = $this->url->link('information/information', 'information_id=5');
		$data['faq'] = $this->url->link('information/information', 'information_id=7');




		$data['powered'] = sprintf($this->language->get('text_powered'), $this->config->get('config_name'), date('Y', time()));





			if ($this->request->server['HTTPS']) {

			$server = $this->config->get('config_ssl');

		} else {

			$server = $this->config->get('config_url');

		}

		if (is_file(DIR_IMAGE . $this->config->get('config_logo'))) {

			$data['logo'] = $server . 'image/' . $this->config->get('config_logo');

		} else {

			$data['logo'] = '';

		}

		// Whos Online

		if ($this->config->get('config_customer_online')) {

			$this->load->model('tool/online');



			if (isset($this->request->server['REMOTE_ADDR'])) {

				$ip = $this->request->server['REMOTE_ADDR'];

			} else {

				$ip = '';

			}



			if (isset($this->request->server['HTTP_HOST']) && isset($this->request->server['REQUEST_URI'])) {

				$url = ($this->request->server['HTTPS'] ? 'https://' : 'http://') . $this->request->server['HTTP_HOST'] . $this->request->server['REQUEST_URI'];

			} else {

				$url = '';

			}



			if (isset($this->request->server['HTTP_REFERER'])) {

				$referer = $this->request->server['HTTP_REFERER'];

			} else {

				$referer = '';

			}



			$this->model_tool_online->addOnline($ip, $this->customer->getId(), $url, $referer);

		}



		$data['scripts'] = $this->document->getScripts('footer');

		

		return $this->load->view('common/footer', $data);

	}

}

