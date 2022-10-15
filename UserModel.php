<?php

namespace grimm\grimmmvc;

use grimm\grimmmvc\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}