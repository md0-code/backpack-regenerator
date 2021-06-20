<?php

return [
    // Backpack route name (if changed, the routes file must published and updated accordingly)
    'route_name' => 'reports',

    // Backpack permissions
    'allow_create'  => true,
    'allow_update'  => true,
    'allow_delete'  => true,
    'allow_show'    => true,
    'allow_clone'   => true,

    // Shows only reports with a certain tag
    'restrict_by_tag' => '',

    // Hide columns that are not meant for regular users
    'display_name_column' => false,
    'display_tag_column' => false,
];
