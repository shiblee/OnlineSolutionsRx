<?php
class ModelExtensionModuleContactMessages extends Model {
    public function getMessages($start = 0, $limit = 10) {
        if ($start < 0) $start = 0;
        if ($limit < 1) $limit = 10;

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "contact_messages ORDER BY date_added DESC LIMIT " . (int)$start . "," . (int)$limit);
        return $query->rows;
    }

    public function getTotalMessages() {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "contact_messages");
        return $query->row['total'];
    }
}

