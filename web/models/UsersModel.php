<?php

namespace App\Models;


/**
 * @property int user_id,
 * @property string username,
 * @property string password,
 * @property float age,
 * @property int gender,
 * @property float height,
 * @property float weight,
 * @property string created_at,
 * @property string updated_at
 */
class UsersModel extends \Fux\Database\Model\Model implements \App\Packages\Auth\Contracts\Authenticatable
{

    protected static $tableName = 'users';
    protected static $tableFields = ['user_id', 'username', 'password', 'age', 'gender', 'height', 'weight', 'created_at', 'updated_at'];
    protected static $primaryKey = ['user_id'];

    public static function getAuthIdentifierName()
    {
        return 'username';
    }

    public function getAuthIdentifier()
    {
        return $this->username;
    }

    public static function getAuthPasswordName()
    {
        return 'password';
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function checkRememberToken($token)
    {
        return false;
    }

    public function getRememberToken()
    {
        return false;
    }

    public function deleteRememberToken($value)
    {
        return true;
    }

    public static function getOtpIdentifierName()
    {
        return 'otp';
    }

    /**
     * Check if the user account is "confirmed", if the return value is not TRUE the authentication will throw an
     * Exception
     *
     * @return bool
     */
    public function isConfirmed()
    {
        return 1;
    }

    /**
     * Return an associative array of regular expression that must be validated in order to have a "valid" password for the instance.
     * Each key is a regular expression, each value is a human-readable explanation of the regex that will be showed
     * on the client site in case of errors.
     *
     *
     * @return string[]
     */
    public static function getPasswordStrengthRules()
    {
        return [
            "[A-Z]+" => "Almeno 1 carattere maiuscolo", //At least 1 upper case character
            "[a-z]+" => "Almeno 1 carattere minuscolo", //At least 1 lower case character
            "[0-9]+" => "Almeno 1 numero", //At least 1 number
            "[\W]+" => "Almeno 1 carattere speciale (!, £, $, %, etc.)", //At least 1 special character
            "^.{8,32}$" => "Lunghezza compresa tra gli 8 e 32 caratteri" //At least 8 characters. Maximum 32 characters.
        ];
    }
}
