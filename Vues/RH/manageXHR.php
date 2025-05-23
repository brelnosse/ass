<?php
    session_start();
    include('../../Controller/registerController.php');
    
    if(isset($_GET["filtres"]) AND isset($_GET["isAnd"]) AND isset($_GET['offset'])){
        $_SESSION['page_num'] = round(intval($_GET['offset'])/10)+1;
        $arr = (array) json_decode($_GET["filtres"]);
        getemploye($arr['search'], $arr['bdate'], $arr['added_date'], $arr['ordre'], $arr['email_status'] , $arr['status'], $_GET['offset'], $_GET["isAnd"]);
        // echo $_GET['offset'];
    }