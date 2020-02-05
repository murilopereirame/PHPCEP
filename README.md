# PHPCEP

## Objetivo
Endpoint destinado a consultas de CEP com PHP

## Obter cep

### Request
`GET /cep/`

    curl -X GET -G "http://localhost:7000/cep/" -d "cep=19041330"
    
### Response

    HTTP/1.1 200 OK
    Date: Wed, 05 Feb 2020 15:24:30 GMT
    Status: 200 OK
    Connection: close
    Content-Type: application/json

    {
        "endereco": "Praça Alfredo Issa",
        "bairro": "Centro",
        "cidade": "São Paulo",
        "uf": "SP",
        "cep": "01033-040",
        "unico": "N"
    }
