<?php

namespace grimm\grimmmvc\form;

use grimm\grimmmvc\Model;

abstract class BaseField
{
    /**
     * @param Model $model
     * @param string $attribute
     */
    public function __construct(public Model $model, public string $attribute)
    {

    }

    abstract protected function renderInput(): string;

    public function __toString(): string
    {
        $this->model->getLabel($this->attribute);
        $this->model->getFirstError($this->attribute);
        $this->renderInput();

        return sprintf('
           <div class="mb-3">
                <label>%s</label>
                     %s
                <div class="invalid-feedback">%s</div>
            </div>
        ',
            $this->model->getLabel($this->attribute),
            $this->renderInput(),
            $this->model->getFirstError($this->attribute)
        );
    }
}