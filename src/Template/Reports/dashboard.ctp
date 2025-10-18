<div class="dashboard content">
    <h2>Dashboard</h2>
    
    <div class="stats-container">
        <div class="stat-box">
            <h3>Total Employees</h3>
            <p class="stat-number"><?= $totalEmployees ?></p>
        </div>
        
        <div class="stat-box">
            <h3>Present Today</h3>
            <p class="stat-number"><?= $todayPresent ?></p>
        </div>
        
        <div class="stat-box">
            <h3>Payslips This Month</h3>
            <p class="stat-number"><?= $monthPayslips ?></p>
        </div>
        
        <div class="stat-box">
            <h3>Total Salary (This Month)</h3>
            <p class="stat-number">₹<?= $this->Number->format($totalSalary->total ?? 0, ['places' => 2]) ?></p>
        </div>
    </div>
    
    <div class="quick-links">
        <h3>Quick Actions</h3>
        <?= $this->Html->link('Mark Attendance', ['controller' => 'Attendances', 'action' => 'daily'], ['class' => 'button']) ?>
        <?= $this->Html->link('Generate Payslip', ['controller' => 'Payslips', 'action' => 'generate'], ['class' => 'button']) ?>
        <?= $this->Html->link('Add Employee', ['controller' => 'Employees', 'action' => 'add'], ['class' => 'button']) ?>
    </div>
</div>

<style>
.stats-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin: 30px 0;
}
.stat-box {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 30px;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
.stat-box h3 {
    margin: 0 0 10px 0;
    font-size: 16px;
    opacity: 0.9;
}
.stat-number {
    font-size: 36px;
    font-weight: bold;
    margin: 0;
}
.quick-links {
    margin-top: 40px;
    padding: 20px;
    background: #f5f5f5;
    border-radius: 10px;
}
.quick-links h3 {
    margin-bottom: 15px;
}
</style>