<?php
class SubmissaoModel
{
    private mysqli $con;

    public function __construct(mysqli $con)
    {
        $this->con = $con;
    }
}
?>