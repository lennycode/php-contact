<?php

namespace di\database;
interface iDataFunctions
{
    function create();

    function delete();

    function update();

    function fetch();

    function adhoc($sql);
}