<?php

    include('../functions_main.php');
    include('../libraries/library_gotlint.php');


    #-> ----------------------------------------------------------------------------------------------------------------------------------------------------------

    #-> Взимане на масива с правата. Изтичане на сесия.
    if (isset($_SESSION['operations_array'])) {
        $operations_array = $_SESSION['operations_array'];
    } else {
        $operations_array = array();
    }
    #-> Проверка дали се е логнал потребител
    if (!isset($_SESSION['logged_user_number'])) {
        exit("<br>$error05");
    }
    #-> ----------------------------------------------------------------------------------------------------------------------------------------------------------

    #-> Забавя изпълнението на код с 1 секунда
    sleep(1);

    $requestUserRegistration = 'Заявка потребител за интеграция';

    #-> Сесия на логнат потребител
    $logged_user_number = $_SESSION['logged_user_number'];
    $logged_user_name = $_SESSION['logged_user_name'];
    $logged_user_email = $_SESSION['logged_user_email'];
    $logged_user_type = $_SESSION['logged_user_type'];
    $sessionID = $_SESSION['sessionID'];

    #-> параметър за лог
    $parameter = '';


    #-> Деклариране на променливи
    $message = '';
    $error = '';
    $regResponse = '';
    $contractNumber = '';
    $officeId = '';
    $mobTelefon = '';
    $sendingEmailDetailsArray = '';


    if ($logged_user_email == '') {
        $error = 'Нямате дефиниран e-mail';
    };

    #=> Извличане на данни от формата за регистриране на реален потребител
    if ($error == '' && isset($_POST['realUserRegistration'])) {
        $destinationEmail = 'antonio.hristov@speedy.bg';
        $fullName = $_POST['fullName'];
        $phoneNumber = $_POST['phoneNumber'];
        $senderEmail = $_POST['senderEmail'];
        $contractNumber = $_POST['contractNumber'];
        $clientId = $_POST['clientId'];
        $officeId = $_POST['officeId'];
        $additionalInfo = $_POST['additionalInfo'];

        #-> Конвертиране на формат от UTF-8 на Windows-1251
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);
        $contractNumber = iconv('UTF-8', 'Windows-1251', $contractNumber);
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $officeId = iconv('UTF-8', 'Windows-1251', $officeId);
        $additionalInfo = iconv('UTF-8', 'Windows-1251', $additionalInfo);

        #-> Метод за валидация на празни полета
        if (empty($fullName) || empty($phoneNumber) || empty($senderEmail) || empty($contractNumber) || empty($clientId) || empty($officeId)) {
            $error .= 'Моля да попълните всички полета<br>';
        }

        #-> Метод за валидация на email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= 'Невалиден e-mail, моля да попълните валиден<br>';
        }

        #-> Писмо
        $emailMessage = 'Заявка за създаване на РЕАЛЕН потребител' . '<br><br>';
        $emailMessage .= 'Име на лице, отговарящо за интеграцията: ' . $fullName . '<br>';
        $emailMessage .= 'Телефонен номер: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail за връзка: ' . $senderEmail . '<br>';
        $emailMessage .= 'Номер на договор: ' . $contractNumber . '<br>';
        $emailMessage .= 'Клиентски номер и номер на обект: ' . $clientId . '<br>';
        $emailMessage .= 'Обслужващ офис на този обект: ' . $officeId . '<br>';
        $emailMessage .= 'Допълнителна информация: ' . $additionalInfo . '<br><br>';
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>Телефон: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'Real user';
    }


    #=> Извличане на данни от формата за регистриране на тестов потребител
    if ($error == '' && isset($_POST['testUserRegistration'])) {
        $destinationEmail = 'antonio.hristov@speedy.bg';
        $fullName = $_POST['fullName'];
        $phoneNumber = $_POST['phoneNumber'];
        $senderEmail = $_POST['senderEmail'];
        $clientId = $_POST['clientId'];
        $additionalInfo = $_POST['additionalInfo'];

        #-> Конвертиране на формат от UTF-8 на Windows-1251
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $additionalInfo = iconv('UTF-8', 'Windows-1251', $additionalInfo);

        #-> Метод за валидация на празни полета
        if (empty($fullName) || empty($phoneNumber) || empty($senderEmail) || empty($clientId)) {
            $error .= 'Моля да попълните всички полета<br>';
        }

        #-> Метод за валидация на email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= 'Невалиден e-mail, моля да попълните валиден<br>';
        }


        #-> Параметри за свързване с SQL
        include('../sql/SQLconnect_PORTAL_ARC.php');

        #-> Извикване на функция за звързване с firebird DB
        $dbh = DBConnection($host, $username, $password, $error_DB_connect);

        #-> Стартиране на транзакция
        $transaction = ibase_trans(IBASE_READ + IBASE_COMMITTED + IBASE_REC_VERSION + IBASE_NOWAIT);

        #-> Функция извеждаща данни за служител по ID към дадена дата
        $empDetailsArray = getEmployeeDetailsByIdToDate($transaction, $logged_user_number, date('d.m.Y'));
        $mobTelefon = $empDetailsArray['mobTelefon'];

        #-> Rollback transaction
        ibase_rollback($transaction);

        #-> Close DB connection
        ibase_close();


        #-> Писмо
        $emailMessage = 'Заявка за създаване на ТЕСТОВИ потребител' . '<br><br>';
        $emailMessage .= 'Име на лице, отговарящо за интеграцията: ' . $fullName . '<br>';
        $emailMessage .= 'Телефонен номер: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail за връзка: ' . $senderEmail . '<br>';
        $emailMessage .= 'Име на фирмата: ' . $clientId . '<br>';
        $emailMessage .= 'Допълнителна информация: ' . $additionalInfo . '<br><br>';
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>Телефон: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'Test user';

    }


    #=> Извличане на данни от формата за регистриране на MySpeedy потребител
    if ($error == '' && isset($_POST['mySpeedyUserRegistration'])) {
        $destinationEmail = 'antonio.hristov@speedy.bg';
        $clientId = $_POST['clientId'];
        $contractNumber = $_POST['contractNumber'];
        $objectId = $_POST['objectId'];
        $clientAddress = $_POST['clientAddress'];
        $fullName = $_POST['fullName'];
        $phoneNumber = $_POST['phoneNumber'];
        $senderEmail = $_POST['senderEmail'];
        $radioBtnObjects = $_POST['radioBtnObjects'];
        $radioBtnAmountsPaid = $_POST['radioBtnAmountsPaid'];
        $radioBtnAdminPrivileges = $_POST['radioBtnAdminPrivileges'];

        #-> Конвертиране на формат от UTF-8 на Windows-1251
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $clientAddress = iconv('UTF-8', 'Windows-1251', $clientAddress);
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);

        if (empty($clientId) || empty($contractNumber) || empty($objectId) || empty($clientAddress) || empty($fullName) || empty($phoneNumber) || empty($senderEmail)) {
            $error .= 'Моля да попълните всички полета<br>';
        }

        #-> Метод за валидация на email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= 'Невалиден e-mail, моля да попълните валиден<br>';
        }

        #-> Проверка за радио бутон "Право да генерират пратки от всички обекти"
        if ($radioBtnObjects == 'generateYes') {
            $radioBtnObjectsMsg = 'Да';
        } else {
            $radioBtnObjectsMsg = 'Не';
        }

        #-> Проверка за радио бутон "Право за достъп до справки за изплатени суми"
        if ($radioBtnAmountsPaid == 'permissionsYes') {
            $radioBtnAmountsPaidMsg = 'Да';
        } else {
            $radioBtnAmountsPaidMsg = 'Не';
        }

        if ($radioBtnAdminPrivileges == 'privilegesYes') {
            $radioBtnAdminPrivilegesMsg = 'Да';
        } else {
            $radioBtnAdminPrivilegesMsg = 'Не';
        }

        #-> Писмо
        $emailMessage = 'Заявка за създаване на MySpeedy потребител' . '<br><br>';
        $emailMessage .= 'Име на фирмата: ' . $clientId . '<br>';
        $emailMessage .= 'Номер на договор: ' . $contractNumber . '<br>';
        $emailMessage .= 'Клиентски номер и номер на обект: ' . $objectId . '<br>';
        $emailMessage .= 'Адрес: ' . $clientAddress . '<br>';
        $emailMessage .= 'Име на MySpeedy потребителя: ' . $fullName . '<br>';
        $emailMessage .= 'Телефонен номер: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail за връзка: ' . $senderEmail . '<br>';
        $emailMessage .= 'Да генерира товарителници от всички обекти (собствен и чужди на договора): ' . $radioBtnObjectsMsg . '<br>' ;
        $emailMessage .= 'Достъп до Справки за изплатени/неизплатени суми от НП/ППП: ' . $radioBtnAmountsPaidMsg . '<br>' ;
        $emailMessage .= 'Пълен достъп на Администратор: ' . $radioBtnAdminPrivilegesMsg . '<br><br>' ;
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>Телефон: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'My Speedy user';
    }



    #=> Извличане на данни от формата за регистриране на потребител за готова платформа
    if ($error == '' && isset($_POST['platformUserRegistration'])) {
        $destinationEmail = 'antonio.hristov@speedy.bg';
        $clientId = $_POST['clientId'];
        $contractNumber = $_POST['contractNumber'];
        $bulstat = $_POST['bulstat'];
        $objectId = $_POST['objectId'];
        $clientAddress = $_POST['clientAddress'];
        $fullName = $_POST['fullName'];
        $phoneNumber = $_POST['phoneNumber'];
        $senderEmail = $_POST['senderEmail'];
        $nameOfPlatform = $_POST['nameOfPlatform'];
        $mainIndustry = $_POST['mainIndustry'];
        $subIndustries = $_POST['subIndustries'];
        $onlineShopAddress = $_POST['onlineShopAddress'];

        #-> Конвертиране на формат от UTF-8 на Windows-1251
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $clientAddress = iconv('UTF-8', 'Windows-1251', $clientAddress);
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);
        $nameOfPlatform = iconv('UTF-8', 'Windows-1251', $nameOfPlatform);
        $mainIndustry = iconv('UTF-8', 'Windows-1251', $mainIndustry);
        $subIndustries = iconv('UTF-8', 'Windows-1251', $subIndustries);

        if (empty($clientId) || empty($bulstat) || empty($objectId) || empty($fullName) || empty($phoneNumber) || empty($senderEmail) || empty($nameOfPlatform) || empty($onlineShopAddress)) {
            $error .= 'Моля да попълните всички полета<br>';
        }

        #-> Метод за валидация на email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= 'Невалиден e-mail, моля да попълните валиден<br>';
        }


        #-> Писмо
        $emailMessage = 'Заявка за създаване на потребител за готова платформа' . '<br><br>';
        $emailMessage .= 'Име на фирмата: ' . $clientId . '<br>';
        $emailMessage .= 'Номер на договор: ' . $contractNumber . '<br>';
        $emailMessage .= 'Булстат: ' . $bulstat . '<br>';
        $emailMessage .= 'Клиентски номер и номер на обект: ' . $objectId . '<br>';
        $emailMessage .= 'Адрес: ' . $clientAddress . '<br>';
        $emailMessage .= 'Лице, отговарящо за интеграцията: ' . $fullName . '<br>';
        $emailMessage .= 'Телефонен номер: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail за връзка: ' . $senderEmail . '<br>';
        $emailMessage .= 'Име на платформата: ' . $nameOfPlatform . '<br>';
        $emailMessage .= 'Индустрия: ' . $mainIndustry . '<br>';
        $emailMessage .= 'Подиндустрия: ' . $subIndustries . '<br>';
        $emailMessage .= 'Адрес на онлайн магазин: ' . $onlineShopAddress . '<br><br>';
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>Телефон: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'Platform user';
    }



    #-> Ако няма грешка продължаваме с цикъла
    if ($error == '') {

        #-> Ембедната картинка в писмото
        $embeddedImageDetailsArray = array(
            'embeddedImage' => '../images/logoSpeedy.png',
            'embeddedImageTitle' => 'Speedy',
            'embeddedImageCid' => 'Logo',
        );


        $sendingEmailDetailsArray = array(
            'requirePHPMailerClass' => 'Y',
            'pathToPHPMailer' => PHP_MAILER_SOURCE_PATH,
            'serverHost' => PHP_MAILER_GRID_SERVER_HOST,
            'smtpSecure' => PHP_MAILER_GRID_SMTP_SECURE,
            'serverPort' => PHP_MAILER_GRID_SERVER_PORT,
            'username' => PHP_MAILER_GRID_USERNAME,
            'password' => PHP_MAILER_GRID_PASSWORD,
            'sendOnBehalf' => $logged_user_email,
            'emailCharset' => 'Windows-1251',
            'senderName' => '',
            //'emailsList' => 'antonio.hristov@speedy.bg', //'api.registration@speedy.bg'
            'copyToList' => $logged_user_email,
            'bccList' => '',
            'replyToList' => $logged_user_email,
            //'emailSubject' => $requestUserRegistration . ' ' . $fullName,
            'emailMessage' => $emailMessage,
            'returnedError' => 'Грешка при изпращане на писмо.',
            'returnedSuccessMessage' => 'Успешно изпратено писмо на',
            'embeddedImageDetailsArray' => $embeddedImageDetailsArray,
        );


        #-> Проверка коя форма за регистрация се използва и към коя поща да изпраща писмо.
        if (isset($_POST['realUserRegistration']) || isset($_POST['testUserRegistration'])) {
            $sendingEmailDetailsArray += array('emailsList' => 'api.registration@speedy.bg'); //api.registration@speedy.bg
        } else {
            $sendingEmailDetailsArray += array('emailsList' => 'dba@speedy.bg'); //dba@speedy.bg
        }

        #-> Проверка коя форма за регистрация се използва и кой subject да участва.
        if (isset($_POST['realUserRegistration'])) {
            $sendingEmailDetailsArray += array('emailSubject' => $requestUserRegistration . ' ' . $contractNumber);
        } elseif (isset($_POST['testUserRegistration'])) {
            $sendingEmailDetailsArray += array('emailSubject' => $requestUserRegistration . ' ' . $clientId);
        } else {
            $sendingEmailDetailsArray += array('emailSubject' => $requestUserRegistration);
        }

        #-> Изпращане на мейл
        list($sendingEmailStatus, $sendingEmailMessageRequester, $sendingEmailErrorRequester) = sendMessageToEmailEx($sendingEmailDetailsArray);

        $message = $sendingEmailMessageRequester;
        $error = $sendingEmailErrorRequester;

        if ($sendingEmailStatus == 'OK') {

            if (isset($_POST['realUserRegistration'])) { // 'contractNumber'
                $fullName = iconv('Windows-1251', 'UTF-8', $fullName);
                $senderEmail = iconv('Windows-1251', 'UTF-8', $senderEmail);
                $contractNumber = iconv('Windows-1251', 'UTF-8', $contractNumber);
                $clientId = iconv('Windows-1251', 'UTF-8', $clientId);
                $officeId = iconv('Windows-1251', 'UTF-8', $officeId);
                $query = "
                      INSERT INTO request_registration
                        (fullName, phoneNumber, senderEmail, contractNumber, client, officeId, empID)
                      VALUES
                        ('$fullName', '$phoneNumber', '$senderEmail', '$contractNumber', '$clientId', '$officeId', $logged_user_number)";
            } else {
                $fullName = iconv('Windows-1251', 'UTF-8', $fullName);
                $senderEmail = iconv('Windows-1251', 'UTF-8', $senderEmail);
                $clientId = iconv('Windows-1251', 'UTF-8', $clientId);
                $phoneNumber = iconv('Windows-1251', 'UTF-8', $phoneNumber);
                $query = "
                      INSERT INTO request_registration
                        (fullName, phoneNumber, senderEmail, client, empID)
                      VALUES
                        ('$fullName', '$phoneNumber', '$senderEmail', '$clientId', $logged_user_number)";
            }


            include('../sql/SQLconnect_EPS.php');

            $result = mysqli_query($connection, $query) or die(mysqli_error($connection));

            mysqli_close($connection);
        }
    }

    #-> Показва грешката на екрана
    if ($error != '') {
        $error = "<div class=\"divError\">$error</div>";
        $status = '';
        $regResponse = $error;
    } else {
        $message = "<div class=\"divOk\">$message</div>";
        $status = 'OK';
        $regResponse = $message;
    }

    $regResponse = iconv('Windows-1251', 'UTF-8', $regResponse);


    #-> Запис на log
    insertPageLog($sessionID, 241, $logged_user_number, $parameter);

    #-> Извежда резултатът на екрана
    echo json_encode(
        array(
            'regResponse' => $regResponse,
            'regResponseBottom' => $regResponse,
            'status' => $status
        )
    );
