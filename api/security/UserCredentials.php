<?php

namespace api\security;


use common\models\user\User;
use Facebook\Facebook;
use OAuth2\GrantType\GrantTypeInterface;
use OAuth2\RequestInterface;
use OAuth2\ResponseInterface;
use OAuth2\ResponseType\AccessTokenInterface;
use OAuth2\Storage\UserCredentialsInterface;

class UserCredentials implements GrantTypeInterface
{

    private $userInfo;

    protected $storage;

    /**
     * @param OAuth2\Storage\UserCredentialsInterface $storage REQUIRED Storage class for retrieving user credentials information
     */
    public function __construct(UserCredentialsInterface $storage)
    {
        $this->storage = $storage;
    }

    public function getQuerystringIdentifier()
    {
        return 'user_credentials';
    }

    public function validateRequest(RequestInterface $request, ResponseInterface $response)
    {
        if (!$request->request("access_token")) {
            if (!$request->request("email") || !$request->request("password")) {
                $response->setError(400, 'invalid_request', 'Missing parameters: required');
                return null;
            } else {

                if (!$this->storage->checkUserCredentials("email:" . $request->request("email"), $request->request("password"))) {
                    $response->setError(401, 'invalid_grant', 'Usuário não existe');
                    return null;
                }
                /* TODO verificar email para logar
                if (User::findByEmail($request->request("email"))->status == User::STATUS_USER_INCOMPLET) {
                    $response->setError(401, 'invalid_grant', 'Por favor, verifique seu email');
                    return null;
                }*/

            }

            $userInfo = $this->storage->getUserDetails("email:" . $request->request("email"));

            if (empty($userInfo)) {
                $response->setError(400, 'invalid_grant', 'Unable to retrieve user information');

                return null;
            }

            if (!isset($userInfo['user_id'])) {
                throw new \LogicException("you must set the user_id on the array returned by getUserDetails");
            }

            $this->userInfo = $userInfo;

            return true;

        } else {

            $fb = new Facebook(['app_id' => FACEBOOK_CLIENT_ID,
                'app_secret' => FACEBOOK_CLIENT_SECRET,
                'default_access_token' => $request->request("access_token"),]);

            $facebookResponse = $fb->get('/me?fields=name,email');
            $userGraph = $facebookResponse->getGraphUser();

            if (!$this->storage->checkUserCredentials($userGraph->getId(), '')) {
                $response->setError(401, 'invalid_grant', 'User doesn\'t exis');

                return null;
            }

            $userInfo = $this->storage->getUserDetails($userGraph->getId());

            if (empty($userInfo)) {
                $response->setError(400, 'invalid_grant', 'Unable to retrieve user information');

                return null;
            }

            if (!isset($userInfo['user_id'])) {
                throw new \LogicException("you must set the user_id on the array returned by getUserDetails");
            }

            $this->userInfo = $userInfo;

            return true;
        }
    }

    public
    function getClientId()
    {
        return null;
    }

    public
    function getUserId()
    {
        return $this->userInfo['user_id'];
    }

    public
    function getScope()
    {
        return isset($this->userInfo['scope']) ? $this->userInfo['scope'] : null;
    }

    public
    function createAccessToken(AccessTokenInterface $accessToken, $client_id, $user_id, $scope)
    {
        return $accessToken->createAccessToken($client_id, $user_id, $scope);
    }
}