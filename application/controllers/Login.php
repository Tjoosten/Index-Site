<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class: Login controller
 *
 * @version 1.0.0
 */
class Login extends CI_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->database();
  }

  public function index()
  {
    $this->load->view();
    $this->load->view();
    $this->load->view();
    $this->load->view();
  }

  /**
   * @return redirect
   */
  public function logout()
  {
    redirect();
  }
}
