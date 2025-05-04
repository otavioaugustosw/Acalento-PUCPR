<?php
function format_date($date)
{
    return date("d/m/Y", strtotime($date));
}

function format_hour($hour)
{
    return date("H:i", strtotime($hour));
}