<?php

    include('../functions_main.php');
    include('../libraries/library_gotlint.php');


    #-> ----------------------------------------------------------------------------------------------------------------------------------------------------------

    #-> ������� �� ������ � �������. �������� �� �����.
    if (isset($_SESSION['operations_array'])) {
        $operations_array = $_SESSION['operations_array'];
    } else {
        $operations_array = array();
    }
    #-> �������� ���� �� � ������ ����������
    if (!isset($_SESSION['logged_user_number'])) {
        exit("<br>$error05");
    }
    #-> ----------------------------------------------------------------------------------------------------------------------------------------------------------

    #-> ������ ������������ �� ��� � 1 �������
    sleep(1);

    $requestUserRegistration = '������ ���������� �� ����������';

    #-> ����� �� ������ ����������
    $logged_user_number = $_SESSION['logged_user_number'];
    $logged_user_name = $_SESSION['logged_user_name'];
    $logged_user_email = $_SESSION['logged_user_email'];
    $logged_user_type = $_SESSION['logged_user_type'];
    $sessionID = $_SESSION['sessionID'];

    #-> ��������� �� ���
    $parameter = '';


    #-> ����������� �� ����������
    $message = '';
    $error = '';
    $regResponse = '';
    $contractNumber = '';
    $officeId = '';
    $mobTelefon = '';
    $sendingEmailDetailsArray = '';


    if ($logged_user_email == '') {
        $error = '������ ��������� e-mail';
    };

    #=> ��������� �� ����� �� ������� �� ������������ �� ������ ����������
    if ($error == '' && isset($_POST['realUserRegistration'])) {
        $destinationEmail = 'antonio.hristov@speedy.bg';
        $fullName = $_POST['fullName'];
        $phoneNumber = $_POST['phoneNumber'];
        $senderEmail = $_POST['senderEmail'];
        $contractNumber = $_POST['contractNumber'];
        $clientId = $_POST['clientId'];
        $officeId = $_POST['officeId'];
        $additionalInfo = $_POST['additionalInfo'];

        #-> ������������ �� ������ �� UTF-8 �� Windows-1251
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);
        $contractNumber = iconv('UTF-8', 'Windows-1251', $contractNumber);
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $officeId = iconv('UTF-8', 'Windows-1251', $officeId);
        $additionalInfo = iconv('UTF-8', 'Windows-1251', $additionalInfo);

        #-> ����� �� ��������� �� ������ ������
        if (empty($fullName) || empty($phoneNumber) || empty($senderEmail) || empty($contractNumber) || empty($clientId) || empty($officeId)) {
            $error .= '���� �� ��������� ������ ������<br>';
        }

        #-> ����� �� ��������� �� email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= '��������� e-mail, ���� �� ��������� �������<br>';
        }

        #-> �����
        $emailMessage = '������ �� ��������� �� ������ ����������' . '<br><br>';
        $emailMessage .= '��� �� ����, ���������� �� ������������: ' . $fullName . '<br>';
        $emailMessage .= '��������� �����: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail �� ������: ' . $senderEmail . '<br>';
        $emailMessage .= '����� �� �������: ' . $contractNumber . '<br>';
        $emailMessage .= '��������� ����� � ����� �� �����: ' . $clientId . '<br>';
        $emailMessage .= '��������� ���� �� ���� �����: ' . $officeId . '<br>';
        $emailMessage .= '������������ ����������: ' . $additionalInfo . '<br><br>';
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>�������: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'Real user';
    }


    #=> ��������� �� ����� �� ������� �� ������������ �� ������ ����������
    if ($error == '' && isset($_POST['testUserRegistration'])) {
        $destinationEmail = 'antonio.hristov@speedy.bg';
        $fullName = $_POST['fullName'];
        $phoneNumber = $_POST['phoneNumber'];
        $senderEmail = $_POST['senderEmail'];
        $clientId = $_POST['clientId'];
        $additionalInfo = $_POST['additionalInfo'];

        #-> ������������ �� ������ �� UTF-8 �� Windows-1251
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $additionalInfo = iconv('UTF-8', 'Windows-1251', $additionalInfo);

        #-> ����� �� ��������� �� ������ ������
        if (empty($fullName) || empty($phoneNumber) || empty($senderEmail) || empty($clientId)) {
            $error .= '���� �� ��������� ������ ������<br>';
        }

        #-> ����� �� ��������� �� email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= '��������� e-mail, ���� �� ��������� �������<br>';
        }


        #-> ��������� �� ��������� � SQL
        include('../sql/SQLconnect_PORTAL_ARC.php');

        #-> ��������� �� ������� �� ��������� � firebird DB
        $dbh = DBConnection($host, $username, $password, $error_DB_connect);

        #-> ���������� �� ����������
        $transaction = ibase_trans(IBASE_READ + IBASE_COMMITTED + IBASE_REC_VERSION + IBASE_NOWAIT);

        #-> ������� ��������� ����� �� �������� �� ID ��� ������ ����
        $empDetailsArray = getEmployeeDetailsByIdToDate($transaction, $logged_user_number, date('d.m.Y'));
        $mobTelefon = $empDetailsArray['mobTelefon'];

        #-> Rollback transaction
        ibase_rollback($transaction);

        #-> Close DB connection
        ibase_close();


        #-> �����
        $emailMessage = '������ �� ��������� �� ������� ����������' . '<br><br>';
        $emailMessage .= '��� �� ����, ���������� �� ������������: ' . $fullName . '<br>';
        $emailMessage .= '��������� �����: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail �� ������: ' . $senderEmail . '<br>';
        $emailMessage .= '��� �� �������: ' . $clientId . '<br>';
        $emailMessage .= '������������ ����������: ' . $additionalInfo . '<br><br>';
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>�������: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'Test user';

    }


    #=> ��������� �� ����� �� ������� �� ������������ �� MySpeedy ����������
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

        #-> ������������ �� ������ �� UTF-8 �� Windows-1251
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $clientAddress = iconv('UTF-8', 'Windows-1251', $clientAddress);
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);

        if (empty($clientId) || empty($contractNumber) || empty($objectId) || empty($clientAddress) || empty($fullName) || empty($phoneNumber) || empty($senderEmail)) {
            $error .= '���� �� ��������� ������ ������<br>';
        }

        #-> ����� �� ��������� �� email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= '��������� e-mail, ���� �� ��������� �������<br>';
        }

        #-> �������� �� ����� ����� "����� �� ��������� ������ �� ������ ������"
        if ($radioBtnObjects == 'generateYes') {
            $radioBtnObjectsMsg = '��';
        } else {
            $radioBtnObjectsMsg = '��';
        }

        #-> �������� �� ����� ����� "����� �� ������ �� ������� �� ��������� ����"
        if ($radioBtnAmountsPaid == 'permissionsYes') {
            $radioBtnAmountsPaidMsg = '��';
        } else {
            $radioBtnAmountsPaidMsg = '��';
        }

        if ($radioBtnAdminPrivileges == 'privilegesYes') {
            $radioBtnAdminPrivilegesMsg = '��';
        } else {
            $radioBtnAdminPrivilegesMsg = '��';
        }

        #-> �����
        $emailMessage = '������ �� ��������� �� MySpeedy ����������' . '<br><br>';
        $emailMessage .= '��� �� �������: ' . $clientId . '<br>';
        $emailMessage .= '����� �� �������: ' . $contractNumber . '<br>';
        $emailMessage .= '��������� ����� � ����� �� �����: ' . $objectId . '<br>';
        $emailMessage .= '�����: ' . $clientAddress . '<br>';
        $emailMessage .= '��� �� MySpeedy �����������: ' . $fullName . '<br>';
        $emailMessage .= '��������� �����: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail �� ������: ' . $senderEmail . '<br>';
        $emailMessage .= '�� �������� ������������� �� ������ ������ (�������� � ����� �� ��������): ' . $radioBtnObjectsMsg . '<br>' ;
        $emailMessage .= '������ �� ������� �� ���������/����������� ���� �� ��/���: ' . $radioBtnAmountsPaidMsg . '<br>' ;
        $emailMessage .= '����� ������ �� �������������: ' . $radioBtnAdminPrivilegesMsg . '<br><br>' ;
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>�������: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'My Speedy user';
    }



    #=> ��������� �� ����� �� ������� �� ������������ �� ���������� �� ������ ���������
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

        #-> ������������ �� ������ �� UTF-8 �� Windows-1251
        $clientId = iconv('UTF-8', 'Windows-1251', $clientId);
        $clientAddress = iconv('UTF-8', 'Windows-1251', $clientAddress);
        $fullName = iconv('UTF-8', 'Windows-1251', $fullName);
        $nameOfPlatform = iconv('UTF-8', 'Windows-1251', $nameOfPlatform);
        $mainIndustry = iconv('UTF-8', 'Windows-1251', $mainIndustry);
        $subIndustries = iconv('UTF-8', 'Windows-1251', $subIndustries);

        if (empty($clientId) || empty($bulstat) || empty($objectId) || empty($fullName) || empty($phoneNumber) || empty($senderEmail) || empty($nameOfPlatform) || empty($onlineShopAddress)) {
            $error .= '���� �� ��������� ������ ������<br>';
        }

        #-> ����� �� ��������� �� email
        if (filter_var($senderEmail, FILTER_VALIDATE_EMAIL) != true) {
            $error .= '��������� e-mail, ���� �� ��������� �������<br>';
        }


        #-> �����
        $emailMessage = '������ �� ��������� �� ���������� �� ������ ���������' . '<br><br>';
        $emailMessage .= '��� �� �������: ' . $clientId . '<br>';
        $emailMessage .= '����� �� �������: ' . $contractNumber . '<br>';
        $emailMessage .= '�������: ' . $bulstat . '<br>';
        $emailMessage .= '��������� ����� � ����� �� �����: ' . $objectId . '<br>';
        $emailMessage .= '�����: ' . $clientAddress . '<br>';
        $emailMessage .= '����, ���������� �� ������������: ' . $fullName . '<br>';
        $emailMessage .= '��������� �����: ' . $phoneNumber . '<br>';
        $emailMessage .= 'E-mail �� ������: ' . $senderEmail . '<br>';
        $emailMessage .= '��� �� �����������: ' . $nameOfPlatform . '<br>';
        $emailMessage .= '���������: ' . $mainIndustry . '<br>';
        $emailMessage .= '������������: ' . $subIndustries . '<br>';
        $emailMessage .= '����� �� ������ �������: ' . $onlineShopAddress . '<br><br>';
        $emailMessage .= '<em><strong>' . $logged_user_name . '</strong></em><br>';
        $emailMessage .= '<em>' . $logged_user_type . '</em><br>';
        $emailMessage .= '<em>�������: ' . $mobTelefon . '</em><br>';
        $emailMessage .= '<em>e-mail: ' . $logged_user_email . '</em><br><br>';
        $emailMessage .= "<img src=\"cid:Logo\" alt=\"Speedy\">";

        $parameter = 'Platform user';
    }



    #-> ��� ���� ������ ������������ � ������
    if ($error == '') {

        #-> ��������� �������� � �������
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
            'returnedError' => '������ ��� ��������� �� �����.',
            'returnedSuccessMessage' => '������� ��������� ����� ��',
            'embeddedImageDetailsArray' => $embeddedImageDetailsArray,
        );


        #-> �������� ��� ����� �� ����������� �� �������� � ��� ��� ���� �� ������� �����.
        if (isset($_POST['realUserRegistration']) || isset($_POST['testUserRegistration'])) {
            $sendingEmailDetailsArray += array('emailsList' => 'api.registration@speedy.bg'); //api.registration@speedy.bg
        } else {
            $sendingEmailDetailsArray += array('emailsList' => 'dba@speedy.bg'); //dba@speedy.bg
        }

        #-> �������� ��� ����� �� ����������� �� �������� � ��� subject �� �������.
        if (isset($_POST['realUserRegistration'])) {
            $sendingEmailDetailsArray += array('emailSubject' => $requestUserRegistration . ' ' . $contractNumber);
        } elseif (isset($_POST['testUserRegistration'])) {
            $sendingEmailDetailsArray += array('emailSubject' => $requestUserRegistration . ' ' . $clientId);
        } else {
            $sendingEmailDetailsArray += array('emailSubject' => $requestUserRegistration);
        }

        #-> ��������� �� ����
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

    #-> ������� �������� �� ������
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


    #-> ����� �� log
    insertPageLog($sessionID, 241, $logged_user_number, $parameter);

    #-> ������� ���������� �� ������
    echo json_encode(
        array(
            'regResponse' => $regResponse,
            'regResponseBottom' => $regResponse,
            'status' => $status
        )
    );
