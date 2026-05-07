<?php
class ModelExtensionModuleBlogCategory extends Model {
   public function getblogCategories() {
    $query = $this->db->query("SELECT c.category_id, cd.name, c.status FROM " . DB_PREFIX . "blog_category c 
    LEFT JOIN " . DB_PREFIX . "blog_category_description cd ON (c.category_id = cd.category_id) 
    WHERE cd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

    return $query->rows;
}

    public function addCategory($data) {
        $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category (status) VALUES ('" . (int)$data['status'] . "')");
        $category_id = $this->db->getLastId();

        foreach ($data['category_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "blog_category_description SET category_id = '" . (int)$category_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "'");
        }

        return $category_id;
    }

    public function deleteCategory($category_id) {
    // Delete category from main table
    $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category WHERE category_id = '" . (int)$category_id . "'");

    // Delete category descriptions from multi-language table
    $this->db->query("DELETE FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int)$category_id . "'");
}


// catalog/model/extension/module/blog_category.php ya admin/model/extension/module/blog_category.php
public function getCategoriesblog() {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_description WHERE language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY name ASC");

    return $query->rows;
}



public function getCategory($category_id) {
    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category WHERE category_id = '" . (int)$category_id . "'");
    return $query->row;
}

public function getCategoryDescriptions($category_id) {
    $category_description_data = [];

    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "blog_category_description WHERE category_id = '" . (int)$category_id . "'");

    foreach ($query->rows as $result) {
        $category_description_data[$result['language_id']] = [
            'name' => $result['name']
        ];
    }

    return $category_description_data;
}

public function editCategory($category_id, $data) {
    $this->db->query("UPDATE " . DB_PREFIX . "blog_category SET status = '" . (int)$data['status'] . "' WHERE category_id = '" . (int)$category_id . "'");

    foreach ($data['category_description'] as $language_id => $value) {
        $this->db->query("UPDATE " . DB_PREFIX . "blog_category_description SET name = '" . $this->db->escape($value['name']) . "' WHERE category_id = '" . (int)$category_id . "' AND language_id = '" . (int)$language_id . "'");
    }
}




}
