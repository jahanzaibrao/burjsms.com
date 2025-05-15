<?php
Doo::loadModel('base/ScUsersPermissionsBase');

class ScUsersPermissions extends ScUsersPermissionsBase
{

    public function addUserPermissions($uid, $pgid, $perms)
    {
        $this->user_id = $uid;
        $this->pg_id = $pgid;
        $this->perm_data = $perms;
        Doo::db()->insert($this);
    }
}
