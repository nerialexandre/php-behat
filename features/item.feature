@cesta
Feature: Lista de itens

    Api para cadastrar itens numa lista
    Regras:
    - nenhuma

    Scenario: Cadastrando novo item
        Given existe tabela "lista", que podem ser adicionados inumeros itens
        When Eu adiciono um item com o titulo: "Sith Lord Lightsaber" à tabela
        Then Devo ter 1 novo item cadastrado
        And o status desse item deve ser "true"
