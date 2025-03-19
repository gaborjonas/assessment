<?php

function shortenUrl(string $longUrl): string
{
    return substr(
        base64_encode(sha1($longUrl)),
        0,
        9
    );
}

var_dump(shortenUrl('y=='));
