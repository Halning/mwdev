<?php

namespace Modules;

global $templates, $center;

if ('admin' === $_SESSION['status']) {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);
        switch ($action) {

            case 'send':
                $subscribeLetters = new SubscribeLetters();
                $messageType = $subscribeLetters::MESSAGE_ERROR_ID;
                $resultMessage = 'Не удалось отправить тестовое сообщение.';
                $resultMessageMultiple = '';

                $isNotTest = filter_input(INPUT_POST, 'sub-sending', FILTER_VALIDATE_INT);
                $theme = filter_input(INPUT_POST, 'theme', FILTER_SANITIZE_STRING);
                $from = filter_input(INPUT_POST, 'from', FILTER_SANITIZE_STRING);
                $fromEmail = filter_input(INPUT_POST, 'from-email', FILTER_VALIDATE_EMAIL);
                $letterTemplate = filter_input(INPUT_POST, 'template', FILTER_VALIDATE_INT);
                $testEmail1 = filter_input(INPUT_POST, 'test-mail-1', FILTER_VALIDATE_EMAIL);
                $testEmail2 = filter_input(INPUT_POST, 'test-mail-2', FILTER_VALIDATE_EMAIL);
                $testEmail3 = filter_input(INPUT_POST, 'test-mail-3', FILTER_VALIDATE_EMAIL);
                $subscribersBase = filter_input(INPUT_POST, 'letter-base', FILTER_SANITIZE_STRING);

                if ($theme && $from && $letterTemplate && $fromEmail) {
                    $subscribeLetters->getLetter($letterTemplate);

                    if ($subscribeLetters->letter) {

                        if ($isNotTest) {

                            //get email message from file template
                            /*
                            $path = __DIR__ . '/../templates/mail.template' . $subscribeLetters->letter->id . '.tpl';
                            $emailMessage = @file_get_contents($path);
                            */

                            //get email letter from blob storage
                            $emailMessage = stripslashes($_POST['letter-content']);

                           /* $blobStorabe = new BlobStorage();
                            $emailMessage = $blobStorabe->getBlob(
                                $subscribeLetters::STORAGE_CONTAINER,
                                $subscribeLetters->id.'.tpl'
                            );*/

                        } else {
                            $emailMessage = stripslashes($_POST['letter-content']);
                        }

                        if ($emailMessage) {

                            $result = false;
                            $emailsFinal = array();

                            if ($isNotTest && $subscribersBase) {

                                //get emails from subscribers base
                                $subscribers = new Subscribe();
                                $subscribers->filterGroup = $subscribersBase;
                                $subscribers->getSubscribers(false);
                                foreach ($subscribers->subscribers as $subscriber) {
                                    if ($subscriber->sub_email) {

                                        $emailsFinal[] = array(
                                            'email' => $subscriber->sub_email,
                                            'name' => $subscriber->user_name,
                                        );
                                    }
                                }

                            } elseif ($testEmail1) {


                                //get test emails
                                $testEmails = array($testEmail1, $testEmail2, $testEmail3);

                                foreach ($testEmails as $testEmail) {
                                    if ($testEmail) {
                                        $emailsFinal[] = array(
                                            'email' => $testEmail,
                                            'name' => 'Makewear test'
                                        );
                                    }
                                }

                            } else {
                                $resultMessage .= ' Неверно заполнены поля (test email or letter base)';
                            }

                            //sending
                            if (count($emailsFinal) > 0 && count($emailsFinal) < 999) {
                                $result = Mail::sendMultiple(
                                    $emailsFinal,
                                    $theme,
                                    $emailMessage,
                                    $fromEmail,
                                    $from
                                );
                            } elseif (count($emailsFinal) >= 999) {

                                // split array on less arrays with 998 length
                                $outputEmailFinals = array_chunk($emailsFinal, 998);
                                foreach ($outputEmailFinals as $key => $outputEmailFinal) {
                                    $result = Mail::sendMultiple(
                                        $outputEmailFinal,
                                        $theme,
                                        $emailMessage,
                                        $fromEmail,
                                        $from
                                    );
                                    $resultMessageMultiple .= "<br>Массив №$key => $result";
                                }
                            }

                            //init success message
                            if ($result) {
                                $resultMessage = 'Сообщение успешно отправлено'.$resultMessageMultiple;
                                $messageType = $subscribeLetters::MESSAGE_SUCCESS_ID;
                            } else {
                                $resultMessage .= ' Ошибка почтового сервера'.$resultMessageMultiple;
                            }

                        } else {
                            $resultMessage .= ' Не удальсь открыть файл шаблона письма';
                        }
                    } else {
                        $resultMessage .= ' Ошибка базы данных';
                    }
                } else {
                    $resultMessage .= ' Неверно заполнены поля';
                }

                $center .= $subscribeLetters->getResultMessage($resultMessage, $messageType);
                break;

            case 'letter-edit':

                //edit letter template (save)
                $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $letterContent = stripslashes($_POST['letter-content']);
                $letter = new SubscribeLetters();
                $letter->id = $id;

                if ($id && $name && $letterContent) {
                    $letter
                        ->updateLetter($id, $name)
                        ->setTemplateFile($letterContent);
                }

                if ($letter->ajaxResult) {
                    $center = $letter->getResultMessage(
                        'Изминения успешно сохранены.',
                        $letter::MESSAGE_SUCCESS_ID,
                        'letters'
                    );
                } else {
                    $center = $letter->getResultMessage(
                        'Произошла ошыбка записи файла.',
                        $letter::MESSAGE_ERROR_ID,
                        'letters'
                    );
                }
                break;

            case 'letter-add':
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $template = filter_input(INPUT_POST, 'template', FILTER_SANITIZE_STRING);
                $letterContent = stripslashes($_POST['letter-content']);
                $letter = new SubscribeLetters();
                $messageType = $letter::MESSAGE_ERROR_ID;
                $resultMessage = 'Не удалось создать файл шаблона. ';

                //choose tempplate for letter if exists
                switch ($template) {

                    case 'base':
                        $templates->set_tpl('{$content}', $letterContent);
                        $letterContent = $templates->get_tpl('mail.main.template');
                        break;

                    case 'vitalij':
                        $templates->set_tpl('{$content}', $letterContent);
                        $letterContent = $templates->get_tpl('mail.vitalij.template');
                        break;
                }

                if ($name && $letterContent) {
                    $letter
                        ->addLetter($name)
                        ->setTemplateFile($letterContent);

                    if ($letter->ajaxResult && $letter->id) {
                        $resultMessage = 'Файл шаблона успешно создан.';
                        $messageType = $letter::MESSAGE_SUCCESS_ID;
                    } else {
                        $resultMessage .= 'Ошыбка записи БД или файла.';
                    }
                } else {
                    $resultMessage .= 'Не заполнены поля.';
                }

                $center = $letter->getResultMessage(
                    $resultMessage,
                    $messageType,
                    'letters'
                );
                break;
        }

    } elseif ('GET' === $_SERVER['REQUEST_METHOD']) {

        $action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);

        switch ($action) {

            case 'letters/':
            case 'letters':

                //letters page
                $allLines = '';
                $letters = new SubscribeLetters();

                if (filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT)) {
                    $letters->currentPage = filter_input(INPUT_GET, 'p', FILTER_VALIDATE_INT);
                }

                $letters->getLetters();

                foreach ($letters->letters as $letter) {
                    $templates->set_tpl('{$id}', $letter->id);
                    $templates->set_tpl('{$name}', $letter->name);
                    $allLines .= $templates->get_tpl('subscribe.letters.line');
                }

                $templates->set_tpl('{$allLines}', $allLines);
                $templates->set_tpl('{$pages}', $letters->generatePages());
                $center = $templates->get_tpl('subscribe.letters');
                break;


            case 'edit-letter':

                //edit letter template
                $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

                if ($id) {

                    $letter = new SubscribeLetters();
                    $blobStorabe = new BlobStorage();
                    $letter->getLetter($id);
                    $templates->set_tpl('{$id}', $letter->letter->id);
                    $templates->set_tpl('{$name}', $letter->letter->name);
                    $templates->set_tpl(
                        '{$letterContent}',
                        $blobStorabe->getBlob(
                            $letter::STORAGE_CONTAINER,
                            $id.'.tpl'
                        )
                    );
//                    $templates->set_tpl('{$letterContent}', $letter->getLetterContent($id));
                    $center = $templates->get_tpl('subscribe.letter.edit');
                }
                break;

            case 'add-letter':
                $center = $templates->get_tpl('subscribe.letter.add');
                break;


            default:

                //show send letters page
                $subscribeLetters = new SubscribeLetters();
                $subscribeLetters->getLetters(false);
                $subscribers = new Subscribe();
                $subscribers->getSubscribers(false);

                $basesOptions = '';
                foreach (Subscribe::$filterGroupList as $listType => $listName) {
                    $basesOptions .= "<option value='$listType'>$listName ({$subscribers->amountSubscribersByType[$listType]})</option>";
                }

                $templates->set_tpl('{$lettersOptions}', $subscribeLetters->getLettersOptions());
                $templates->set_tpl('{$basesOptions}', $basesOptions);
                $center .= $templates->get_tpl('subscribe.send');
                break;
        }
    }
}
