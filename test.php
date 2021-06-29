<?php


//toto zoberie parameter heslo z webky a vypise (echo) ho na screen, potom ho mozeme vizualne porovnat
echo password_hash($_GET["heslo"],PASSWORD_DEFAULT);
