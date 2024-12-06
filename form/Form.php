<?php
namespace JurateVilima\MvcCore\form;

class Form {
    public $model;
    private $className;

    public function __construct($model) {
        $this->model = $model;
        $this->className = "{$this->model->id}-form";
    }

    public function begin($action, $method) {
        echo "<form action='$action' method='$method'>";
    }

    public static function end() {
        echo "</form>";
    }

    public function getFormClass() {
        return $this->className;
    }

    public function getFormName() {
        return "<h2 class='form__title'>{$this->model->formName}</h2>";
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
        return "<input name='$attribute' type='$inputType' value='$oldValue' class='form__input' id='$attribute'>";
    }

    private function createTextarea($attribute, $class, $oldValue) {
        return "<textarea name='$attribute' class='form__textarea'>
                    $oldValue
                </textarea>";
    }

    private function createFieldHtml($inputInfo) {
        extract($inputInfo);

        $fieldHtml = "<div class='form__item'>
                    <label for='$attribute'>$label</label>";
  
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

        $fieldHtml .= "</div>";
        
                        // $fieldHtml .= "     <div class='form__message invalid-feedback'>
                        //         $error
                        //     </div>
                        // </div>";
        
        return $fieldHtml;
    }
    
}