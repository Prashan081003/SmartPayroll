<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        Employee Payroll System -
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>

    <?= $this->Html->css('base.css') ?>
    <?= $this->Html->css('style.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
</head>
<body>
    <nav class="top-nav">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="<?= $this->Url->build('/') ?>">Employee Payroll System</a>
            </div>
            
            <!-- Hamburger Menu Button -->
            <button class="hamburger" id="hamburger-btn" aria-label="Toggle menu">
                <span></span>
                <span></span>
                <span></span>
            </button>
            
            <ul class="nav-menu" id="nav-menu">
                <li>
                    <?= $this->Html->link('Dashboard', ['controller' => 'Reports', 'action' => 'dashboard']) ?>
                </li>
                
                <li class="nav-dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        Employees <span class="arrow">▼</span>
                    </a>
                    <ul class="dropdown-content">
                        <li><?= $this->Html->link('All Employees', ['controller' => 'Employees', 'action' => 'index']) ?></li>
                        <li><?= $this->Html->link('Add Employee', ['controller' => 'Employees', 'action' => 'add']) ?></li>
                    </ul>
                </li>
                
                <li class="nav-dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        Attendance <span class="arrow">▼</span>
                    </a>
                    <ul class="dropdown-content">
                        <li><?= $this->Html->link('Daily Attendance', ['controller' => 'Attendances', 'action' => 'daily']) ?></li>
                        <li><?= $this->Html->link('Monthly Report', ['controller' => 'Attendances', 'action' => 'monthlyReport']) ?></li>
                        <li><?= $this->Html->link('All Records', ['controller' => 'Attendances', 'action' => 'index']) ?></li>
                    </ul>
                </li>
                
                <li class="nav-dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        Payroll <span class="arrow">▼</span>
                    </a>
                    <ul class="dropdown-content">
                        <li><?= $this->Html->link('Generate Payslip', ['controller' => 'Payslips', 'action' => 'generate']) ?></li>
                        <li><?= $this->Html->link('All Payslips', ['controller' => 'Payslips', 'action' => 'index']) ?></li>
                    </ul>
                </li>
                
                <li class="nav-dropdown">
                    <a href="javascript:void(0)" class="dropdown-toggle">
                        Reports <span class="arrow">▼</span>
                    </a>
                    <ul class="dropdown-content">
                        <li><?= $this->Html->link('Department Monthly Salary', ['controller' => 'Reports', 'action' => 'departmentMonthlySalary']) ?></li>
                        <li><?= $this->Html->link('Employee Monthly Salary', ['controller' => 'Reports', 'action' => 'employeeMonthlySalary']) ?></li>
                        <li><?= $this->Html->link('Employee Yearly Salary', ['controller' => 'Reports', 'action' => 'employeeYearlySalary']) ?></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <main class="main-content">
        <div class="container">
            <?= $this->Flash->render() ?>
            <?= $this->fetch('content') ?>
        </div>
    </main>

    <footer class="site-footer">
        <div class="container">
            <p>&copy; <?= date('Y') ?> Employee Payroll System. All rights reserved.</p>
        </div>
    </footer>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const hamburger = document.getElementById('hamburger-btn');
        const navMenu = document.getElementById('nav-menu');
        const dropdowns = document.querySelectorAll('.nav-dropdown');
        
        // Hamburger menu toggle
        hamburger.addEventListener('click', function() {
            hamburger.classList.toggle('active');
            navMenu.classList.toggle('active');
            document.body.classList.toggle('menu-open');
        });
        
        // Dropdown toggle
        dropdowns.forEach(function(dropdown) {
            const toggle = dropdown.querySelector('.dropdown-toggle');
            
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                // On mobile, toggle current dropdown
                const isMobile = window.innerWidth <= 768;
                
                if (isMobile) {
                    // Close other dropdowns on mobile
                    dropdowns.forEach(function(other) {
                        if (other !== dropdown) {
                            other.classList.remove('active');
                        }
                    });
                }
                
                // Toggle current dropdown
                dropdown.classList.toggle('active');
            });
        });
        
        // Close menu when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.nav-container')) {
                dropdowns.forEach(function(dropdown) {
                    dropdown.classList.remove('active');
                });
                
                // Close mobile menu
                if (window.innerWidth <= 768) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                    document.body.classList.remove('menu-open');
                }
            }
        });
        
        // Close mobile menu on link click
        const navLinks = document.querySelectorAll('.nav-menu a');
        navLinks.forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 768) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                    document.body.classList.remove('menu-open');
                }
            });
        });
        
        // Handle window resize
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth > 768) {
                    hamburger.classList.remove('active');
                    navMenu.classList.remove('active');
                    document.body.classList.remove('menu-open');
                    dropdowns.forEach(function(dropdown) {
                        dropdown.classList.remove('active');
                    });
                }
            }, 250);
        });
    });
    </script>

    <?= $this->fetch('script') ?>
</body>
</html>

<style>
/* Reset and Base Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
    line-height: 1.6;
    color: #333;
    background-color: #f5f5f5;
}

body.menu-open {
    overflow: hidden;
}

/* Navigation Bar */
.top-nav {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    position: sticky;
    top: 0;
    z-index: 1000;
}

.nav-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: relative;
}

.nav-brand a {
    color: white;
    text-decoration: none;
    font-size: 20px;
    font-weight: bold;
    padding: 15px 0;
    display: block;
    white-space: nowrap;
}

/* Hamburger Menu */
.hamburger {
    display: none;
    flex-direction: column;
    background: none;
    border: none;
    cursor: pointer;
    padding: 10px;
    z-index: 1001;
}

.hamburger span {
    width: 25px;
    height: 3px;
    background-color: white;
    margin: 3px 0;
    transition: all 0.3s ease;
    border-radius: 3px;
}

.hamburger.active span:nth-child(1) {
    transform: rotate(45deg) translate(8px, 8px);
}

.hamburger.active span:nth-child(2) {
    opacity: 0;
}

.hamburger.active span:nth-child(3) {
    transform: rotate(-45deg) translate(7px, -7px);
}

/* Navigation Menu */
.nav-menu {
    list-style: none;
    display: flex;
    margin: 0;
    padding: 0;
}

.nav-menu > li {
    position: relative;
}

.nav-menu > li > a {
    color: white;
    text-decoration: none;
    padding: 15px 20px;
    display: block;
    transition: background-color 0.3s;
    white-space: nowrap;
}

.nav-menu > li > a:hover {
    background-color: rgba(255, 255, 255, 0.1);
}

/* Dropdown Styles */
.nav-dropdown {
    position: relative;
}

.dropdown-toggle {
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 5px;
}

.arrow {
    font-size: 10px;
    transition: transform 0.3s;
}

.nav-dropdown.active .arrow {
    transform: rotate(180deg);
}

.dropdown-content {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    background-color: white;
    min-width: 220px;
    box-shadow: 0 8px 16px rgba(0,0,0,0.2);
    border-radius: 0 0 4px 4px;
    overflow: hidden;
    z-index: 1000;
}

/* Fix for last menu item (Reports dropdown) */
.nav-menu > li:last-child .dropdown-content,
.nav-menu > li:nth-last-child(1) .dropdown-content,
.nav-menu > li:nth-last-child(2) .dropdown-content {
    left: auto;
    right: 0;
}

.nav-dropdown:hover .dropdown-content,
.nav-dropdown.active .dropdown-content {
    display: block;
    animation: slideDown 0.3s ease;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.dropdown-content li {
    list-style: none;
}

.dropdown-content li a {
    color: #333;
    padding: 12px 20px;
    text-decoration: none;
    display: block;
    transition: background-color 0.2s;
    white-space: nowrap;
}

.dropdown-content li a:hover {
    background-color: #f0f0f0;
    color: #667eea;
}

/* Main Content */
.main-content {
    min-height: calc(100vh - 140px);
    padding: 30px 0 60px 0; /* Added bottom padding */
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
    position: relative;
}

/* Footer */
.site-footer {
    background-color: #2c3e50;
    color: white;
    padding: 20px 0;
    text-align: center;
    margin-top: auto; /* Push to bottom */
    position: relative;
    width: 100%;
    clear: both;
}

/* Flash Messages */
.message {
    padding: 15px 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    border-left: 4px solid;
}

.message.success {
    background-color: #d4edda;
    border-color: #28a745;
    color: #155724;
}

.message.error {
    background-color: #f8d7da;
    border-color: #dc3545;
    color: #721c24;
}

.message.warning {
    background-color: #fff3cd;
    border-color: #ffc107;
    color: #856404;
}

.message.info {
    background-color: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

/* Button Styles */
.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 14px;
    margin-right: 10px;
}

.button:hover {
    background-color: #5568d3;
}

.button.float-right {
    float: right;
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

table th,
table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

table th {
    background-color: #667eea;
    color: white;
    font-weight: 600;
}

table tr:hover {
    background-color: #f9f9f9;
}

table tr:last-child td {
    border-bottom: none;
}

/* Form Styles */
input[type="text"],
input[type="email"],
input[type="number"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
    color: #555;
}

fieldset {
    border: 1px solid #e0e0e0;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    background: white;
}

legend {
    font-size: 18px;
    font-weight: bold;
    padding: 0 10px;
    color: #667eea;
}

/* Pagination */
.paginator {
    margin-top: 30px;
    text-align: center;
}

.pagination {
    display: inline-flex;
    list-style: none;
    padding: 0;
    margin: 0 0 10px 0;
    flex-wrap: wrap;
}

.pagination li {
    margin: 2px;
}

.pagination a {
    padding: 8px 12px;
    text-decoration: none;
    color: #667eea;
    border: 1px solid #ddd;
    border-radius: 3px;
    transition: all 0.3s;
}

.pagination a:hover {
    background-color: #667eea;
    color: white;
}

.pagination .active a {
    background-color: #667eea;
    color: white;
    border-color: #667eea;
}

/* Utility Classes */
.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.mt-20 {
    margin-top: 20px;
}

.mb-20 {
    margin-bottom: 20px;
}

.clearfix::after {
    content: "";
    clear: both;
    display: table;
}

/* ============================================
   RESPONSIVE STYLES - MOBILE & TABLET
   ============================================ */

@media (max-width: 768px) {
    /* Show hamburger menu */
    .hamburger {
        display: flex;
    }
    
    /* Hide navigation menu by default on mobile */
    .nav-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 280px;
        height: 100vh;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
        flex-direction: column;
        padding-top: 60px;
        transition: right 0.3s ease;
        overflow-y: auto;
        box-shadow: -2px 0 10px rgba(0,0,0,0.3);
    }
    
    /* Show menu when active */
    .nav-menu.active {
        right: 0;
    }
    
    /* Mobile menu items */
    .nav-menu > li {
        width: 100%;
        border-bottom: 1px solid rgba(255,255,255,0.1);
    }
    
    .nav-menu > li > a {
        padding: 15px 20px;
        width: 100%;
    }
    
    /* Mobile dropdown */
    .dropdown-content {
        position: static;
        box-shadow: none;
        background-color: rgba(0, 0, 0, 0.2);
        min-width: auto;
        width: 100%;
        border-radius: 0;
    }
    
    .dropdown-content li a {
        color: white;
        padding: 12px 20px 12px 40px;
    }
    
    .dropdown-content li a:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    /* Brand adjustments */
    .nav-brand a {
        font-size: 16px;
        padding: 12px 0;
    }
    
    /* Container adjustments */
    .container {
        padding: 0 15px;
    }
    
    /* Table responsive */
    .table-responsive {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    table {
        font-size: 13px;
    }
    
    table th,
    table td {
        padding: 8px;
    }
    
    /* Form adjustments */
    input[type="text"],
    input[type="email"],
    input[type="number"],
    input[type="date"],
    select,
    textarea {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    /* Button adjustments */
    .button {
        padding: 8px 15px;
        font-size: 13px;
        margin: 5px 5px 5px 0;
    }
    
    .button.float-right {
        float: none;
        display: block;
        width: 100%;
        text-align: center;
    }
    
    /* Pagination responsive */
    .pagination {
        justify-content: center;
    }
    
    .pagination li {
        margin: 2px;
    }
    
    .pagination a {
        padding: 6px 10px;
        font-size: 13px;
    }
}

/* Tablet landscape */
@media (max-width: 1024px) and (min-width: 769px) {
    .nav-container {
        padding: 0 15px;
    }
    
    .nav-menu > li > a {
        padding: 15px 15px;
        font-size: 14px;
    }
    
    .dropdown-content {
        min-width: 200px;
    }
}

/* Small mobile devices */
@media (max-width: 480px) {
    .nav-brand a {
        font-size: 14px;
    }
    
    .nav-menu {
        width: 100%;
        right: -100%;
    }
    
    .main-content {
        padding: 20px 0;
    }
    
    h3, h4 {
        font-size: 18px;
    }
    
    .site-footer {
        font-size: 13px;
        padding: 15px 0;
    }
}

.message.info {
    background-color: #d1ecf1;
    border-color: #17a2b8;
    color: #0c5460;
}

/* Button Styles */
.button {
    display: inline-block;
    padding: 10px 20px;
    background-color: #667eea;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s;
    font-size: 14px;
    margin-right: 10px;
}

.button:hover {
    background-color: #5568d3;
}

.button.float-right {
    float: right;
}

/* Table Styles */
.table-responsive {
    overflow-x: auto;
    margin-top: 20px;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

table th,
table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #e0e0e0;
}

table th {
    background-color: #667eea;
    color: white;
    font-weight: 600;
}

table tr:hover {
    background-color: #f9f9f9;
}

table tr:last-child td {
    border-bottom: none;
}

/* Form Styles */
input[type="text"],
input[type="email"],
input[type="number"],
input[type="date"],
select,
textarea {
    width: 100%;
    padding: 10px;
    margin: 8px 0;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 2px rgba(102, 126, 234, 0.1);
}

label {
    font-weight: 600;
    display: block;
    margin-bottom: 5px;
    color: #555;
}

fieldset {
    border: 1px solid #e0e0e0;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 5px;
    background: white;
}

legend {
    font-size: 18px;
    font-weight: bold;
    padding: 0 10px;
    color: #667eea;
}

/* Pagination */
.paginator {
    margin-top: 30px;
    text-align: center;
}

.pagination {
    display: inline-flex;
    list-style: none;
    padding: 0;
    margin: 0 0 10px 0;
}

.pagination li {
    margin: 0 2px;
}

.pagination a {
    padding: 8px 12px;
    text-decoration: none;
    color: #667eea;
    border: 1px solid #ddd;
    border-radius: 3px;
    transition: all 0.3s;
}

.pagination a:hover {
    background-color: #667eea;
    color: white;
}

.pagination .active a {
    background-color: #667eea;
    color: white;
    border-color: #667eea;
}

/* Responsive Design */
@media (max-width: 768px) {
    .nav-container {
        flex-direction: column;
        align-items: flex-start;
    }
    
    .nav-menu {
        flex-direction: column;
        width: 100%;
    }
    
    .nav-menu > li {
        width: 100%;
    }
    
    .dropdown-content {
        position: static;
        box-shadow: none;
        background-color: rgba(255, 255, 255, 0.1);
    }
    
    .dropdown-content li a {
        color: white;
        padding-left: 40px;
    }
    
    .dropdown-content li a:hover {
        background-color: rgba(255, 255, 255, 0.1);
    }
}

/* Utility Classes */
.text-center {
    text-align: center;
}

.text-right {
    text-align: right;
}

.mt-20 {
    margin-top: 20px;
}

.mb-20 {
    margin-bottom: 20px;
}

.clearfix::after {
    content: "";
    clear: both;
    display: table;
}
</style>