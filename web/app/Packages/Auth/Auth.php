<?php

namespace App\Packages\Auth;

use App\Packages\Auth\Contracts\Authenticatable;
use App\Packages\Auth\Contracts\AuthOTPProviderInterface;
use App\Packages\Auth\Exceptions\AccountNotConfirmedException;
use App\Packages\Auth\Exceptions\InvalidCredentialsException;
use App\Packages\Auth\Exceptions\InvalidPasswordStrengthException;
use App\Packages\Auth\Exceptions\OtpThrottlingLimitException;
use Fux\Database\Model\Model;

class Auth
{

    /**
     * Make a login attempt for a specific model class with some provided credentials (or matching criterion)
     *
     * @param string | Authenticatable $modelName
     * @param array $credentials
     *
     * @return bool
     *
     * @throws InvalidCredentialsException
     * @throws \Exception
     */
    public static function attempt($modelName, $credentials)
    {
        if (!class_exists($modelName))
            throw new \Exception("The class $modelName does not exists");

        $userFieldName = $modelName::getAuthIdentifierName();
        $pwFieldName = $modelName::getAuthPasswordName();

        if (!isset($credentials[$userFieldName]))
            throw new \Exception("The field '$userFieldName' has not been passed in credentials object");

        if (!isset($credentials[$pwFieldName]))
            throw new \Exception("The field '$pwFieldName' has not been passed in credentials object");

        $matchingFields = $credentials;
        unset($matchingFields[$pwFieldName]);

        $user = $modelName::getWhere($matchingFields);
        if (!$user)
            throw new InvalidCredentialsException();

        $realPw = $user->getAuthPassword();
        if (!password_verify($credentials[$pwFieldName], $realPw))
            throw new InvalidCredentialsException();

        if (!$user->isConfirmed()) throw new AccountNotConfirmedException($user);

        $_SESSION[$modelName] = $user->toArray();

        return true;
    }


    /**
     * Check if a specific user is logged  for a specific model class
     *
     * @param string $modelName
     *
     * @return void
     */
    public static function login($modelName, $user)
    {
        $_SESSION[$modelName] = $user->toArray();
    }


    /**
     * Check if a specific user is logged  for a specific model class
     *
     * @param string $modelName
     *
     * @return bool
     */
    public static function check($modelName)
    {
        return isset($_SESSION[$modelName]);
    }

    /**
     * Check if a specific user is logged  for a specific model class
     *
     * @param string $modelName
     * @param bool $forceRefetch Weather to refetch logged user data from DB before returning
     *
     * @return Model | Authenticatable | null
     *
     * @throws \Exception
     */
    public static function user($modelName, $forceRefetch = false)
    {
        if (!self::check($modelName)) return null;
        $model = new $modelName($_SESSION[$modelName]);
        if ($forceRefetch) $model->refetch();
        return $model;
    }


    /**
     * Check if a specific user is logged  for a specific model class
     *
     * @param string | Authenticatable $modelName
     *
     * @return bool
     */
    public static function logout($modelName){
        unset($_SESSION[$modelName]);
    }


    /**
     * Generate and send OTP for a given Authenticatable instance and a given Provider class
     *
     * @param Authenticatable $user
     * @param AuthOTPProviderInterface | string $provider
     * @param
     *
     * @throws Exceptions\OtpGenerationException
     * @throws OtpThrottlingLimitException
     */
    public static function sendOtp($user, $provider, $throttling = false)
    {
        if ($throttling){
            if (!$provider::checkOtpThrottling($user)){
                throw new OtpThrottlingLimitException($provider::getOtpThrottlingSeconds());
            }
        }
        $otp = $provider::generateOtp($user);
        return $provider::sendOtp($user, $otp);
    }


    /**
     * Check the given OTP for a given Authenticatable instance
     *
     * @param Authenticatable $user
     * @param AuthOTPProviderInterface | string $provider
     * @param string $otp
     *
     * @throws Exceptions\OtpNotValidException
     */
    public static function checkOtp($user, $provider, $otp)
    {
        return $provider::checkOtp($user, $otp);
    }


    /**
     * Check if the given password is valid for a given model
     *
     * @param string | Authenticatable $modelName
     * @param string
     * @throws InvalidPasswordStrengthException
     */
    public static function checkValidPassword($modelName, $password){
        foreach($modelName::getPasswordStrengthRules() as $regex => $explanation){
            if (!preg_match("/".$regex."/", $password)) throw new InvalidPasswordStrengthException($explanation);
        }
    }

}