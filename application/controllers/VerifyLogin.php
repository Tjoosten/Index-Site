<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class VerifyLogin extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();

    $this->load->model('User', '', TRUE);
    $this->load->helper(['string', 'url']);

    $this->load->library(['email', 'form_validation']);
  }

  public function index()
  {
    $this->form_validation->set_rules('username', 'Username', 'trim|required');
    $this->form_validation->set_rules('password', 'Password', 'trim|required|callback_check_database');

    if ($this->form_validation->run() == FALSE)
    {
      $this->load->view();
      $this->load->view();
      $this->load->view();
      $this->load->view();
    }
    else
    {
      redirect('backend', 'refresh');
    }
  }

  public function check_database($password)
  {
    $username = $this->input->post('username');
    $result   = $this->user->login($username, $password);

    if ($result)
    {
      $auth       = [];
      $permission = [];

      foreach ($result as $row) {
        $auth['id']       => $row->id;
        $auth['username'] => $row->username;
        $auth['admin']    => $row->admin_role;
        $auth['theme']    => $row->theme;
        $auth['email']    => $row->mail;

        $this->session->set_userdata('logged_in', $auth);
        $permissions = $this->user->permissions($auth['id']);

        foreach ($permissions as $perm)
        {
          $permission['user_id']  = $auth['id'];
          $permission['profiles'] = $perm->profiles;
        }

        $this->session->set_userdata('permissions', $permission);
      }

      return TRUE;
    }
    else
    {
      $this->form_validation->set_message('check_database', 'Wrong username or password');
      return FALSE;
    }
  }

  public function reset()
  {
    $output = $this->user->reset_pass();

    if (count($output) == 1)
    {
      $password['new'] = random_string('alnum', 16);
      $mail            = $this->load->view('email/reset', $password, TRUE);

      $this->user->reset_pass($password);

      $this->email->from('noreply@activsme.be', 'Noreply activsme.be');
      $this->email->to($this->input->post('recovery'));
      $this->email->subject('Reset password - activisme.be');
      $this->email->message($email);
      $this->email->set_mailtype('html');
      $this->email->send();
      $this->email->clear();

      $this->load->view('alerts/reset_success');
    }
    else
    {
      $this->load->view('alerts/reset_failed');
    }
  }
}
