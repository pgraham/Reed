<?php
/**
 * =============================================================================
 * Copyright (c) 2010, Philip Graham
 * All rights reserved.
 *
 * This file is part of Reed and is licensed by the Copyright holder under the
 * 3-clause BSD License.  The full text of the license can be found in the
 * LICENSE.txt file included in the root directory of this distribution or at
 * the link below.
 * =============================================================================
 *
 * @license http://www.opensource.org/licenses/bsd-license.php
 * @package Reed
 */
/**
 * This class ensures that the requesting user is assigned to a session.  The
 * session can optionally be associated with a user.
 *
 * @author Philip Graham <philip@lightbox.org>
 * @package Reed
 */
class Reed_Auth {

    public static $sessionId = null;

    /**
     * Authenticate the request.
     *
     * @param PDO - Database connection to use to validate/update/store session
     *              information
     */
    public static function init($pdo) {
        if (self::$sessionId !== null) { // We've already been through this
            return;
        }

        if (isset($_COOKIE['reedsessid'])) {
            // This user has been here before
            $sessionId = $_COOKIE['reedsessid'];

            $stmt = $pdo->prepare(
                'SELECT * FROM sessions WHERE sessid=:sessid');
            $stmt->execute(array(':sessid' => $sessionId));

            $row = $stmt->fetch();
            $curTime = date('Y-m-d H:i:s');
            if ($row['expires'] >= $curTime) {
                // Renew the session
                self::$sessionId = $sessionId;
                $expires = date('Y-m-d H:i:s',
                    time() + Reed_Config::getSessionTtl());

                $stmt = $pdo->prepare(
                    'UPDATE sessions SET expires = :expires WHERE id=:id');
                $stmt->execute(array(
                    ':id'      => $row['id'],
                    ':expires' => $expires
                ));
                return;
            }
        }

        // This user has not been here before, has an expired session or has
        // cleared their cookies
        $prefix = str_pad(rand(0, 100), 3, '0', STR_PAD_LEFT);
        $sessionId = uniqid($prefix.'-', true);
        $expires = date('Y-m-d H:i:s', time() + Reed_Config::getSessionTtl());

        $stmt = $pdo->prepare('INSERT INTO sessions (sessid, expires) VALUES
            (:sessid, :expires)');
        $stmt->execute(array(
            ':sessid'  => $sessionId,
            ':expires' => $expires
        ));

        $path = Reed_Config::getWebSiteRoot();
        if (substr($path, -1) != '/') {
            $path = $path.'/';
        }

        setcookie('reedsessid', $sessionId, time() + 31556926, $path);
        self::$sessionId = $sessionId;
    }
}
