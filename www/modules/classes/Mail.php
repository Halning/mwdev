<?php
/**
 * Created by PhpStorm.
 * Date: 15.02.16
 * Time: 17:54
 */

namespace Modules;

use SendGrid;

/**
 * Class Mail.
 * Відправляє email використовуючи SendGrid.
 * @package Modules
 */
class Mail
{
    private static $sendGrid;

    /**
     * @param string $to
     * @param string $subject
     * @param string $html
     * @param string $from
     * @return bool
     * @throws SendGrid\Exception
     */
    public static function send($to, $subject, $html, $from = 'info@makewear.com.ua', $name = 'Makewear')
    {
        if (null === self::$sendGrid) {

            //якщо екземпляр класу SendGrid не створений то створюєм
            self::$sendGrid = new SendGrid(SEND_GRID_KEY);
        }

        $email = new SendGrid\Email();
        $email
            ->addTo($to)
            ->setFrom($from)
            ->setFromName($name)
            ->setSubject($subject)
            ->setHtml($html);

        $result = self::$sendGrid->send($email);

        if ($result->getCode() === 200) {
            return true;
        }

        return false;
    }

    public static  function sendMultiple($to, $subject, $html, $fromEmail  = 'info@makewear.com.ua', $fromName = 'Makewear')
    {
        if (null === self::$sendGrid) {

            //якщо екземпляр класу SendGrid не створений то створюєм
            self::$sendGrid = new SendGrid(SEND_GRID_KEY);
        }

        $names = array();
        $emails = array();
        $email = new SendGrid\Email();

        foreach ($to as $item) {
            $emails[] = $item['email'];
            $names[] = $item['name'];
        }

        $email
            ->setSmtpapiTos($emails)
            ->setFrom($fromEmail)
            ->setFromName($fromName)
            ->setSubject($subject)
            ->setHtml($html);

        $result = self::$sendGrid->send($email);

        if ($result->getCode() === 200) {
            return true;
        }

        return false;
    }
}
