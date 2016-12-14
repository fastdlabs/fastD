<?php

route()->get('/', 'Http\Controller\IndexController@welcomeAction');

route()->get('/foo/{name}', 'Http\Controller\IndexController@sayHelloAction');