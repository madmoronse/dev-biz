<?php
class ModelAccountTeacher extends Model {
    public function getTeachers()
    {
        $teacher_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "teacher");
        foreach ($query->rows as $value) {
            $teacher_data[$value['teacher_id']] = array(
                'teacher_id'     => $value['teacher_id'],
                'customer_id' => $value['customer_id']
            );
        }
        return $teacher_data;
    }
    
    public function getTeacherInfo($customer_id)
    {
        $customer_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");

        if(empty($customer_query->row)) {
            return false;
        }

        $customer_data = array(
            'email' => $customer_query->row['email'],
            'firstname'  => $customer_query->row['firstname'],
            'middlename'  => $customer_query->row['middlename'],
            'lastname'  => $customer_query->row['lastname']
        );

        return $customer_data;
    }

    public function getTeacher($teacher_id)
    {
        $teacher_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "teacher WHERE teacher_id = '" . (int)$teacher_id . "'");

        $teacher_data = array(
            'customer_id'  => $teacher_query->row['customer_id']
        );

        return $teacher_data;
    }

    public function getTeacherForCustomerId($customer_id)
    {
        $teacher_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "teacher WHERE customer_id = '" . (int)$customer_id . "'");

        $teacher_data = array(
            'teacher_id' => $teacher_query->row['teacher_id']
        );

        return $teacher_data;
    }

    public function getTotalOrderByCustomerId($customer_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['total'];
    }

    public function getTotalSumOrderByCustomerId($customer_id) {
        $query = $this->db->query("SELECT SUM(total) AS totalSum FROM " . DB_PREFIX . "order WHERE customer_id = '" . (int)$customer_id . "'");

        return $query->row['totalSum'];
    }

    public function getStudents($teacher_id)
    {
        $student_data = array();

        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE teacher_id = '" . (int)$teacher_id . "'");

        foreach ($query->rows as $value) {
            $student_data[$value['customer_id']] = array(
                'customer_id'   => $value['customer_id'],
                'email'         => $value['email'],
                'firstname'     => $value['firstname'],
                'middlename'    => $value['middlename'],
                'lastname'      => $value['lastname'],
                'phone'         => $value['telephone'],
                'totalSum'      => $this->getTotalSumOrderByCustomerId($value['customer_id']),
                'total'         => $this->getTotalOrderByCustomerId($value['customer_id'])
            );
        }
        return $student_data;
    }

    public function getTotalStudents($teacher_id) {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer WHERE teacher_id = '" . (int)$teacher_id . "'");

        return $query->row['total'];
    }




}