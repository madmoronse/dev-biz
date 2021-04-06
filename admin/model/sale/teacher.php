<?php
class ModelSaleTeacher extends Model{
    public function addTeacher($customer_id)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "teacher SET customer_id = '" . $customer_id . "'");
    }


    public function editTeacher($data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "teacher SET customer_id = '" . $this->db->escape($data['customer_id']) . "' 
                        WHERE teacher_id  = '" . (int)$this->db->escape($data['teacher_id']) . "'");
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

    public function getTeachers()
    {
        $teacher_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "teacher");
        foreach ($query->rows as $value) {
            $teacher_data[$value['teacher_id']] = array(
                'teacher_id'   => $value['teacher_id'],
                'customer_id'  => $value['customer_id']
            );
        }
        return $teacher_data;
    }

    public function getTeacher($teacher_id)
    {
        $teacher_query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "teacher WHERE teacher_id = '" . (int)$teacher_id . "'");

        if(empty($teacher_query->row)) {
            return false;
        }

        $teacher_data = array(
            'email' => $teacher_query->row['email'],
            'name'  => $teacher_query->row['name'],
            'code'  => $teacher_query->row['code'],
            'customer_id'  => $teacher_query->row['customer_id']
        );

        return $teacher_data;
    }


   

    public function getStudents($teacher_id)
    {
        $student_data = array();

        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE teacher_id = '" . (int)$teacher_id . "'");
        foreach ($query->rows as $value) {
            $student_data[$value['customer_id']] = array(
                'customer_id'   => $value['customer_id'],
                'email'         => $value['email'],
                'firstname'          => $value['firstname'],
                'middlename'          => $value['middlename'],
                'lastname'          => $value['lastname'],
                'phone'         => $value['telephone']
            );
        }
        return $student_data;
    }



    public function deleteTeacher($teacher_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "teacher WHERE teacher_id = '" . (int)$teacher_id . "'");
    }


    public function getTotalTeachers()
    {
        $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "teacher ");

        return $query->row['total'];
    }
}