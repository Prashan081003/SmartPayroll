<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Payslip Entity
 *
 * @property int $id
 * @property int $employee_id
 * @property int $month
 * @property int $year
 * @property float $base_pay
 * @property int $days_worked
 * @property float $total_bonus
 * @property float $total_deductions
 * @property float $net_salary
 * @property \Cake\I18n\FrozenDate $payment_date
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 *
 * @property \App\Model\Entity\Employee $employee
 * @property \App\Model\Entity\Bonus[] $bonuses
 * @property \App\Model\Entity\Deduction[] $deductions
 */
class Payslip extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
    ];
}
