<?php
namespace App\View\Cell;

use Cake\View\Cell;
use Cake\ORM\TableRegistry;

class ImageCell extends Cell
{
    /**
     * Display image upload/view interface
     * 
     * @param string $model Model name (e.g., 'Employees')
     * @param int $recordId Record ID
     * @param string $field Field name in database (e.g., 'photo')
     * @param array|null $uploadUrl Upload action URL
     * @param string $mode Mode: 'view', 'edit', or 'add'
     */
    public function display($model, $recordId, $field, $uploadUrl = null, $mode = 'edit')
    {
        $this->loadModel($model);
        
        $imagePath = null;
        $hasImage = false;
        
        // Get existing image if record exists
        if (!empty($recordId)) {
            $record = $this->$model->get($recordId);
            if (!empty($record->$field)) {
                $imagePath = $record->$field;
                $hasImage = true;
            }
        }
        
        $this->set([
            'model' => $model,
            'recordId' => $recordId,
            'field' => $field,
            'imagePath' => $imagePath,
            'hasImage' => $hasImage,
            'uploadUrl' => $uploadUrl,
            'mode' => $mode
        ]);
    }
}