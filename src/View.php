<?php

namespace zhlix\view;

class View
{
    public function __construct ()
    {
        config(['view_path' => __DIR__ . '/../view/'], 'view');
    }

    public function index ()
    {
        return view('/templates/index');
    }

    public function table ()
    {
        return view('/templates/table');
    }

    public function form ()
    {
        return view('/templates/form');
    }

    public function login ()
    {
        return view('/templates/login');
    }
}