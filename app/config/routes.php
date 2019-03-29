<?php

route()->addRoute('GET', '/', function () {
    throw new Exception('hello');
});
