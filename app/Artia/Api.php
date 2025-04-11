<?php

namespace App\Artia;

use Exception;
use App\Artia\Request\Curl;
use App\Artia\Builder\QueryBuilder;
use App\Artia\Builder\MutationBuilder;
use App\Artia\Token\Token;

class Api extends Curl
{
    public function __construct()
    {
        $token = Token::getCacheTokenFile();

        $this->headers = [
            'Authorization: Bearer ' . $token->token,
        ];
    }

    /**
     * @var string
     */
    protected string $url = 'https://app.artia.com/graphql';

    /**
     * @var string
     */
    private string $name;

    /**
     * @var array
     */
    private array $arguments;

    /**
     * @var array
     */
    private array $body;

    /**
     * @param string $name
     * @return $this
     */
    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public function arguments(array $arguments): self
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @param array $body
     * @return $this
     */
    public function body(array $body = []): self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * @param MutationBuilder|QueryBuilder $builder
     * @return @this
     */
    public function build(MutationBuilder|QueryBuilder $builder): self
    {
        $this->query = $builder->name($this->name)
            ->arguments($this->arguments)
            ->body($this->body)
            ->build();

        return $this;
    }

    /**
     * @throws Exception
     * @return null|object
     */
    public function call(): ?object
    {
        $response = $this->send();

        if (isset($response->errors)) {
            foreach ($response->errors as $err) {
                // throw new Exception($err->message);
            }
        }

        return $response;
    }
}
