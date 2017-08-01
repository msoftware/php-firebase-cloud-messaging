<?php

namespace sngrl\PhpFirebaseCloudMessaging;

use GuzzleHttp;

/**
 * @author sngrl
 */
class Client implements ClientInterface
{
    const DEFAULT_API_URL = 'https://fcm.googleapis.com/fcm/send';
    const DEFAULT_TOPIC_ADD_SUBSCRIPTION_API_URL = 'https://iid.googleapis.com/iid/v1:batchAdd';
    const DEFAULT_TOPIC_REMOVE_SUBSCRIPTION_API_URL = 'https://iid.googleapis.com/iid/v1:batchRemove';

    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var string
     */
    private $proxyApiUrl;

    /**
     * @var GuzzleHttp\Client
     */
    private $guzzleClient;

    /**
     * @param GuzzleHttp\ClientInterface $client
     */
    public function injectGuzzleHttpClient(GuzzleHttp\ClientInterface $client)
    {
        $this->guzzleClient = $client;
    }

    /**
     * Add your server api key here
     * read how to obtain an api key here: https://firebase.google.com/docs/server/setup#prerequisites
     *
     * @param string $apiKey
     *
     * @return Client
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * People can overwrite the api url with a proxy server url of their own
     *
     * @param string $url
     * @return Client
     */
    public function setProxyApiUrl($url)
    {
        $this->proxyApiUrl = $url;

        return $this;
    }

    /**
     * sends your notification to the google servers and returns a guzzle response object
     * containing their answer.
     *
     * @param Message $message
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\RequestException
     */
    public function send(Message $message)
    {
        return $this->guzzleClient->post(
            $this->getApiUrl(),
            [
                'headers' => [
                    'Authorization' => sprintf('key=%s', $this->apiKey),
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($message),
            ]
        );
    }

    /**
     * @param integer $topic_id
     * @param array|string $recipientsTokens
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function addTopicSubscription($topic_id, $recipientsTokens)
    {
        return $this->processTopicSubscription(
            $topic_id,
            $recipientsTokens,
            self::DEFAULT_TOPIC_ADD_SUBSCRIPTION_API_URL
        );
    }


    /**
     * @param integer $topic_id
     * @param array|string $recipientsTokens
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function removeTopicSubscription($topic_id, $recipientsTokens)
    {
        return $this->processTopicSubscription(
            $topic_id,
            $recipientsTokens,
            self::DEFAULT_TOPIC_REMOVE_SUBSCRIPTION_API_URL
        );
    }


    /**
     * @param integer $topic_id
     * @param array|string $recipientsTokens
     * @param string $url
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function processTopicSubscription($topic_id, $recipientsTokens, $url)
    {
        if (!is_array($recipientsTokens)) {
            $recipientsTokens = [$recipientsTokens];
        }

        return $this->guzzleClient->post(
            $url,
            [
                'headers' => [
                    'Authorization' => sprintf('key=%s', $this->apiKey),
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode(
                    [
                        'to' => '/topics/'.$topic_id,
                        'registration_tokens' => $recipientsTokens,
                    ]
                ),
            ]
        );
    }

    /**
     * @return string
     */
    private function getApiUrl()
    {
        return isset($this->proxyApiUrl) ? $this->proxyApiUrl : self::DEFAULT_API_URL;
    }
}