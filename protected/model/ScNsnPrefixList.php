<?php
Doo::loadModel('base/ScNsnPrefixListBase');

class ScNsnPrefixList extends ScNsnPrefixListBase
{
    public function countPrefixesByCoverage($country_prefix, $iso = '')
    {
        $this->country_prefix = $country_prefix;
        if ($iso != "") $this->country_iso = $iso;
        $results = Doo::db()->find($this, array('select' => 'count(*) as total', 'limit' => 1));
        return isset($results->total) ?  intval($results->total) : 0;
    }
}
