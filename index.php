<?php
$endpoint = $argv[1];
$xmlContent = $argv[2];
$method = $argv[3];

/*$filePath = __DIR__ . '/meuarquivo.xml';
$xmlContent = file_get_contents($filePath);
$endpoint = $argv[1];
$method = $argv[2];*/

// Opções do cliente SOAP
$options = array(
    'location' => $endpoint,
    'uri' => 'http://www.ans.gov.br/padroes/tiss/schemas', 
    'trace' => 1,      // Ativar rastreamento
    'exceptions' => 1, // Ativar exceções
);

// Criação do cliente SOAP
$client = new SoapClient(null, $options);

try {
    $noh = '';
    if ($method == 'tissSolicitacaoProcedimento_Operation') {
        $noh = 'autorizacaoProcedimentoWS';
    } elseif ($method == 'tissCancelaGuia_Operation') {
        $noh = 'reciboCancelaGuiaWS';
    } else {
        $arr = array('status' => false, 'method' => 'metodo nao previsto');
        echo json_encode($arr);
        return false;
    }

    // Invoca o método da API SOAP com o conteúdo XML e método específico
    $response = $client->__doRequest($xmlContent, $endpoint, $method, SOAP_1_1, 0);

    // Verificar se a resposta contém um <soap:Fault>
    if (strpos($response, '<soap:Fault>') !== false) {
        $xmlObject = simplexml_load_string($response);
        /*$faultcode = $xmlObject->xpath('//soap:Fault/faultcode');
        $faultstring = $xmlObject->xpath('//soap:Fault/faultstring');
        echo json_encode(['status' => false, 'error' => "SOAP Fault: {$faultcode[0]} - {$faultstring[0]}"], JSON_PRETTY_PRINT);*/
        $fault = $xmlObject->xpath('//soap:Fault');
        echo json_encode(['status' => false, 'error' => $fault], JSON_PRETTY_PRINT);
        return false;
    }

    $xmlObject = simplexml_load_string($response);
    if (!$xmlObject) {
        echo json_encode(['status' => false, 'error' => 'Falha ao processar a resposta XML.'], JSON_PRETTY_PRINT);
        return false;
    }

    //$result = $xmlObject->xpath('//soap:ans');
    $result = $xmlObject->children('soap', true)->Body->children('ans', true)->$noh;
    if (empty($result)) {
        echo json_encode(['status' => false, 'error' => 'Resposta inesperada.'], JSON_PRETTY_PRINT);
        return false;
    }

    echo json_encode(['status' => true, 'response' => $result], JSON_PRETTY_PRINT);
    return true;

} catch (SoapFault $e) {
    echo json_encode(['status' => false, 'error' => "Erro na requisicao SOAP: {$e->getMessage()}"], JSON_PRETTY_PRINT);
    return false;
}
?>