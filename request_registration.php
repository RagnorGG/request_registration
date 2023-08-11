<?php

    include('../session.php');
    include('../functions_main.php');
    include('../libraries/library_gotlint.php');
    include('../sql/SQLconnect_PORTAL_ARC.php');
    include('../sql/SQLconnect_CONTRACTS_Firebird.php');
    include('../functions/functions_login.php');



    $pageTitle = '������ ���������� �� ����������';

    #-> ����������� �� ����������
    $fullName = '';
    $phoneNumber = '';
    $senderEmail = '';
    $contractNumber = '';
    $clientId = '';
    $officeId = '';
    $message = '';
    $error = '';
    $regResponse = '';
    $objectId = '';
    $clientAddress = '';
    $radioBtnObjects = '';
    $radioBtnAmountsPaid = '';
    $realUserRegistration = '';
    $testUserRegistration = '';
    $mySpeedyUserRegistration = '';
    $platformUserRegistration = '';
    $nameOfPlatform = '';
    $industry = '';
    $subIndustry = '';
    $subIndustriesArray = '';
    $onlineShopAddress = '';
    $platformResults = '';
    $bulstat = '';
    $additionalInfo = '';




    #-> -----------------------------------------------------------------------------------------------------------------------------------------------------------
    #-> ������� �� ������ � �������
    if (isset($_SESSION['operations_array'])) {
        $operations_array = $_SESSION['operations_array'];
    } else {
        $operations_array = array();
    }
    #-> �������� ���� �� � ������ ����������
    if (!isset($_SESSION['logged_user_number'])) {
        exit("<br>$error");
    }
#-> -----------------------------------------------------------------------------------------------------------------------------------------------------------

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="Windows-1251">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../css/request_registration.css?v=2a" rel="stylesheet" type="text/css">
    <link href="../ITIR/jquery-ui.css" rel="stylesheet" type="text/css">
    <script src='../jquery/3.1.1/jquery-3.1.1.min.js' type='text/javascript'></script>
    <!-- jQuery UI -->
    <script src='../jquery/3.1.1/jquery-ui.min.js' type='text/javascript'></script>
    <!-- Search office autocomplete -->
    <script>
        $(document).ready(function () {

            $("#officeName").autocomplete({
                delay: 100,
                minLength: 1,
                autoFocus: true, // highlight the first record
                source: function (request, response) {
                    $.ajax({
                        //url: '../AJAX/searchEmpDetails.php',
                        url: '../AJAX/searchOffices.php',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            search: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    var emp = ui.item.label; // Display the selected text
                    var officeID = ui.item.officeID; // Save selected id to input
                    var office = ui.item.value;

                    $('#officeName').val(office);

                    return false;
                }
            });
        })
    </script>
    <!-- Search contractnumber ID -->
    <script>
        $(document).ready(function () {

            $("#contractNumber").autocomplete({
                delay: 100,
                minLength: 1,
                autoFocus: true, // highlight the first record
                source: function (request, response) {
                    $.ajax({
                        url: '../AJAX/searchContracts.php',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            search: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                },
                select: function (event, ui) {
                    var emp = ui.item.label; // Display the selected text
                    var contractID = ui.item.contractID; // Save selected id to input
                    var contract = ui.item.value;

                    $('#contractNumber').val(contract);

                    return false;
                }
            });

            $("#realUserSubmit").click(function (e) {
                //prevent Default functionality
                e.preventDefault();
                $("#regResponse").html('����, ���������.<br>' + '<img src=../images/preloader.gif>');
                document.getElementById("realUserSubmit").disabled = true;

                $.ajax({
                    url: 'request_registration_actions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $("#realUserRegistration").serialize(),
                    success: function (data) {
                        // ... do something with the data...
                        console.log('Submited');
                        console.log(data);
                        var regResponse = data.regResponse;
                        var status = data.status;
                        $("#regResponse").html(regResponse);

                        // if everything is OK reset form and clear inputs
                        if (status == 'OK') {
                            document.getElementById('realUserRegistration').reset();
                        }
                        ;
                        document.getElementById("realUserSubmit").disabled = false;
                    }

                });

            });

            $("#testUserSubmit").click(function (e) {
                //prevent Default functionality
                e.preventDefault();
                $("#regResponse").html('����, ���������.<br>' + '<img src=../images/preloader.gif>');
                document.getElementById("testUserSubmit").disabled = true;

                $.ajax({
                    url: 'request_registration_actions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $("#testUserRegistration").serialize(),
                    success: function (data) {
                        // ... do something with the data...
                        console.log('Submited');
                        console.log(data);
                        var regResponse = data.regResponse;
                        var status = data.status;
                        $("#regResponse").html(regResponse);

                        // if everything is OK reset form and clear inputs
                        if (status == 'OK') {
                            document.getElementById('testUserRegistration').reset();
                        }
                        ;
                        document.getElementById("testUserSubmit").disabled = false;

                    }

                });


            });
            $("#mySpeedyUserSubmit").click(function (e) {
                //prevent Default functionality
                e.preventDefault();
                $("#regResponse").html('����, ���������.<br>' + '<img src=../images/preloader.gif>');
                document.getElementById("testUserSubmit").disabled = true;

                $.ajax({
                    url: 'request_registration_actions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $("#mySpeedyUserRegistration").serialize(),
                    success: function (data) {
                        // ... do something with the data...
                        console.log('Submited');
                        console.log(data);
                        var regResponse = data.regResponse;
                        var status = data.status;
                        $("#regResponse").html(regResponse);

                        // if everything is OK reset form and clear inputs
                        if (status == 'OK') {
                            document.getElementById('mySpeedyUserRegistration').reset();
                        }
                        ;
                        document.getElementById("mySpeedyUserSubmit").disabled = false;

                    }

                });
            });



            $("#platformUserSubmit").click(function (e) {
                //prevent Default functionality
                e.preventDefault();
                $("#regResponse").html('����, ���������.<br>' + '<img src=../images/preloader.gif>');
                document.getElementById("testUserSubmit").disabled = true;

                $.ajax({
                    url: 'request_registration_actions.php',
                    type: 'POST',
                    dataType: 'json',
                    data: $("#platformUserRegistration").serialize(),
                    success: function (data) {
                        // ... do something with the data...
                        console.log('Submited');
                        console.log(data);
                        var regResponse = data.regResponse;
                        //var regResponseBottom = data.regResponseBottom;
                        var status = data.status;
                        $("#regResponse").html(regResponse);
                        //$("#regResponseBottom").html(regResponseBottom);

                        // Check if there is an error
                        if (status !== 'OK') {
                            // Scroll to the top of the page if there is an error
                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                        }

                        // Check if there is an error
                        if (status == 'OK') {
                            // Scroll to the top of the page to show response
                            $('html, body').animate({ scrollTop: 0 }, 'slow');
                        }

                        // if everything is OK reset form and clear inputs
                        if (status == 'OK') {
                            document.getElementById('platformUserRegistration').reset();
                        }
                        ;
                        document.getElementById("platformUserSubmit").disabled = false;

                    }

                });
            });

        })

    </script>
    <!-- Switch test user / real user registration -->
    <script type="text/javascript">
        $(document).ready(function () {
            // hide the forms when page is ready
            $('#realUserRegistration').hide();
            $('#testUserRegistration').hide();
            $('#mySpeedyUserRegistration').hide();
            $('#platformUserRegistration').hide();

            $('#realRegistration').click(function () {
                $('#realUserRegistration').show();
                $('#testUserRegistration').hide();
                $('#mySpeedyUserRegistration').hide();
                $('#platformUserRegistration').hide();
            });
            $('#testRegistration').click(function () {
                $('#testUserRegistration').show();
                $('#realUserRegistration').hide();
                $('#mySpeedyUserRegistration').hide();
                $('#platformUserRegistration').hide();
            });
            $('#registrationMySpeedy').click(function () {
                $('#mySpeedyUserRegistration').show();
                $('#testUserRegistration').hide();
                $('#realUserRegistration').hide();
                $('#platformUserRegistration').hide();
            });
            $('#registrationForPlatform').click(function () {
                $('#platformUserRegistration').show();
                $('#realUserRegistration').hide();
                $('#testUserRegistration').hide();
                $('#mySpeedyUserRegistration').hide();
            });
        });
    </script>
    <!-- Ajax connection that associates industry with subindustry -->
    <script>
        $(document).ready(function(){
            $("#mainIndustry").change(function(){

                // Ajax ������ ��� PHP ����
                $.ajax({
                    url: "getSubIndustries.php",
                    type: "POST",
                    data: $("#platformUserRegistration").serialize(),
                    success: function (data) {
                        // Do something with the data and split the array with the | separator
                        var json = JSON.parse(data);
                        var subIndustryString = json.subIndustry;
                        var subIndustryArray = subIndustryString.split("|");
                        var selectElement = document.getElementById("subIndustry");
                        // Clear any existing options
                        selectElement.innerHTML = "";
                        // Create and append new options based on the subIndustryArray
                        subIndustryArray.forEach(function (subIndustry) {
                            var option = document.createElement("option");
                            option.text = subIndustry;
                            option.value = subIndustry;
                            selectElement.appendChild(option);
                        });
                    }
                })
            })
        })
     </script>

    <title><?php echo $pageTitle; ?></title>
<head>

<body>
<div class="divPageTitle"><?php echo $pageTitle; ?></div>
<div id="regResponse" class="regResponse">���� �� ��������� ������ ������</div>
<div class="chooseRegForm">
    <div>
        <input class="radioBtn" type="radio" name="action" id="realRegistration" value=""/>
        <label for="realRegistration">����������� �� ������ ����������</label>
    </div>
    <div>
        <input class="radioBtn" type="radio" name="action" id="testRegistration" value=""/>
        <label for="testRegistration">����������� �� ������� ����������</label>
    </div>
    <div>
        <input class="radioBtn" type="radio" name="action" id="registrationMySpeedy" value=""/>
        <label class="textColorRed" for="registrationMySpeedy">����������� �� MySpeedy ����������</label>
    </div>
    <div>
        <input class="radioBtn" type="radio" name="action" id="registrationForPlatform" value=""/>
        <label class="textColorRed" for="registrationForPlatform">����������� �� ���������� �� ������ ���������</label>
    </div>
</div>
<div class="regContainer">
    <form class="regForm" action="" method="post" id="realUserRegistration">
        <input type="hidden" name="realUserRegistration" value="realUserRegistration">
        <div class="regSections">
            <p class="regDescription">��� �� ����, ���������� �� ������������</p>
            <input class="regInput" type="text" name="fullName" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">��������� �����</p>
            <input class="regInput" type="text" name="phoneNumber" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">E-mail �� ������</p>
            <input class="regInput" type="text" name="senderEmail" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">����� �� �������</p>
            <input class="regInput" name="contractNumber" id="contractNumber" autocomplete="off" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">��������� ����� � ����� �� �����</p>
            <input class="regInput" type="number" name="clientId" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">��������� ���� �� ���� �����</p>
            <input data-live-search="true" class="regInput" type="text" id="officeName" name="officeId"
                   autocomplete="off" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">������������ ���������� (�������������� ����)</p>
            <input data-live-search="true" class="regInput" type="text" id="additionalInfo" name="additionalInfo"
                   autocomplete="off"/>
        </div>
        <tr class="regSections">
            <input class="inputButton" type="button" id="realUserSubmit" name="realUserSubmit" value="�����"/>
        </tr>
    </form>

    <form class="regForm" action="" method="post" id="testUserRegistration">
        <input type="hidden" name="testUserRegistration" value="testUserRegistration">
        <div div class="regSections">
            <p class="regDescription">��� �� ����, ���������� �� ������������</p>
            <input class="regInput" type="text" name="fullName" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">��������� �����</p>
            <input class="regInput" type="text" name="phoneNumber" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">����� �� �������</p>
            <input class="regInput" type="text" name="clientId" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">E-mail �� ������</p>
            <input class="regInput" type="text" name="senderEmail" required/>
        </div>
        <div class="regSections">
            <p class="regDescription">������������ ���������� (�������������� ����)</p>
            <input data-live-search="true" class="regInput" type="text" id="additionalInfo" name="additionalInfo"
                   autocomplete="off"/>
        </div>
        <tr class="regSections">
            <input class="inputButton" type="button" id="testUserSubmit" name="testUserSubmit" value="�����"/>
        </tr>
    </form>

    <form class="regForm" action="" method="post" id="mySpeedyUserRegistration">
        <input type="hidden" name="mySpeedyUserRegistration" value="mySpeedyUserRegistration">
        <div class="regSections">
            <p class="regDescriptionRed">��� �� �������</p>
            <input class="regInput" type="text" name="clientId" required></input>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">����� �� �������</p>
            <input class="regInput" type="text" name="contractNumber" required>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��������� ����� � ����� �� �����</p>
            <input class="regInput" type="text" name="objectId" required>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">�����</p>
            <input class="regInput" type="text" name="clientAddress" required>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��� �� MySpeedy �����������</p>
            <input class="regInput" type="text" name="fullName" required>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��������� �����</p>
            <input class="regInput" type="text" name="phoneNumber" required>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">E-mail �� ������</p>
            <input class="regInput" type="text" name="senderEmail" required>
        </div>
        <div>
            <h4 class="regDescriptionRed">����� �� �����������:</h4>
            <div class="regSections">
                <p class="regDescriptionRed">�� �������� ������������� �� ������ ������ (�������� � ����� �� ��������)</p>
                <label for="generatePackagesFromObjectsYes">��</label>
                <input id="generatePackagesFromObjectsYes" type="radio" name="radioBtnObjects" value="generateYes">
                <label for="generatePackagesFromObjectsNo">��</label>
                <input id="generatePackagesFromObjectsNo" type="radio" name="radioBtnObjects" value="generateNo" checked="checked">
            </div>
            <div class="regSections">
                <p class="regDescriptionRed">������ �� ������� �� ���������/����������� ���� �� ��/���</p>
                <label for="permissionsForAmountsPaidYes">��</label>
                <input id="permissionsForAmountsPaidYes" type="radio" name="radioBtnAmountsPaid" value="permissionsYes">
                <label for="permissionsForAmountsPaidNo">��</label>
                <input id="permissionsForAmountsPaidNo" type="radio" name="radioBtnAmountsPaid" value="permissionsNo" checked="checked">
            </div>
            <div class="regSections">
                <p class="regDescriptionRed">����� ������ �� �������������</p>
                <label for="adminPrivilegesYes">��</label>
                <input id="adminPrivilegesYes" type="radio" name="radioBtnAdminPrivileges" value="privilegesYes">
                <label for="adminPrivilegesNo">��</label>
                <input id="adminPrivilegesNo" type="radio" name="radioBtnAdminPrivileges" value="privilegesNo" checked="checked">
            </div>
        </div>
        <tr class="regSections">
            <input class="inputButtonRed" type="button" id="mySpeedyUserSubmit" name="mySpeedyUserSubmit"
                   value="�����"/>
        </tr>
    </form>

    <form class="regForm" action="" method="post" id="platformUserRegistration">
        <input type="hidden" name="platformUserRegistration" value="platformUserRegistration">
        <div class="regSections">
            <p class="regDescriptionRed">��� �� �������*</p>
            <input class="regInput" type="text" name="clientId">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">����� �� �������</p>
            <input class="regInput" type="text" name="contractNumber">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">�������*</p>
            <input class="regInput" type="text" name="bulstat">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��������� ����� � ����� �� �����*</p>
            <input class="regInput" type="text" name="objectId">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">�����</p>
            <input class="regInput" type="text" name="clientAddress">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��� �� ����, ���������� �� ������������*</p>
            <input class="regInput" type="text" name="fullName">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��������� �����*</p>
            <input class="regInput" type="text" name="phoneNumber">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">E-mail �� ������*</p>
            <input class="regInput" type="text" name="senderEmail">
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">��� �� �����������*</p>
            <select class="regSelector" name="nameOfPlatform" id="nameOfPlatform">
                <?php
                    include('../sql/SQLconnect_PORTAL_ARC.php');

                    #-> ����� �� ������ ��� ��
                    $dbh = DBConnection("portal.db.speedy.bg:portal", "SYSDBA", "SpdDB@01", "������");

                    #-> �������� �� ����������
                    $transaction = ibase_trans(IBASE_READ + IBASE_COMMITTED + IBASE_REC_VERSION + IBASE_NOWAIT);

                    $query = "
                            SELECT
                              cs.Str_Value AS ClientSystemTypes
                            FROM
                              Company_Settings cs
                            WHERE
                              cs.Const_Name = 'CLIENT_SYSTEM_TYPE'
                              AND cs.CompanySystemID = 1";

                    #-> ��������� �� �������� ��� ������ � ������, ��� �� ����
                    $result = ibase_query($transaction, $query) or die( 'ERROR in queryShifts: ' . ibase_errmsg());

                    $platformListArray = array();

                    #-> �������� �� ����� �� SQL �������� � �����
                    while($row = ibase_fetch_assoc($result)) {
                        $clientSystemTypes = $row['CLIENTSYSTEMTYPES'];
                    }

                    #-> ��������� �� ������ �� ���� ��� � CR LF ����������
                    $platformListArray = explode("\r\n", $clientSystemTypes);

                    #-> ��������� �� ������������ �� ������
                    echo "<option></option>";
                    foreach($platformListArray as $platform) {
                        echo "<option>" . $platform . "</option>";
                    }

                    ibase_rollback($transaction);

                    ibase_close($dbh);
                ?>
            </select>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">���������</p>
            <select class="regSelector" name="mainIndustry" id="mainIndustry">
                <?php

                    #-> ����� �� ������ ��� ��
                    $dbh = DBConnection("portal.db.speedy.bg:portal", "SYSDBA", "SpdDB@01", "������");

                    #-> �������� �� ����������
                    $transaction = ibase_trans(IBASE_READ + IBASE_COMMITTED + IBASE_REC_VERSION + IBASE_NOWAIT);

                    $query = "
                            SELECT
                              cs.Str_Value AS ClientIndustries
                            FROM
                              Company_Settings cs
                            WHERE
                              cs.Const_Name = 'CLIENT_INDUSTRIES'
                              AND cs.CompanySystemID = 1";

                    #-> ��������� �� �������� ��� ������ � ������, ��� �� ����
                    $result = ibase_query($transaction, $query) or die('ERROR in queryShifts: ' . ibase_errmsg());

                    #-> �������� �� ����� �� SQL �������� � �����
                    while($row = ibase_fetch_assoc($result)) {
                        $clientIndustries = $row['CLIENTINDUSTRIES'];
                    }

                    #-> ��������� �� ������ � ����������/���������� �� ������ ������ ������
                    $clientIndustriesArray = explode(";", $clientIndustries);
                    $clientIndustriesArray = array_filter($clientIndustriesArray);
                    $mainIndustriesArray = array();
                    $subIndustriesArray = array();

                    #-> ��������� �� ����� � ������� ��� � ������
                    foreach($clientIndustriesArray as $index => $industry) {
                        if($index % 2 ==0) {
                            $mainIndustriesArray[] = $industry;
                        } else {
                            $subIndustriesArray[] = $industry;
                        }
                    }


                    #-> ��������� �� ������������ �� ������
                    echo "<option></option>";
                    foreach($mainIndustriesArray as $mainIndustry) {
                        echo "<option value=\"$mainIndustry\">" . $mainIndustry . "</option>";
                    }

                    ibase_rollback($transaction);
                    ibase_close($dbh);
                ?>
            </select>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">������������</p>
            <select class="regSelector" name="subIndustries" id="subIndustry"></select>
        </div>
        <div class="regSections">
            <p class="regDescriptionRed">����� �� ������ �������*</p>
            <input class="regInput" type="text" name="onlineShopAddress">
        </div>
        <tr class="regSections">
            <input class="inputButtonRed" type="button" id="platformUserSubmit" name="platformUserSubmit"
                   value="�����"/>
        </tr>
        <input type="hidden" name="clientIndustries" id="clientIndustries" value="<?php echo $clientIndustries ?>">
    </form>
    <div id="regResponseBottom" class="regResponse"></div>
</div>
</body>

</html>