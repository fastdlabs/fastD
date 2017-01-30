<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Auth;


/**
 * Interface AuthenticateInterface
 * @package Auth
 */
interface AuthenticateInterface
{
    /**
     * Find model by it's identifiers.
     *
     * @param mixed $id
     * @return static
     */
    public static function findIdentity($id);

    /**
     * Get the auth id that used to store in session.
     *
     * @return mixed
     */
    public function getAuthId();

    /**
     * Check whether the given password is correct.
     *
     * @param $password
     * @return boolean
     */
    public function validatePassword($password);
}