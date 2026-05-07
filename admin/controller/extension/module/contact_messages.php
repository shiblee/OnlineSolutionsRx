<?php
class ControllerExtensionModuleContactMessages extends Controller {
    public function index() {
        $this->load->language('extension/module/contact_messages');
        $this->document->setTitle('Contact Messages');

        $this->load->model('extension/module/contact_messages');
        $results = $this->model_extension_module_contact_messages->getMessages();

        $data['messages'] = array();

        foreach ($results as $result) {
            $data['messages'][] = array(
                'name'       => $result['name'],
                'email'      => $result['email'],
                'enquiry'    => $result['enquiry'],
                'date_added' => date('d-m-Y h:i A', strtotime($result['date_added']))
            );
        }
        // echo "<pre>";print_r($data);exit;

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/contact_messages', $data));
    }
}
