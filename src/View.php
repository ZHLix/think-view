<?php

namespace zhlix\view;

class View
{
    public function __construct ()
    {
        config(['view_path' => __DIR__ . '/../view/'], 'view');
    }
}