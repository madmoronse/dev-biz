<?php
class ModelAccountComment extends Model {
	public function addComment($data) {
		$this->db->query("INSERT INTO " . DB_PREFIX . "comment SET customer_id = '" . (int)$this->customer->getId() .
                        "', title = '" . $this->db->escape($data['title']) . "', content = '" .
                        $this->db->escape($data['content']) . "'");
	}

	public function editComment($comment_id, $data) {
		$this->db->query("UPDATE " . DB_PREFIX . "comment SET title = '" . $this->db->escape($data['title']) . "', 
                        content = '" . $this->db->escape($data['content']) . "' WHERE comment_id  = '" . (int)$comment_id . "' 
                        AND customer_id = '" . (int)$this->customer->getId() . "'");
	}

	public function getComments() {
		$comment_data = array();

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "comment WHERE customer_id = '" . (int)$this->customer->getId() . "'");
		foreach ($query->rows as $value) {
			$comment_data[$value['comment_id']] = array(
				'comment_id'     => $value['comment_id'],
				'title'      => $value['title'],
				'content'     => $value['content']
			);
		}
		return $comment_data;
	}

	public function getComment($comment_id) {
		$comment_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "comment WHERE comment_id = '" . (int)$comment_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		$comment_data = array(
			'title'      => $comment_query->row['title'],
			'content'    => $comment_query->row['content']
		);

		return $comment_data;
	}


	public function deleteComment($comment_id) {
		$this->db->query("DELETE FROM " . DB_PREFIX . "comment WHERE comment_id = '" . (int)$comment_id . "' 
		                AND customer_id = '" . (int)$this->customer->getId() . "'");
	}


	public function getTotalComments() {
		$query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "comment WHERE customer_id = '" .
                                (int)$this->customer->getId() . "'");

		return $query->row['total'];
	}
}