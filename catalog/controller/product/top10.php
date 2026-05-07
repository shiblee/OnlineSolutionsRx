<?php
class ControllerProductTop10 extends Controller {
    public function index() {
        $this->load->language('product/top10');
        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('product/top10')
        );

        // Sorting
        $url = '';

        $data['sorts'] = array();

        $data['sorts'][] = array(
        'text'  => $this->language->get('text_default'),
        'value' => 'p.date_added-DESC',
        'href'  => $this->url->link('product/top10', 'sort=p.date_added&order=DESC' . $url)
        );

        $data['sorts'][] = array(
        'text'  => $this->language->get('text_name_asc'),
        'value' => 'pd.name-ASC',
        'href'  => $this->url->link('product/top10', 'sort=pd.name&order=ASC' . $url)
        );

        $data['sorts'][] = array(
        'text'  => $this->language->get('text_name_desc'),
        'value' => 'pd.name-DESC',
        'href'  => $this->url->link('product/top10', 'sort=pd.name&order=DESC' . $url)
        );

        $data['sorts'][] = array(
        'text'  => $this->language->get('text_price_asc'),
        'value' => 'ps.price-ASC',
        'href'  => $this->url->link('product/top10', 'sort=ps.price&order=ASC' . $url)
        );

        $data['sorts'][] = array(
        'text'  => $this->language->get('text_price_desc'),
        'value' => 'ps.price-DESC',
        'href'  => $this->url->link('product/top10', 'sort=ps.price&order=DESC' . $url)
        );



        // Get selected sort/order from request
        $sort = isset($this->request->get['sort']) ? $this->request->get['sort'] : 'p.date_added';
        $order = isset($this->request->get['order']) ? $this->request->get['order'] : 'DESC';
        $page = isset($this->request->get['page']) ? (int)$this->request->get['page'] : 1;
        $limit = isset($this->request->get['limit']) ? (int)$this->request->get['limit'] : $this->config->get('theme_default_product_limit');


        $filter_data = array(
        'sort'  => $sort,
        'order' => $order,
        'start' => 0,
        'limit' => 10
        );

        $data['limits'] = [];

        $limits = array_unique(array(25, 50, 75, 100));
        sort($limits);

        foreach ($limits as $value) {
            $data['limits'][] = array(
                'text' => $value,
                'value' => $value,
                'href' => $this->url->link('product/top10', 'sort=' . $sort . '&order=' . $order . '&limit=' . $value)
            );
        }


        $results = $this->model_catalog_product->getProductSpecialsTop10($filter_data);

        $data['products'] = [];

        foreach ($results as $result) {
            $data['products'][] = array(
                'product_id' => $result['product_id'],
                'thumb'      => $this->model_tool_image->resize($result['image'], 200, 200),
                'name'       => $result['name'],
                'price'      => $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']),
                'special'    => $result['special'] ? $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']) : false,
                'href'       => $this->url->link('product/product', 'product_id=' . $result['product_id'])
            );
        }

        $data['heading_title'] = $this->language->get('heading_title');
        $data['continue'] = $this->url->link('common/home');

        // Load page parts
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['column_right'] = $this->load->controller('common/column_right');
        $data['content_top'] = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer'] = $this->load->controller('common/footer');
        $data['header'] = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('product/top10', $data));
    }
}
