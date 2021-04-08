<?php
class ModelDesignSlimBanner extends Model {
    public function setHit() {
        $query = $this->db->query(
            "UPDATE `" . DB_PREFIX . "setting` SET `value` = `value` + 1 WHERE `key` = 'slim_banner_hits'");
    }
}
?>