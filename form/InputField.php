<?php

namespace app\core\form;

use app\core\Model;

class InputField extends BaseField
{
    public const TYPE_TEXT = 'text';
    public const TYPE_PASSWORD = 'password';
    public const TYPE_NUMBER = 'number';
    public const TYPE_EMAIL = 'email';
    public const TYPE_DATE = 'date';
    public string $type;

    public function __construct(Model $model, string $attribute)
    {
        parent::__construct($model, $attribute);
        $this->type = self::TYPE_TEXT;
    }
    protected function renderInput(): string
    {
        return sprintf('<input type="%s" name="%s" value="%s" class="form-control%s"/>',
            $this->type,
            $this->attribute,
            $this->model->{$this->attribute},
            $this->model->hasError($this->attribute) ? ' is-invalid' : '',
        );

    }

    public function passwordField(): static
    {
        $this->type = self::TYPE_PASSWORD;

        return $this;
    }
}