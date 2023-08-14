<?php
$endpoint = $argv[1];
$xmlContent = $argv[2];
$method = $argv[3];

// Opções do cliente SOAP
$options = array(
    'location' => $endpoint,
    'uri' => 'http://www.ans.gov.br/padroes/tiss/schemas', 
    'trace' => 1, // Ativar rastreamento
    'exceptions' => 1, // Ativar exceções
);

// Criação do cliente SOAP
$client = new SoapClient(null, $options);

try {
    // Invoca o método da API SOAP com o conteúdo XML e método específico
    $response = $client->__doRequest($xmlContent, $endpoint, $method, SOAP_1_1, 0);
    $json_data = json_encode($response, JSON_PARTIAL_OUTPUT_ON_ERROR);
    echo $json_data;

    // Processa a resposta da API
    print_r($response);
} catch (SoapFault $e) {
    echo 'Erro na requisição SOAP: ' . $e->getMessage();
}
?>
