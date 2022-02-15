<?php

/**
 * SessionWrapper.php
 *
 * create a wrapper for the SESSION global array
 *
 * Author: CF Ingrams
 * Email: <clinton@cfing.co.uk>
 * Date: 22/10/2017
 *
 *
 * @author CF Ingrams <clinton@cfing.co.uk>
 * @copyright CFI
 *
 */

namespace Sessions;

class SessionWrapper implements SessionInterface
{
    public function __construct() { }

    public function __destruct() {
    }

    public function setSessionVar($session_key, $session_value_to_set)
    {
        $session_value_set_successfully = false;
        if (!empty($session_value_to_set))
        {
            $_SESSION[$session_key] = $session_value_to_set;
            if (strcmp($_SESSION[$session_key], $session_value_to_set) == 0)
            {
                $session_value_set_successfully = true;
            }
        }
        return $session_value_set_successfully;
    }

    public function getSessionVar($session_key)
    {
        $session_value = false;

        if (isset($_SESSION[$session_key]))
        {
            $session_value = $_SESSION[$session_key];
        }
        return $session_value;
    }

    public function unsetSessionVar($session_key)
    {
        $unset_session = false;
        if (isset($_SESSION[$session_key]))
        {
            unset($_SESSION[$session_key]);
        }
        if (!isset($_SESSION[$session_key]))
        {
            $unset_session = true;
        }
        return $unset_session;
    }

    public function setLogger()
    {

    }
}
