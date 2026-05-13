<?php

namespace Plugin\ApiToken42\Controller\Admin;

use Eccube\Controller\AbstractController;
use League\Bundle\OAuth2ServerBundle\Manager\ClientFilter;
use League\Bundle\OAuth2ServerBundle\Manager\ClientManagerInterface;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Nyholm\Psr7\Factory\Psr17Factory;
use Plugin\ApiToken42\Entity\UserEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TokenGeneratorController extends AbstractController
{
    public function __construct(
        private ClientManagerInterface $clientManager,
        private AuthorizationServer $authorizationServer,
    ) {
    }

    /**
     * @Route("/%eccube_admin_route%/api/token-generator", name="admin_api_token_generator", methods={"GET"})
     * @Template("@ApiToken42/admin/TokenGenerator/index.twig")
     */
    public function index(): array
    {
        $clients = $this->clientManager->list(ClientFilter::create());

        return ['clients' => $clients];
    }

    /**
     * @Route(
     *     "/%eccube_admin_route%/api/token-generator/generate/{identifier}",
     *     name="admin_api_token_generator_generate",
     *     methods={"POST"}
     * )
     */
    public function generate(Request $request, string $identifier): JsonResponse
    {
        $this->isTokenValid();

        $client = $this->clientManager->find($identifier);
        if (null === $client || !$client->isActive()) {
            return $this->json(['error' => 'クライアントが見つかりません'], 404);
        }

        $grants = array_map('strval', $client->getGrants());
        $redirectUris = $client->getRedirectUris();

        if (!in_array('authorization_code', $grants, true)) {
            return $this->json([
                'error' => 'このクライアントは authorization_code グラントが設定されていません',
            ], 400);
        }

        if (empty($redirectUris)) {
            return $this->json([
                'error' => 'このクライアントにリダイレクト URI が設定されていません',
            ], 400);
        }

        try {
            $factory = new Psr17Factory();
            $redirectUri = (string) $redirectUris[0];
            $scopeIds = array_map('strval', $client->getScopes());

            log_info('ApiToken42: Step1 validateAuthorizationRequest', ['redirect_uri' => $redirectUri]);

            $authPsrRequest = $factory->createServerRequest('GET', '/oauth/authorize')
                ->withQueryParams([
                    'response_type' => 'code',
                    'client_id' => $client->getIdentifier(),
                    'redirect_uri' => $redirectUri,
                    'scope' => implode(' ', $scopeIds),
                ]);

            $authorizationRequest = $this->authorizationServer->validateAuthorizationRequest($authPsrRequest);

            log_info('ApiToken42: Step2 completeAuthorizationRequest');

            $member = $this->getUser();
            $authorizationRequest->setUser(new UserEntity($member->getLoginId()));
            $authorizationRequest->setAuthorizationApproved(true);

            $redirectResponse = $this->authorizationServer->completeAuthorizationRequest(
                $authorizationRequest,
                $factory->createResponse(302)
            );

            $location = $redirectResponse->getHeaderLine('Location');
            parse_str((string) parse_url($location, PHP_URL_QUERY), $params);
            $code = $params['code'] ?? null;

            log_info('ApiToken42: Step3 code', ['code_exists' => $code !== null]);

            if (null === $code) {
                return $this->json(['error' => '認証コードの生成に失敗しました'], 500);
            }

            log_info('ApiToken42: Step4 respondToAccessTokenRequest');

            $tokenPsrRequest = $factory->createServerRequest('POST', '/oauth/token')
                ->withParsedBody([
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $redirectUri,
                    'client_id' => $client->getIdentifier(),
                    'client_secret' => $client->getSecret(),
                ]);

            $tokenResponse = $this->authorizationServer->respondToAccessTokenRequest(
                $tokenPsrRequest,
                $factory->createResponse()
            );

            $tokens = json_decode((string) $tokenResponse->getBody(), true);

            log_info('ApiToken42: 完了', ['has_access_token' => isset($tokens['access_token'])]);

            return $this->json($tokens);
        } catch (OAuthServerException $e) {
            log_error('ApiToken42: OAuthServerException', [$e->getMessage(), $e->getHint() ?? '']);

            return $this->json(['error' => $e->getMessage()], $e->getHttpStatusCode());
        } catch (\Throwable $e) {
            log_error('ApiToken42: Throwable', [$e->getMessage(), $e->getFile().':'.$e->getLine()]);

            return $this->json(['error' => 'トークンの生成に失敗しました: '.$e->getMessage()], 500);
        }
    }
}
