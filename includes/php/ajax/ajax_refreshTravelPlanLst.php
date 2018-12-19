<?php
include_once '../travelPlan_pagination.php';

if(isset($_GET['permission']) && !empty($_GET['permission'])){
    viewTravelPlanPagination();    
}
?>
