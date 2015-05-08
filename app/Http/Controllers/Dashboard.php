<?php

namespace Http\Controllers;
use Http\Models\User;
use Core\app;
use System\Models\Auth;
use System\MVC\Controller;

if ( ! defined('ROOT_PATH') ) exit('No direct script access allowed');

class Dashboard extends Controller
{
    protected $level;
    protected $user;
    protected $label;
    protected $roleMask;
    protected $auth;
    public function __construct()
    {
        $this->auth = Auth::what('user');
        if(!$this->auth->has('authenticated')->session())
        {
            return app::redirect('users');
        }

        parent::__construct();
        $this->user = User::getAccount();
        $this->label = $this->user->label;
        $this->roleMask = User::getRole(true);
            if ($this->roleMask >= 3) { // if user is staff member (rank/level = 1), show this.
                    #return $this->logout();
                    return $this->showStaffPanel();
                } else {
                    return $this->showUserPanel();
                }
            }

    public function home(array $arguments = array(), $action)
    {
       return NULL; // this is a workaround getting redirected by default, inheritance from controller::home();.
    }

    public function logout()
    {
        User::logout();
       return app::redirect('home');
    }

    private function showUserPanel()
    {
        $this->view->render('Dashboard/Member/' . app::currentUrl(true) . '',array('title' => 'Bruger Panel',  'profile' => User::query("SELECT * FROM members INNER JOIN users ON members.uid = users.id WHERE members.uid = :id LIMIT 1", array(':id' => $this->user['id']))[0]));
    }

   private function showStaffPanel()
    {
        $token = $this->auth->get('token')->session();
        self::showPanel(app::currentUrl(), $token);
    }

   private function showPanel($url, $token)
    {
        if ($token) {
                if ($this->roleMask >= 4) {
                    $this->level = 'Admin';
                    return $this->view->render('Dashboard/' . $this->level . '/' . $url['1'] . '', array('title' => 'Admin Panel', 'profile' => User::query("SELECT * FROM members INNER JOIN users ON members.uid = users.id WHERE members.uid = :id LIMIT 1", array(':id' => $this->user['id']))[0]));
                }
                    elseif ($this->roleMask == 3) {
                    $this->level = 'Staff';
                    $this->view->title = 'Staff Panel';
                    $this->view->render('Dashboard/' . $this->level . '/' . $url['1'] . '');
                }    elseif($this->roleMask == 2) {
                    $this->level = 'Moderator';
                    $this->view->title = 'Moderator Panel';
                    $this->view->render('Dashboard/' . $this->level . '/' . $url['1'] . '');
                }
            }
    }

   private static function query_tokens($query)
    {
        $regex = '/-?"[\pL\s]+"|-?\pL+/';

        preg_match_all($regex, $query, $tokens, PREG_SET_ORDER);

        foreach ($tokens as & $token) {
            $token = array_shift($token);

            $modifier = NULL;

            if ($token[0] === '-' || $token[0] === '+') {
                $modifier = $token[0];

                $token = substr($token, 1);
            }
            if ($token[0] === '"') {
                $token = trim($token, '"');
            }
            $token = $modifier . $token;
        }

        return $tokens;
    }
}