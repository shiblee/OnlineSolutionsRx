<?php
class ModelInformationContact extends Model {
    public function saveContact($data) {
        date_default_timezone_set('Asia/Kolkata'); 

        $date_added = date('Y-m-d H:i:s'); 

        $this->db->query("INSERT INTO " . DB_PREFIX . "contact_messages SET name = '" . $this->db->escape($data['name']) . "', email = '" . $this->db->escape($data['email']) . "', enquiry = '" . $this->db->escape($data['enquiry']) . "', date_added = '" . $this->db->escape($date_added) . "'");
    }
}
