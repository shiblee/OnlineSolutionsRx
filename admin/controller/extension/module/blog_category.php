<?php
class ControllerExtensionModuleBlogCategory extends Controller {
    private $error = [];

    public function index() {
        $this->load->language('extension/module/blog_category');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/blog_category');

        $this->getList();
    }


    public function add() {
        $this->load->language('extension/module/blog_category');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/blog_category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_blog_category->addCategory($this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/blog_category', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function edit() {
        $this->load->language('extension/module/blog_category');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/blog_category');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
            $this->model_extension_module_blog_category->editCategory($this->request->get['category_id'], $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->url->link('extension/module/blog_category', 'user_token=' . $this->session->data['user_token'], true));
        }

        $this->getForm();
    }

    public function delete() {
        $this->load->language('extension/module/blog_category');
        $this->document->setTitle($this->language->get('heading_title'));
        $this->load->model('extension/module/blog_category');

      if (isset($this->request->get['category_id'])) {
    $this->model_extension_module_blog_category->deleteCategory($this->request->get['category_id']);
    $this->session->data['success'] = $this->language->get('text_success');
}


        $this->response->redirect($this->url->link('extension/module/blog_category', 'user_token=' . $this->session->data['user_token'], true));
    }

    protected function getList() {
        $data['categories'] = [];

        $results = $this->model_extension_module_blog_category->getblogCategories();

      foreach ($results as $result) {
  $data['categories'][] = [
    'category_id' => $result['category_id'],
    'name'        => $result['name'],
    'status'      => $result['status'],
    'edit'        => $this->url->link('extension/module/blog_category/edit', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'], true),
    'delete'      => $this->url->link('extension/module/blog_category/delete', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $result['category_id'], true)
];

}


        $data['add'] = $this->url->link('extension/module/blog_category/add', 'user_token=' . $this->session->data['user_token'], true);
        $data['delete'] = $this->url->link('extension/module/blog_category/delete', 'user_token=' . $this->session->data['user_token'], true);
        $data['user_token'] = $this->session->data['user_token'];

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/blog_category_list', $data));
    }

    protected function getForm() {
        $data['user_token'] = $this->session->data['user_token'];

        if (isset($this->request->get['category_id'])) {
            $data['action'] = $this->url->link('extension/module/blog_category/edit', 'user_token=' . $this->session->data['user_token'] . '&category_id=' . $this->request->get['category_id'], true);
        } else {
            $data['action'] = $this->url->link('extension/module/blog_category/add', 'user_token=' . $this->session->data['user_token'], true);
        }

        $data['cancel'] = $this->url->link('extension/module/blog_category', 'user_token=' . $this->session->data['user_token'], true);

        if (isset($this->request->get['category_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
            $category_info = $this->model_extension_module_blog_category->getCategory($this->request->get['category_id']);
        }

        $this->load->model('localisation/language');
        $data['languages'] = $this->model_localisation_language->getLanguages();

        if (isset($this->request->post['category_description'])) {
            $data['category_description'] = $this->request->post['category_description'];
        } elseif (isset($this->request->get['category_id'])) {
            $data['category_description'] = $this->model_extension_module_blog_category->getCategoryDescriptions($this->request->get['category_id']);
        } else {
            $data['category_description'] = [];
        }

        if (isset($this->request->post['status'])) {
            $data['status'] = $this->request->post['status'];
        } elseif (!empty($category_info)) {
            $data['status'] = $category_info['status'];
        } else {
            $data['status'] = 1;
        }

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/blog_category_form', $data));
    }

    protected function validateForm() {
        if (!$this->user->hasPermission('modify', 'extension/module/blog_category')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        foreach ($this->request->post['category_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
                $this->error['name'][$language_id] = $this->language->get('error_name');
            }
        }

        return !$this->error;
    }
}
