<?php

namespace grimm1994\grimmCore;

use grimm1994\grimmCore\db\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}