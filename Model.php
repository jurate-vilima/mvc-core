<?php
namespace JurateVilima\MvcCore;

abstract class Model {
    public $errors = [];
    public $errorMessages = [
        'required' => "{label} is required field",
        'email' => "{label} is invalid",
        'min' => "{label} value must be at least {value} characters long",
        'max' => "{label} value must be no more than {value} characters",
        'match' => "{label} doesn't match {value} field",
        'unique' => "{label} should be unique",
    ];
    abstract protected function rules();
    abstract protected function ruleLabels();
    abstract protected function getDbAttributes();
    abstract protected function tableName();

    public function loadData($data) {
        foreach($data as $key => $value) {
            if(property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function validate($rules = null) {
        if(!$rules)
            $rules = $this->rules();

        //attribute- field name, rules- rules, applied to this
        foreach($rules as $attribute => $ruleArr) {
            foreach($ruleArr as $rule) {
                if(is_string($rule)) {
                    $this->applyRule($rule, $attribute);
                }

                if(is_array($rule)) {
                    //var_dump(array_values($rule)[0]);
                    $this->applyRuleWithParams($rule, $attribute);
                }
            }
        }
    }

    public function addError($rule, $attribute, $value = '') {
        $this->errors[$attribute][] = $this->formatError($rule, $attribute, $value);
    }

    public function formatError($rule, $attribute, $value = '') {
        $label = $this->ruleLabels()[$attribute];
        $error = str_replace("{label}", $label, $this->errorMessages[$rule]);

        if($value) 
            $error = str_replace("{value}", $value, $error);

        return $error;
    }

    // display the first error if input has several errors
    public function getFirstError($attribute) {
        return $this->errors[$attribute][0];
    }

    //  REFACTOR
    // apply validation rules that are a string- 'required', 'email' etc.
    // attribute- fieldname
    public function applyRule($rule, $attribute) {
        switch($rule) {
            case 'required': 
                if(!$this->$attribute)
                    $this->addError($rule, $attribute);
                break;
            case 'email':
                if(!filter_var($this->$attribute, FILTER_VALIDATE_EMAIL))
                    $this->addError($rule, $attribute);
                break;
            case 'unique':
                $tableName = $this->tableName();

                if(Application::$app->db->findOne($tableName, [$attribute => $this->$attribute])) {
                    $this->addError($rule, $attribute);
                }
                break;
        }
    }

    //apply validation rules with parameters, rules with condition
    public function applyRuleWithParams($rule, $attribute) {
        foreach($rule as $key => $value) {
            switch($key) {
                case 'min': 
                    if(strlen($this->$attribute) < $value)
                        $this->addError($key, $attribute, $value);
                    break;
                case 'max':
                    if(strlen($this->$attribute) > $value)
                        $this->addError($key, $attribute, $value);
                    break;
                case 'match':
                    if($this->$attribute !== $this->$value) {
                        $matchLabel = $this->ruleLabels()[$value];
                        $this->addError($key, $attribute, $matchLabel);
                    }
                    break;
            }
        }
        
    }

    public function save() {
        $tableName = $this->tableName();
        $attributesToSave = $this->getDbAttributes();
        $valueArr = createKeyValueArray($this, $attributesToSave);

        Application::$app->db->insertRecord($tableName, $valueArr);
        Application::$app->session->setFlash('success', 'You registered successfuly!');  
    }
   
}