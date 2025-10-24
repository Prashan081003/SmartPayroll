<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

class Department extends Entity
{
    protected $_accessible = [
        'name' => true,
        'code' => true,
        'description' => true,
        'employees' => true,
        'created' => true,
        'modified' => true
    ];
}