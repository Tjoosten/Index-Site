<?php

class User extends CI_Model
{
  public function __construct()
  {
    parent::_constrcut();
  }

  public function login($username, $password)
  {
    $this->db->select('*');
    $this->db->from('users');
    $this->db->where('Mail = ' . "'" . $username . "'");
    $this->db->where('password = ' . "'" . MD5($password) . "'");
    $this->db->where('Blocked', 0);
    $this->db->limit(1);

    $query = $this->db->get();

    if ($query->num_rows() == 1)
    {
      return $query->result();
    }
    else
    {
      return FALSE;
    }

    public function reset_pass($password)
    {
      $db['password'] = md5($password['new']);

      $this->db->where('mail', $this->input->post('recovery'));
      $this->db->update('users', $db);
    }

    public function permission($userid)
    {
      $this->db->select('*');
      $this->db->from('Permissions');
      $this->db->where('user_id', $userid);

      $query = $this->db->get();

      if ($query->num_rows() == 1)
      {
        return $query->result();
      }
      else
      {
        return false;
      }
    }
  }
}
