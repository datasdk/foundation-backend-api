<?php

return [
    'name' => 'Chat',

    'messagesPerPage' => 25,


     'admin' => [

  
        'navigationbar'=>[
            
            "group" => "chat",  
            
            "sorting" => 700,

            "link" => ['name' => 'Chat','icon'=> 'fas fa-comment','link' => 'chat.index', 'new_window' => false],

            "submenu" => [
   
                [ "icon" => "fas fa-cog", "name" => "Settings", "link" => "settings.chat.index", 'new_window' => false], 

            ],

              
        ],
    
    ]

];
