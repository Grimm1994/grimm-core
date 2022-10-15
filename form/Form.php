<?php

namespace grimm994\grimmCore\form;

use grimm994\grimmCore\Model;

class Form
{
    public static function begin(string | null $action, string $method): static
    {
        echo sprintf('<form action="%s" method="%s">', $action, $method);

        return new static();
    }

    public static function end(): void
    {
        echo '</form>';
    }

    public function inputField(Model $model, string $attribute): BaseField
    {
        return new InputField($model, $attribute);
    }

    public function textareaField(Model $model, string $attribute): BaseField
    {
        return new TextareaField($model, $attribute);
    }
}