<?php

namespace App\DocSign;

require_once(__DIR__ . '/composer/vendor/autoload.php');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;


date_default_timezone_set('America/Sao_Paulo');


class DocSign
{
    const IS_HOMOLOG = false; // Obrigtório mudar quando sair de produção juntamente com as infos abaixo preenchidas

    const API_URL_HOMOLOG = "https://sandbox.clicksign.com/api/"; // SandBox
    // const ACCESS_TOKEN_HOMOLOG = "417d3393-8236-4cd7-a0b8-fef2d57e447f"; // SandBox 
    #const ACCESS_TOKEN_HOMOLOG = "e3d1be7b-f8a3-4c6b-a03a-247598589c3d"; // SandBox marcelovieira1995
    const ACCESS_TOKEN_HOMOLOG = "97c56ba4-a8fc-4019-958b-c7ca26b2787d"; // SandBox ti@pier1.com.br

    const SIGNER_KEY_AUTOMATIC_HOMOLOG = "45aa5e93-587b-44f1-8796-2ab769281989";  // thiago@pier1.com.br key para assinatura automatica via API homolog
    const SIGNER_NAME_AUTOMATIC_HOMOLOG = "Pier Cruise";  
    const SIGNER_EMAIL_AUTOMATIC_HOMOLOG = "thiago@pier1.com.br";  
    const SECRET_HMAC_SHA256_HOMOLOG = "-----BEGIN RSA PRIVATE KEY----- MIIEpAIBAAKCAQEA8GsryCYmnvRDXZwY5seRRD+XWYtcWXr6l4810cN+4i/VyaaQ SfkoIhOEpelg/hPmJ7Or8M4sLhsQFyJTtI25hIEyxAE/dk05dr87trlzs9buQJYr TOnEFuU28Y7wQq1aCQfU1n+FCt+jJz+c8pz1fV2eNfYkf8+tPDCvfoCKuzHD2UR+ +fVXbOstXBdmDGtMPPETrdOQaonILKYVPeL9brpOkIBMtzlvyyIo6tNUFJs6SReB /OrNxDXy5EGbYI/ia11Ab3MGsNYBKWnwfLUU/5Mb9Xa3t6vpohxFuv/RydkJU0eV Ix8IbDR0z8SmTBp3aU7peQ1x9rCM0q5+ruGC9QIDAQABAoIBAQDJK4QJCBNM8mrk C1ey086z4toL7Vaq8QJ6izSnfwPO0+P5xcv6eeehC9/0zCGCuLffBLKc3oeNVMvT 779G10gindESvVCS9u9cc4vNy8QK+Y+Gr6EvzHNuDQI6gjZ8NH2gDNOFYFK81IC6 uLnlMH0iF5Ho6TuFTwtP0BUorvpmwxBFlCkCG+8muBr/HRFpeQ7x8KbYKRrQ5qYI cKShjBBtqcUDrl0sBgtjpZKANdqDZyEkDtX+u1M2ks99eV8SjnnsmQiBm//LnLy1 fsrCyg97wUpXFTjj5nyAhes2oRFQedzoMOlUTXJhNgLYte0dfbfUmgj/ra2dqHea qemiapHJAoGBAPjBPGBm5hfNDgMQyr/XlULzX9TxzBf3kJ92AH5dyIoQcHyqYWrH bXWl5NvQ1621ZSxAu4VGUbeQh2RkFh3Ci33dQlcBHs9ek0MeDfqJn/IWPauTs6nU w1aPFDgNEzJExEAVujIksg1Gz5dCg0yNP4W8vIKD02O2QuNkvf6Upu9LAoGBAPdr x2efaFBQs9L4/z8XLk1uNL5omP7SQXUNvqcx43x7aufXq6V4nAlWaSnue2+6czKW 1fgv4ix/hduvV/ocmG55kcoc8IXTQKinNTs6oAHve6JxZLeqwtpRMUj5OPzEOnix g3uZdMiCuubgK4D2QTOZKWec4XuTsRNR3dgxEK6/AoGAVWENthDbXMP20TqMPHhk NMyP0ekEIh3It9KqrLTHxguKcF+SfDHi8gR0nrX5sReXmC7HriDKn3St49kudhx2 AEdXI8Gwr4BIjzrblWhzS4YyuJdDjUEHosgNKofiVNihe1V5yQgrTL9HNSWRJ087 xE1ZbnqMraa7Io1LYPvoqC0CgYAvAJiXBN9qMoqWz5qdv6eaKg93BKPb9NFejUfk t0OCCGgctwnym2D5HSNJRfbJGOB9gc4QIfiTf35MZ5kM+JW0lv+tJ6ZBpgoof+tK 3y46b+ZKy8PYaGYv1zDHriC3VAfPmdlih5p55OvgpEFfdGiahsQo3NKxCTOgefLs +RU0PQKBgQD1F1Ga/+GjEaZe7+zDlB0Kn3FdsXT2XG8iqB08O424muYZU/asYCKm cS8mYHnFlBPah+I06snSUY1DIyZfpTHEDODy3KgHHziYM9qnMftMcDaPb7AsgIUQ o1YNo4KHZd/H0iVa6+ttNvtg5/rTd8QWFH909TMDgqy42j33LVqM2A== -----END RSA PRIVATE KEY-----"; // secret_hmac_sha256 Homolog

    const API_URL = "https://app.clicksign.com/api/"; // Produção
    const ACCESS_TOKEN = "4db19358-30e7-49c5-9fcb-511b547b9a56"; // token de produção

    const SECRET_HMAC_SHA256 = "-----BEGIN RSA PRIVATE KEY----- MIIEpAIBAAKCAQEAw8raioBrfFDyso9wt0budpTfv0rv965gbtvoykZ8xzjnZZAF vUyDC4CkHySimsMmmzIpFAcYi/q+k8r1S9zmmRj1tvmvtztpeNiztfMacyXbH552 1FQ1ASAGOUS5Z51baec/pTlyFYuG3Nd66tijVdIxtB6AcYUIQzEKTqIhh/4wMr+o WHXuz8oD3BZ6axWXNf0ThkrnqVuJ0qZdgxbuzR31SxAGXtCPf5jCB0sNYAQ88sIR FdgaK3IgUMUp6c5m6JedKgbmQdV6R8k6XcqU3VZlY31j1mmIiVSSp4kFqYqD0hIy rlqlVoEKnbSSrr9uxrFXP0Z6PuM+0nRJy3rEuQIDAQABAoIBAHI3KOu45Hr94caG ZNOVvlBQooG+HmSXRcwF3zP4kJvLNzmJHcADHUMdhSBhx7WOeDv6s/np9cjaukgP Eq9xqtfOtrBJ0daLAwkrKRLQQTLA96XnVByjDtScMOVBkQK/ye7qprck3sdwwHid /yd2vzm2R6ZyUK1oVJyVxBSBAhyJBpPU4gl4DEiSFDNH1RsLfBFy44+URnE8Luo3 6r3+euN2C8FBcYzu9ozwuNLlI12gYxRVv/BrT/RCUxq43QEG3qO41FyZGmj1eOhp ropAwf/0M9QXo/UJ+cEVO4TaMTu90uaEuy0SXwZ5Ox7fZY+C4XbDhbA23mBcXHLO 5vpfv/ECgYEA9ZPOyF5/NA4iOgrYTCxbwrXdoGBsLbxSUGCqpnqe9Lj5PVVXH/1U agVtLTSjzigVXV8LC7e/uXow+T6n5CXTZta2rqZvB4tKo4+QGPpXnh4/en11mvwT XAqiUlhwXHWVZt94WpdxC1/F8Gi50RRCnkvy9ROaXrrXkB6AgklsknUCgYEAzBoi I6bkFRpxmtCUauf5NwcjKM3ltBjMPCeM1XsjdYJLmgNA+uicCyYO6PnMlu0Saxzr fytNqAd6kc6Op0Fd0UDQvx9jnrYB51Vgpr4SlayqxsOkgeA/7/RqGl47RdAJjwwr VUZstFWPUa1TYihIMIm1qzUF58lfW89xungDWLUCgYEA7GpjQ9pVM1P391DBcnOx 0v0qY17wRgdqZx7oqmSZ6pa4uycRPSawwXB/7mki2TEVgFIjXCCFXpYX15tOHgGc RwCl1fU8JlMeg9+Npb4742jTS6TEN3Q9XvqN0iB560j3j5XLU8s/dOe6svrfCD0s 9R/AwdchYdkV9RSoOsVc1JECgYAgxZVzuglnZg6VuCYMoGDAuDGqDJ10dILeqc8Q PCxuYtR2z0zwvMbJd3vvZqS2GyzY7BTYH4DhmQnSPaNkMbe5sLmT5ptdUYYuuePC AkUDqWzma2WPwQ0jH1CmGTlVNVlDkcWndJ5hulc/2x/HZAZrXMFB00q2c/wOxtlR ApIu/QKBgQCGEiLy1xSc5g0vimU6582Hvsv/G/Tvv6wr/iHdBcj+dQn1OqymQ0ST 1hf7iAmENOEqUb22pzDL6Li9ej7of0YclBX7y3u3t4gWRQVs/WFpbH61guIePsjP hOC3FjyIFk69Rv7xWa60DuFzyqF/3h2sm8So0wHeI+fGCW+oCQ5dbw== -----END RSA PRIVATE KEY-----"; // secret_hmac_sha256 Produção
    const SIGNER_KEY_AUTOMATIC = "fcf053ac-b11b-4d62-8f1e-96da5d430f25";  // thiago@pier1.com.br e name Vasconcelos Serviços de Turismo LTDA
    const SIGNER_NAME_AUTOMATIC = "Vasconcelos Serviços de Turismo LTDA";  
    const SIGNER_EMAIL_AUTOMATIC = "thiago@pier1.com.br";  

    const API_VERSION = "v1";

    private $client;
    private $api_url;
    private $url_origin;
    private $token;
    private $signer_name_automatic;
    private $signer_email_automatic;
    private $signer_key_automatic;
    private $secret_hmac_sha256;


    public function __construct($url_origin = "")
    {
        $this->api_url = (self::IS_HOMOLOG ? self::API_URL_HOMOLOG : self::API_URL);
        $this->token = self::IS_HOMOLOG ? self::ACCESS_TOKEN_HOMOLOG : self::ACCESS_TOKEN;

        $this->signer_name_automatic = self::IS_HOMOLOG ? self::SIGNER_NAME_AUTOMATIC_HOMOLOG : self::SIGNER_NAME_AUTOMATIC;
        $this->signer_email_automatic = self::IS_HOMOLOG ? self::SIGNER_EMAIL_AUTOMATIC_HOMOLOG : self::SIGNER_EMAIL_AUTOMATIC;
        $this->signer_key_automatic = self::IS_HOMOLOG ? self::SIGNER_KEY_AUTOMATIC_HOMOLOG : self::SIGNER_KEY_AUTOMATIC;
        $this->secret_hmac_sha256 = self::IS_HOMOLOG ? self::SECRET_HMAC_SHA256_HOMOLOG : self::SECRET_HMAC_SHA256;

        $client = new Client([
            'base_uri' => $this->api_url  . self::API_VERSION . "/",
        ]);

        //$this->url_origin = (self::IS_HOMOLOG ? "http://dev.mycruiseconcierge.com.br" : "https://mycruiseconcierge.com.br"); // . $url_origin;
        $this->url_origin = "https://mycruiseconcierge.com.br";

        //print_r( $client );

        $this->client = $client;
    }

    /**
     * Load widget
     * options [signer_key, url_endpoint, url_origin]
     */

    public function widget()
    {
        $options["url_endpoint"] = str_replace("/api/", "", (self::IS_HOMOLOG ? self::API_URL_HOMOLOG : self::API_URL));
        $options["url_origin"] = $this->url_origin;

        return require_once(__DIR__ . '/widget.php');
    }

    /**
     * Find a document by key and print it
     */

    public function get_documents($key = "")
    {
        try {
            $data = json_decode($this->client->get("documents/{$key}?access_token={$this->token}")->getBody()->getContents(), true);

            $status = 200;
        } catch (ClientException $th) {

            $data = implode(", ", json_decode($th->getResponse()->getBody()->getContents(), true)["errors"]);
            //$data = $th->getResponse()->getBody()->getContents();
            $status = $th->getCode();

            $this->log("get_documents", $data, $status);
        } finally {
            return compact("data", "status");
        }
    }

    /**
     * Create file by base64
     * return array("path" => "", "base64" =>"")
     */

    public function new_file($text_or_file_path)
    {
        $name_file = strftime("%Y-%m-%d-%H-%M-%S") . chr(rand(65, 90));
        $path = "/mycruiseconcierge.com.br/";

        if (strstr($text_or_file_path, ".pdf") || strstr($text_or_file_path, ".PDF")) { // link-do-arquivo.pdf
            $path .=  $name_file . ".pdf";
            $fileCurl = $this->_getFile($text_or_file_path);
            $content_base64 = "data:application/pdf;base64," . base64_encode($fileCurl);
        } else {
            $path .=  $name_file . ".doc";
            $content_base64 = "data:application/msword;base64," . base64_encode($text_or_file_path);
        }

        //var_dump( compact("path", "content_base64", "text_or_file_path", "fileCurl") );

        return compact("path", "content_base64");
    }


    public function create_doc($doc_text = "")
    {

        try {
            $fileBase64 = array("document" => $this->new_file($doc_text));

            $response = $this->client->post("documents?access_token={$this->token}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => $fileBase64
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $status = 200;
        } catch (ClientException $th) {

            $data = implode(", ", json_decode($th->getResponse()->getBody()->getContents(), true)["errors"]);
            //$data = $th->getResponse()->getBody()->getContents();
            $status = $th->getCode();


            $this->log("create_doc", $data, $status);
        } finally {
            return compact("data", "status");
        }
    }
    public function create_signer($signer)
    {

        try {

            $response = $this->client->post("signers?access_token={$this->token}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => array("signer" => $signer)
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $status = 200;
        } catch (ClientException $th) {

            $data = implode(", ", json_decode($th->getResponse()->getBody()->getContents(), true)["errors"]);
            //$data = $th->getResponse()->getBody()->getContents();
            $status = $th->getCode();

            $this->log("create_signer", $data, $status);
        } finally {
            return compact("data", "status");
        }
    }
    public function get_signers($key = "")
    {

        try {
            $data = json_decode($this->client->get("signers/{$key}?access_token={$this->token}")->getBody()->getContents(), true);
            $status = 200;
        } catch (ClientException $th) {

            $data = implode(", ", json_decode($th->getResponse()->getBody()->getContents(), true)["errors"]);
            //$data = $th->getResponse()->getBody()->getContents();
            $status = $th->getCode();

            $this->log("get_signers", $data, $status);
        } finally {
            return compact("data", "status");
        }
    }

    public function create_doc_with_signer($text = "", $signer = [], $notify_signer = false, $document_key = "")
    {

        try {

            $signer_key = isset( $signer["key"] ) && $signer["key"] ? $this->get_signers( $signer["key"] ) : $this->create_signer($signer);

            if (!isset($signer_key["data"]["signer"]["key"])) {
                //$erros = is_array( $signer_key ) && isset( $signer_key["errors"]) ? implode("\n,", $signer_key["errors"] ) : implode($signer_key);
                $erros =  $signer_key["data"];

                throw new \Exception("#1 Erro ao criar o documento, verifique os dados do assinante. \nMais detalhes: \n" . $erros, 401);
            }

            if( $document_key === "" && $text !== null ){
                $document_key = $this->create_doc($text);
            }
            else if( $document_key !== "" ){
                $document_key = $this->get_documents($document_key);
            } else {
                $erros = "Não foi possível criar e/ou localizar o documento.";
                throw new \Exception("#2 Erro ao criar o documento. \nMais detalhes: \n" . $erros, 401);
            }

            if (!isset($document_key["data"]["document"]["key"])) {

                $erros =  $document_key["data"];
                throw new \Exception("#3 Erro ao criar o documento. \nMais detalhes: \n" . $erros, 401);
            }

            //$document_key = $this->get_documents("add79e11-7e5d-49f6-bec4-f3d8821affc0");

            $list = array(
                "document_key" => $document_key["data"]["document"]["key"],
                "signer_key" => $signer_key["data"]["signer"]["key"],
                "sign_as" => "sign"
            );

            // print_r( $list);
            // return;

            $response = $this->client->post("lists?access_token={$this->token}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => array("list" => $list)
            ]);

            $data = json_decode($response->getBody()->getContents(), true);


            if (isset($data['list']['request_signature_key']) && $document_key) { 
                //TODO adicionando signer automatic in document
               $this->add_signer_in_document_by_api($data['list']['request_signature_key']);
            }

            if ($notify_signer) {

                if (!isset($data['list']['request_signature_key'])) {
                    throw new \Exception("Não foi possível enviar este documento.", 401);
                }

                $notify = array(
                    "request_signature_key" => $data['list']['request_signature_key'],
                    "message" => "Prezado,\nPor favor assine o documento.\n\nQualquer dúvida estou à disposição.\n\nAtenciosamente,\nPIER1 - Cruise Experts",
                    "url" => str_replace("/api/", "/", $this->api_url) . "sign/{$data['list']['request_signature_key']}?embedded=true" // https://sandbox.clicksign.com/sign/89efebcb-d045-4975-b740-e64e60191a0d?embedded=true
                );

                $this->client->post("notifications?access_token={$this->token}", [
                    'headers' => ['Content-Type' => 'application/json'],
                    'json' => $notify
                ]);
            }

            //TODO testar retorno na linha debaixo apos validacao de email se esta OK
            //$data = $signer_automatic;


            $status = 200;
        } catch (\Exception $th) {

            $data = $th->getMessage();
            $status = $th->getCode();
        } finally {
            return compact("data", "status");
        }
    }

    public function _generate_hash($request_signature_key)
    {

        $secret = $this->secret_hmac_sha256;
        $secret_hmac_sha256 = hash_hmac('sha256', $request_signature_key,  $secret);
        return $secret_hmac_sha256;
    }

    public function add_signer_in_document_by_api($request_signature_key)
    {
        try {

            $secret_hmac_sha256 = $this->_generate_hash($request_signature_key);

            $json = array(
                "request_signature_key" => $request_signature_key,
                "secret_hmac_sha256" => $secret_hmac_sha256
            );

            $response = $this->client->post("sign?access_token={$this->token}", [
                'headers' => ['Content-Type' => 'application/json'],
                'json' => $json
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            $status = 200;
        } catch (\Exception $th) {

            $data = [$th->getMessage()];
            $status = $th->getCode();

            
            $secret_hmac_sha256 = $this->_generate_hash($request_signature_key);

            $data[] = array(
                "request_signature_key" => $request_signature_key,
                "secret_hmac_sha256" => $secret_hmac_sha256
            );


           // $this->log("add_signer_in_document_by_api", $data, $status);

        } finally {
            return compact("data", "status");
        }
    }

    public function create_signer_for_api($name, $email)
    {
        $signer = [
            "name" => $name,
            "email" => $email,
            "auths" => ["api"], //  assinatura via API.
            "has_documentation" => false,
            "delivery" => "none" // "email, ou none"  o signatário receberá as notificações de confirmação de assinatura e de documento finalizado.
        ];


        return $this->create_signer($signer);
    }

    private function _getFile($filename)
    {
        // PHP 5.5 introduced a CurlFile object that deprecates the old @filename syntax
        // See: https://wiki.php.net/rfc/curl-file-upload
        if (function_exists('file_get_contents')) {
            return file_get_contents($filename);
        }

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);

        $data = curl_exec($ch);
        curl_close($ch);

        return $data;
    }

    
    public function _get_signer_API(){ // Get signer key to signiture automatic MyCuise

        return [
            "name" => $this->signer_name_automatic,
            "email" => $this->signer_email_automatic,
            "key" => $this->signer_key_automatic,
            "auths" =>  ["api"],
            "has_documentation" => false
        ];

       
    }

    public function log($name, $data, $status)
    {
        if (self::IS_HOMOLOG) {
            $b = sprintf("<pre><code>cod: %s \n\r erro: %s </pre></code>", $status, json_encode($data));
            sendmail("marcelovieira1995@gmail.com", sprintf("Erro Docsign Mycruise - DocSign.class '%s'", $name), $b);
        };
    }

    public function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }
}
