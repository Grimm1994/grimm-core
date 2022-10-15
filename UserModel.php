<?php

namespace grimm994\grimmCore;

use grimm994\grimmCore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}