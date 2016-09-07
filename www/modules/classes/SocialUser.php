<?php
/**
 * Created by PhpStorm.
 * Date: 11/20/15
 * Time: 12:28 PM
 */

namespace Modules;


class SocialUser extends User
{
    /**
     * SocialUser constructor. Присвоєння всіх полів К, витягнутих з соціальної мережі.
     * @param $user array
     * @param $mysqli object
     */
    public function __construct($user)
    {
        global $theme_name;

        $theme_name = 'shop';
        $this->mysqli = MySQLi::getInstance()->getConnect();
        $this->template = new \templ();
        $this->template->set_tpl('{$hostName}', $_SERVER['HTTP_HOST']);


        $this->name = $user['name'];
        $this->realName = $user['realname'];
        $this->email = $user['email'];
        $this->socPage = $user['soc_page'];
        $this->socId = $user['soc_id'];
        $this->socProvider = $user['soc_provider'];
        $this->image = $user['image'];
        $this->sex = $user['sex'];
        $this->birthDay = $user['birthday'];
    }


    /**
     * Провіряє чи в БД є К з даним соц. ід.
     * @return int|bool
     */
    public function checkExistUser()
    {
        $query = <<<QUERY
            SELECT * FROM users
            WHERE soc_vk_id = '{$this->socId}'
            OR soc_fb_id = '{$this->socId}'
            OR soc_ok_id = '{$this->socId}'
            OR soc_go_id = '{$this->socId}'
            LIMIT 1
QUERY;
        $result = $this->mysqli->query($query);
        if ($row = $result->fetch_assoc()) {
            $this->id = $row['user_id'];
            $this->activated = $row['activated'];
            $this->email = $row['user_email'];
            $this->name = $row['user_name'];
            return $this->id;
        } else {
            return false;
        }
    }

    public function isActivated()
    {
        return $this->activated;
    }

    /**
     * Створення нового К в БД.
     * @param bool $activate
     * @return $this
     */
    public function createUser($activate = false)
    {
        $password = $this->generatedPassword ? md5($this->generatedPassword) : '';

        $query = "
INSERT INTO users (
    user_name,
    user_realname,
    user_password,
    user_email,
    user_soc_page,
    soc_{$this->socProvider}_id,
    user_soc_provider,
    user_image,
    user_sex,
    user_birthday,
    user_registred_date,
    activated
) VALUES (
    '{$this->name}',
    '{$this->realName}',
    '{$password}',
    '{$this->email}',
    '{$this->socPage}',
    '{$this->socId}',
    '{$this->socProvider}',
    '{$this->image}',
    '{$this->sex}',
    '{$this->birthDay}',
     NOW(),
     '{$activate}'
)";
        $this->mysqli->query($query);
        $this->id = $this->mysqli->insert_id;
        return $this;
    }

    /**
     * Запис в сесію ід даного К, для форми введення ел. адреси.
     * @return $this
     */
    public function setSessionsConfirmId ()
    {
        $_SESSION['confirm_id'] = $this->id;

        return $this;
    }

    /**
     * Повертає ел. адресу К.
     * @return string|null
     */
    public function emailExists()
    {
        return $this->email;
    }
}



























