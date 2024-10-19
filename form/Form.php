<?php
namespace JurateVilima\MvcFramework\form;

class Form {
    public $model;

    public function __construct($model) {
        $this->model = $model;
    }

    public static function begin($action, $method) {
        echo "<form action='$action' method='$method'>";
    }

    public static function end() {
        echo "</form>";
    }

    public function createField($attribute, $fieldType = "input", $inputType = "text") {
        $inputInfo['attribute'] = $attribute;
        $inputInfo['fieldType'] = $fieldType;
        $inputInfo['inputType'] = $inputType;
        // Retrieve the old value for the attribute
        
        if(isset($this->model->errors[$attribute])) {
            $inputInfo['class'] = "is-invalid";
            $inputInfo['error'] = $this->model->getFirstError($attribute);
            // $class = "is-invalid";
            // $error = $this->model->getFirstError($attribute);
        }

        // $oldValue = $this->model->$attribute;
        $inputInfo['oldValue'] = $this->model->$attribute;
        
        // Get the label for the attribute from the model
        $inputInfo['label'] = $this->model->ruleLabels()[$attribute];

        // Output the form field HTML
        echo $this->createFieldHtml($inputInfo);
    }

    private function createFieldTwoParam() {

    }

    private function createTextbox($attribute, $class, $oldValue, $inputType) {
        return "<input name='$attribute' type='$inputType' value='$oldValue' class='form-control $class' id='$attribute' aria-describedby='{$attribute}Help'>";
    }

    private function createTextarea($attribute, $class, $oldValue) {
        return "<textarea name='$attribute' class='form-control $class' aria-label='With textarea'>
                    $oldValue
                </textarea>";
    }

    private function createFieldHtml($inputInfo) {
        extract($inputInfo);

        $fieldHtml = "<div class='mb-3'>
                    <label for='$attribute' class='form-label'>$label</label>";
  
        $class = isset($class) ? $class : '';
        $error = isset($error) ? $error : '';

        switch($fieldType) {
            case 'input': 
                        $fieldHtml .= $this->createTextbox($attribute, $class, $oldValue, $inputType);
                        break;

            case 'textarea':
                        $fieldHtml .= $this->createTextarea($attribute, $class, $oldValue);
                        break;
        };

        $fieldHtml .= "     <div class='invalid-feedback'>
                                $error
                            </div>
                        </div>";
        
        return $fieldHtml;
    }
    
}