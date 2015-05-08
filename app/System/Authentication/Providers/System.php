<?php
/**
 * Created by PhpStorm.
 * User: lotd
 * Date: 05-01-15
 * Time: 13:55
 */

namespace System\Authentication\Providers;



use app;
use System\Authentication\Session;
use System\Authentication\Auth;
use System\Authentication\Role;
use Http\Models\User;
use Config\Config;
use MaxMind\Db\Reader;

class System extends Provider {

    protected $model;
    public function getAuthUrl(){}

    public function authenticate($data, User $model)
    {
        $this->model = $model;
        if ($this->check($data)) {
            $account = $model->query("SELECT * FROM members INNER JOIN users ON members.uid = users.id WHERE members.uid = :id LIMIT 1", array(':id' => $this->id));
            $account[0]->type = 'Account';
            foreach ($account as $r) {
                if (password_verify($data['psw'], $r->psw)) {
                    $user = Auth::what('user')->register($r);
                    if (is_object($user)) {
                        if($user->has()->role(array('role' => $r->role)) && $user->has()->permission(array('permission' => 'read')))
                        {
                            $this->isGranted($r);
                        }
                    }
                }
            }
        } else {
            Session::set('feedback_negative', 'Wrong details.');
            app::redirect('users/authenticate');
            return false;
        }
    }

    private function isGranted($user)
    {
        if (Role::getBitMask($user->role) >= 3) {
                Session::set('feedback_positive', 'Welcome, staff member ' . $user->label . '. Your curent position is: ' . $user->position);
        } else {
            Session::set("feedback_positive", 'Welcome, ' . $user->label . '.');
        }
        return app::redirect('dashboard');
    }

    private $id;
    /**
     * Checks whether a user exists with given input
     * @param $data
     * @return mixed
     */
    function check($user)
    {
        $q = $this->model->query("SELECT id FROM users WHERE label = :username OR email = :email", array(':username' => $user['username'], ':email' => $user['email']));
        $this->id = $q[0]->id;
        return !is_null($this->id) ? true : false;
    }


    public function create($data, User $model)
    {
        $user = $model->select('id', 'users')->where(array('label' => $data['username']))->execute();
        if (count($user) > 0) {
            Session::set("feedback_negative", $data['username'].' already taken.');
            return app::redirect('users');
        } else {

            $ip = trim($_SERVER['REMOTE_ADDR']);
            $location = '';
            if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE)) {
                $lan = substr($ip, 0, 6);
                if ($this->match('192.168.',$lan)) {
                    $location = 'Class C';
                }
                if ($this->match('172.16.',$lan)) {
                    $location = 'Class B';
                }

                $tryWiderLan = trim(substr($ip, 0, 4));
                if ($this->match('10.',$tryWiderLan)) {
                    $location = 'Class A';
                }
                if ($this->match('127.',$tryWiderLan)) {
                    $location = 'Local';
                }

            }
            if($location == '') {
                $reader = new Reader(Config::get()->file('User')['GeoIP']['path']);
                $location = $reader->get($ip);
            }

            $user = [
                'label' => $data['username'],
                'email' => $data['email'],
                'password' => $data['pass'],
                'ip' => $ip,
                'location' => $location,
                'joindate' => $data['joindate'],
                'image' => 'smiley.png'
            ];
            if(is_array($model->insert('label, email, psw, ip, location, joindate, image', (array)$user, 'users')))
            {
                $id = $model->select('id', 'users')->where(array(':label' => $data['username']))->execute();
                $member = [
                    'uid' => $id[0]->id,
                    'role' => 'member',
                    'position' => 'user'
                ];
                if(is_array($model->insert('uid, role, position', (array)$member, 'members'))) {
                    Session::set('feedback_positive', 'Konto ' . $data['username'] . ' oprettet!');
                }
            } else {
                Session::set('feedback_negative', 'Der var et problem med oprettelse af konto tilhørende '.$data['username']);
            }
            return app::redirect('users');
        }
    }

    private function match($pattern, $subject)
    {
        $prepend = '/^'.$pattern.'/';
        return preg_match($prepend, $subject) ? true : false;
    }
}