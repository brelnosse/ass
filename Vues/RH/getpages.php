<?php
session_start();
include('../../Controller/registerController.php');

if(isset($_GET['page'])){
    $lines = ceil(getemployenum()/10);
    if($lines != 0){
        if(isset($_SESSION['page_num'])){
            if($_SESSION['page_num'] > 1){
                echo '<button class="page-btn">&lt;</button>'; 
            }
            for($i = 0; $i < $lines; $i++){
                echo '<button class="page-btn '.($_SESSION['page_num'] == ($i+1) ? "active": "").'">'.($i+1).'</button>';
            }
        }
    }
}