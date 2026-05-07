<?php
class ControllerProductTop10 extends Controller {
    public function index() {
        
        $this->load->language('product/special');

        $this->document->setTitle('Top 10 Products');

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $this->load->model('catalog/category');

        $filter_data = array(
            'sort'  => 'p.viewed', // or p.quantity, or any other logic
            'order' => 'DESC',
            'start' => 0,
            'limit' => 10
        );

        $results = $this->model_catalog_product->getProducts($filter_data);

        $data['products'] = array();

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_default_image_product_width'), $this->config->get('theme_default_image_product_height'));
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_default_image_product_width'), $this->config->get('theme_default_image_product_height'));
            }

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $price = false;
            }

            if ((float)$result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            } else {
                $special = false;
            }

            if ($this->config->get('config_tax')) {
                $tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
            } else {
                $tax = false;
            }

            if ($this->config->get('config_review_status')) {
                $rating = (int)$result['rating'];
            } else {
                $rating = false;
            }

            $data['products'][] = array(
                'product_id'  => $result['product_id'],
                'thumb'       => $image,
                'name'        => $result['name'],
                'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
                'price'       => $price,
                'special'     => $special,
                'tax'         => $tax,
                'rating'      => $rating,
                'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
            );
        }

        $data['breadcrumbs'] = array(
            array(
                'text' => $this->language->get('text_home'),
                'href' => $this->url->link('common/home')
            ),
            array(
                'text' => 'Top 10 Products',
                'href' => $this->url->link('product/top10')
            )
        );

        $data['heading_title'] = 'Top 10 Products';
        $data['text_empty'] = $this->language->get('text_empty');

        $data['button_cart'] = $this->language->get('button_cart');
        $data['button_wishlist'] = $this->language->get('button_wishlist');
        $data['button_compare'] = $this->language->get('button_compare');

        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('product/top10', $data));
    }
}
