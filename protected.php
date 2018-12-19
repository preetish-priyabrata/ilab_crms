<?php
session_start();
if(!isset($_SESSION['empId']) || empty($_SESSION['empId']) || !isset($_SESSION['empType']) || empty($_SESSION['empType']) || !isset($_SESSION['empName']) || empty($_SESSION['empName']) || !isset($_SESSION['contViewSetting']) || empty($_SESSION['contViewSetting'])){
    header("location: login/");
}
?>