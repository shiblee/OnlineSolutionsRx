<?php
class ControllerProductLatestall extends Controller {
    public function index() {
        $this->load->language('product/category');

        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        $this->document->setTitle('Latest Products');

        if (isset($this->request->get['sort'])) {
            $sort = $this->request->get['sort'];
        } else {
            $sort = 'p.date_added';
        }

        if (isset($this->request->get['order'])) {
            $order = $this->request->get['order'];
        } else {
            $order = 'DESC';
        }

        if (isset($this->request->get['page'])) {
            $page = (int)$this->request->get['page'];
        } else {
            $page = 1;
        }

        if (isset($this->request->get['limit'])) {
            $limit = (int)$this->request->get['limit'];
        } else {
            $limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
        }

        $data['breadcrumbs'] = [];
        $data['breadcrumbs'][] = [
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/home')
        ];

        $data['breadcrumbs'][] = [
            'text' => 'Latest Products',
            'href' => $this->url->link('product/latestall')
        ];

        $filter_data = [
            'sort'  => $sort,
            'order' => $order,
            'start' => ($page - 1) * $limit,
            'limit' => $limit
        ];

        $product_total = $this->model_catalog_product->getTotalProducts($filter_data);
        $results = $this->model_catalog_product->getProducts($filter_data);

        $data['products'] = [];

        foreach ($results as $result) {
            if ($result['image']) {
                $image = $this->model_tool_image->resize($result['image'], 300, 300);
            } else {
                $image = $this->model_tool_image->resize('placeholder.png', 300, 300);
            }

            $price = false;
            $special = false;

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
                $price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            }

            if ((float)$result['special']) {
                $special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
            }

            $data['products'][] = [
                'product_id' => $result['product_id'],
                'thumb'      => $image,
                'name'       => $result['name'],
                'description'=> utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, 100) . '..',
                'price'      => $price,
                'special'    => $special,
                'rating'     => $result['rating'],
                'href'       => $this->url->link('product/product', 'product_id=' . $result['product_id'])
            ];
        }

        $url = '';
        if (isset($this->request->get['sort'])) {
            $url .= '&sort=' . $this->request->get['sort'];
        }
        if (isset($this->request->get['order'])) {
            $url .= '&order=' . $this->request->get['order'];
        }
        if (isset($this->request->get['limit'])) {
            $url .= '&limit=' . $this->request->get['limit'];
        }

        $data['sorts'] = [];

        $data['sorts'][] = [
            'text'  => $this->language->get('text_default'),
            'value' => 'p.date_added-DESC',
            'href'  => $this->url->link('product/latestall', 'sort=p.date_added&order=DESC' . $url)
        ];

        $data['sorts'][] = [
            'text'  => $this->language->get('text_name_asc'),
            'value' => 'pd.name-ASC',
            'href'  => $this->url->link('product/latestall', 'sort=pd.name&order=ASC' . $url)
        ];

        $data['sorts'][] = [
            'text'  => $this->language->get('text_price_desc'),
            'value' => 'p.price-DESC',
            'href'  => $this->url->link('product/latestall', 'sort=p.price&order=DESC' . $url)
        ];

        $data['limits'] = [];
        foreach ([25, 50, 75, 100] as $value) {
            $data['limits'][] = [
                'text'  => $value,
                'value' => $value,
                'href'  => $this->url->link('product/latestall', 'limit=' . $value . $url)
            ];
        }

        $pagination = new Pagination();
        $pagination->total = $product_total;
        $pagination->page = $page;
        $pagination->limit = $limit;
        $pagination->url = $this->url->link('product/latestall', $url . '&page={page}');
        $data['pagination'] = $pagination->render();
        $data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * $limit) + 1 : 0, ((($page - 1) * $limit) > ($product_total - $limit)) ? $product_total : ((($page - 1) * $limit) + $limit), $product_total, ceil($product_total / $limit));

        $data['sort']  = $sort;
        $data['order'] = $order;
        $data['limit'] = $limit;

        // Layout render
        $data['heading_title'] = 'Latest Products';
        $data['compare'] = $this->url->link('product/compare');

        $data['continue'] = $this->url->link('common/home');

        $data['column_left']    = $this->load->controller('common/column_left');
        $data['column_right']   = $this->load->controller('common/column_right');
        $data['content_top']    = $this->load->controller('common/content_top');
        $data['content_bottom'] = $this->load->controller('common/content_bottom');
        $data['footer']         = $this->load->controller('common/footer');
        $data['header']         = $this->load->controller('common/header');

        $this->response->setOutput($this->load->view('product/latestall', $data));
    }
}
