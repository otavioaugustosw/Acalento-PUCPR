<?php
include('php/auth_services/AuthService.php');
include('php/config/bootstrap_php.php');
echo AuthService::generatePasswordHash('1234');
