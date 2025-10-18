<?php
// Add these routes to your config/routes.php file

use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::defaultRouteClass(DashedRoute::class);

Router::scope('/', function (RouteBuilder $routes) {
    // Default home page
    $routes->connect('/', ['controller' => 'Reports', 'action' => 'dashboard']);
    
    // Employee routes
    $routes->connect('/employees', ['controller' => 'Employees', 'action' => 'index']);
    $routes->connect('/employees/add', ['controller' => 'Employees', 'action' => 'add']);
    $routes->connect('/employees/edit/:id', ['controller' => 'Employees', 'action' => 'edit'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/employees/view/:id', ['controller' => 'Employees', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    $routes->connect('/employees/delete/:id', ['controller' => 'Employees', 'action' => 'delete'], ['id' => '\d+', 'pass' => ['id']]);
    
    // Attendance routes
    $routes->connect('/attendance/daily', ['controller' => 'Attendances', 'action' => 'daily']);
    $routes->connect('/attendances/save', ['controller' => 'Attendances', 'action' => 'saveAttendance']);
    $routes->connect('/attendance/monthly-report', ['controller' => 'Attendances', 'action' => 'monthlyReport']);
    $routes->connect('/attendance/update-status', ['controller' => 'Attendances', 'action' => 'updateStatus']);
    
    // Payslip routess
    $routes->connect('/payslips', ['controller' => 'Payslips', 'action' => 'index']);
    $routes->connect('/payslips/generate', ['controller' => 'Payslips', 'action' => 'generate']);
    $routes->connect('/payslips/view/:id', ['controller' => 'Payslips', 'action' => 'view'], ['id' => '\d+', 'pass' => ['id']]);
    
    // Report routes
    $routes->connect('/reports/dashboard', ['controller' => 'Reports', 'action' => 'dashboard']);
    $routes->connect('/reports/department-monthly', ['controller' => 'Reports', 'action' => 'departmentMonthlySalary']);
    $routes->connect('/reports/employee-monthly', ['controller' => 'Reports', 'action' => 'employeeMonthlySalary']);
    $routes->connect('/reports/employee-yearly', ['controller' => 'Reports', 'action' => 'employeeYearlySalary']);

    // Fallback route
    $routes->fallbacks(DashedRoute::class);
});