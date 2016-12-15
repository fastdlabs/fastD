<?php

route()->get('/', 'IndexController@welcome');

route()->get('/foo/{name}', 'IndexController@sayHello');