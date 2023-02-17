
<?php
/**
 * Auth Helper
 *
 * @author       Firoz Ahmad Likhon <likh.deshi@gmail.com>
 * @purpose      Auth Helper
 */

if(! function_exists("check")) {

    /**
     * Check if current user is logged in.
     *
     * @return bool
     */
    function check()
    {
        $auth = new Authenticate();
        return $auth->loginStatus();
    }
}

if(! function_exists("can")) {

    /**
     * Check if current user has a permission by its name.
     *
     * @param $permissions
     * @return bool
     */
    function can($permissions)
    {
        $auth = new Authenticate();
        return $auth->can($permissions);
    }
}

if(! function_exists("hasRole")) {

    /**
     * Checks if the current user has a role by its name.
     *
     * @param $roles
     * @return bool
     */
    function hasRole($roles)
    {
        $auth = new Authenticate();
        return $auth->hasRole($roles);
    }
}