<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Error as GlobalError;
use GuzzleHttp\Client;
use PHPUnit\Framework\Error\Error;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->items = 0;
        $this->item;
        $this->client = new Client([
            'base_uri' => 'http://localhost',
        ]);
    }

    /**
     * @Given existe tabela :arg1, que podem ser adicionados inumeros itens
     */
    public function existeTabelaQuePodemSerAdicionadosInumerosItens($arg1)
    {
        $response = $this->client->request('GET', '/api/lista');
        $body = $response->getBody();
        $stringBody = (string) $body;
        $response = json_decode($stringBody, JSON_FORCE_OBJECT);

        if (count($response) >= 1) {
            $this->items = count($response);
            return true;
        } else {
            throw new GlobalError();
        }
        // throw new PendingException();
    }

    /**
     * @When Eu adiciono um item com o titulo: :arg1 à tabela
     */
    public function euAdicionoUmItemComOTituloATabela($arg1)
    {
        $response = $this->client->request('POST', '/api/lista', [
            'form_params' => [
                'title' => $arg1,
                'status' => 1
            ]
        ]);
        $body = $response->getBody();
        $stringBody = (string) $body;
        $response = json_decode($stringBody, JSON_FORCE_OBJECT);
        if ($response['title'] == $arg1) {
            $this->item = $response;
            return true;
        } else {
            throw new GlobalError();
        }
    }

    /**
     * @Then Devo ter :arg1 novo item cadastrado
     */
    public function devoTerNovoItemCadastrado($arg1)
    {
        $response = $this->client->request('GET', '/api/lista');
        $body = $response->getBody();
        $stringBody = (string) $body;
        $response = json_decode($stringBody, JSON_FORCE_OBJECT);

        if (count($response)) {
            return true;
        } else {
            throw new GlobalError();
        }
    }

    /**
     * @Then o status desse item deve ser :arg1
     */
    public function oStatusDesseItemDeveSer($arg1)
    {
        if ($this->item['status'] == 1) {
            return true;
        } else {
            throw new GlobalError();
        }
    }
}
