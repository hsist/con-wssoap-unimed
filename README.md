# con_wssoap_unimed
Consumo web service SOAP Autorizador Unimed Brasil

<hr>

<b>Exemplo de execução:</b>
```
php con-wssoap-unimed/index.php 'https://portal.unimedjaboticabal.coop.br/gcs/tiss/solicitacaoProcedimento' '<soapenv:Envelope>...</soapenv:Envelope>' 'tissSolicitacaoProcedimento_Operation'
```
<b>Exemplo de execução com arquivo xml local:</b>
```
php con-wssoap-unimed/index.php 'http://s20atd-hml.avare.sgusuite.com.br/WSTiss3/http/v4_01_00/tissSolicitacaoProcedimento' 'tissSolicitacaoProcedimento_Operation'
```
