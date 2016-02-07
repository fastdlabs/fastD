<?php
Routes::get(['/', 'name' => 'root'], 'Welcome:Controllers:Index@welcomeAction');