<?php


function gravatar_url($email)
{
    return "https://gravatar.com/avatar/"
        . md5($email)
        . http_build_query([
            's' => 60,
            'd' => "http://s3.amazonaws.com/laracasts/images/default-square-avatar.jpg"
        ]);

}
