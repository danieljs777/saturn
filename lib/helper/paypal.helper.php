<?php

use PayPal\Auth\OAuthTokenCredential;
use PayPal\Rest\ApiContext;

use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\RedirectUrls;

class PayPalHelper
{

    private $sandbox = true;

    private $user = "daniel.js@gmail.com";
    private $pswd = "";
    private $signature = "";

    private $clientId = '';
    private $clientSecret = ''; 

    private $apiContext;   

    public function __construct()
    {
        $curl = curl_init();

        // Include the composer Autoloader
        // The location of your project's vendor autoloader.
        $composerAutoload = PATH_ROOT . 'vendor/autoload.php';
        if (!file_exists($composerAutoload)) {
            //If the project is used as its own project, it would use rest-api-sdk-php composer autoloader.
            $composerAutoload = dirname(__DIR__) . '/vendor/autoload.php';


            if (!file_exists($composerAutoload)) {
                echo "The 'vendor' folder is missing. You must run 'composer update' to resolve application dependencies.\nPlease see the README for more information.\n";
                exit(1);
            }
        }
        require $composerAutoload;

        // Suppress DateTime warnings, if not set already
        date_default_timezone_set(@date_default_timezone_get());

        // Adding Error Reporting for understanding errors properly
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        // Replace these values by entering your own ClientId and Secret by visiting https://developer.paypal.com/webapps/developer/applications/myapps


        /**
         * All default curl options are stored in the array inside the PayPalHttpConfig class. To make changes to those settings
         * for your specific environments, feel free to add them using the code shown below
         * Uncomment below line to override any default curl options.
         */
        //PayPalHttpConfig::$defaultCurlOptions[CURLOPT_SSLVERSION] = CURL_SSLVERSION_TLSv1_2;


        /** @var \Paypal\Rest\ApiContext $apiContext */
        $this->apiContext = $this->getApiContext($this->clientId, $this->clientSecret);

        //return $apiContext;

    }

    /**
     * Helper method for getting an APIContext for all calls
     * @param string $clientId Client ID
     * @param string $clientSecret Client Secret
     * @return PayPal\Rest\ApiContext
     */

    private function getApiContext($clientId, $clientSecret)
    {

        // #### SDK configuration
        // Register the sdk_config.ini file in current directory
        // as the configuration source.
        /*
        if(!defined("PP_CONFIG_PATH")) {
            define("PP_CONFIG_PATH", __DIR__);
        }
        */


        // ### Api context
        // Use an ApiContext object to authenticate
        // API calls. The clientId and clientSecret for the
        // OAuthTokenCredential class can be retrieved from
        // developer.paypal.com

        $apiContext = new ApiContext(
            new OAuthTokenCredential(
                $clientId,
                $clientSecret
            )
        );

        // Comment this line out and uncomment the PP_CONFIG_PATH
        // 'define' block if you want to use static file
        // based configuration

        $apiContext->setConfig(
            array(
                'mode' => 'sandbox',
                'log.LogEnabled' => true,
                'log.FileName' => PATH_ROOT . 'vendor/paypal/rest-api-sdk-php/PayPal.log',
                'log.LogLevel' => 'DEBUG', // PLEASE USE `INFO` LEVEL FOR LOGGING IN LIVE ENVIRONMENTS
                'cache.enabled' => true,
                // 'http.CURLOPT_CONNECTTIMEOUT' => 30
                // 'http.headers.PayPal-Partner-Attribution-Id' => '123123123'
                //'log.AdapterFactory' => '\PayPal\Log\DefaultLogFactory' // Factory class implementing \PayPal\Log\PayPalLogFactory
            )
        );

        // Partner Attribution Id
        // Use this header if you are a PayPal partner. Specify a unique BN Code to receive revenue attribution.
        // To learn more or to request a BN Code, contact your Partner Manager or visit the PayPal Partner Portal
        // $apiContext->addRequestHeader('PayPal-Partner-Attribution-Id', '123123123');

        return $apiContext;
    }        

    public function create_payment_curl($cart, $items, $client)
    {
        $_SESSION['paypal']['payment_json'] = $this->json_payment($cart, $items, $client);

        // $jsonfileCP = '/media/personal/daniel/PROFESSIONAL/freakmarket.com.br/www/lib/helper/create_payment.json';
        // $_SESSION['paypal_json'] = file_get_contents($jsonfileCP);
     
        $access_obj = $this->make_bearer_token();
        
        if(!isset($access_obj->access_token))
        {
           var_dump($access_obj); 
           die();
        }

        $_SESSION['paypal']['payment_token'] = $access_obj->access_token;
        
        $curlCP = curl_init();

        $headersCP = array(
            "Content-Type:application/json",
            "Authorization: Bearer ". $_SESSION['paypal']['payment_token']
                );

        if($this->sandbox)
            $apiEndpoint  = 'https://api.sandbox.paypal.com/v1/payments/payment';
        else
            $apiEndpoint  = 'https://api.paypal.com/v1/payments/payment';        

        // echo "<br>" . $apiEndpoint;

        // var_dump($_SESSION['paypal']['payment_json']);
        // die();

        curl_setopt( $curlCP , CURLOPT_URL , $apiEndpoint );
        curl_setopt( $curlCP , CURLOPT_SSL_VERIFYPEER , false );
        curl_setopt( $curlCP , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt( $curlCP , CURLOPT_HTTPHEADER , $headersCP );
        curl_setopt( $curlCP , CURLOPT_POST , 1 );
        curl_setopt( $curlCP , CURLOPT_POSTFIELDS , $_SESSION['paypal']['payment_json'] );
        curl_setopt( $curlCP , CURLOPT_VERBOSE, true);

        $responseCP = urldecode( curl_exec( $curlCP ) );

        // var_dump((array)json_decode($responseCP));

        $json_arrayCP = (array)json_decode($responseCP);
        
        if(isset($json_arrayCP['id']))
            $_SESSION['paypal']['payment_id'] = $json_arrayCP['id'];
        
        if(isset($json_arrayCP['links']))
            $_SESSION['paypal']['payment_approval_url'] = $json_arrayCP['links'][1]->href;

        if(isset($json_arrayCP['details'][0]->issue));
            Log::verbose("Erro ao processar pagamento : " . $responseCP . "================" . print_r($_SESSION['paypal'], true), "paypal.log");
       
        curl_close($curlCP);    

        // print $json_arrayEP->id;

        return (Array) $json_arrayCP;        
    }

    public function execute_payment_curl($paymentId, $payer_id)
    {

        $curlCP = curl_init();

        $headersCP = array(
            "Content-Type:application/json",
            "Authorization: Bearer ". $_SESSION["paypal"]["payment_token"]
                );

        if($this->sandbox)
            $apiEndpoint  = 'https://api.sandbox.paypal.com/v1/payments/payment/' . $paymentId . '/execute/';
        else
            $apiEndpoint  = 'https://api.paypal.com/v1/payments/payment/' . $paymentId . '/execute/';

        // var_dump(json_encode(array( "payer_id" => $payer_id)));

        curl_setopt( $curlCP , CURLOPT_URL , $apiEndpoint );
        curl_setopt( $curlCP , CURLOPT_SSL_VERIFYPEER , false );
        curl_setopt( $curlCP , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt( $curlCP , CURLOPT_HTTPHEADER , $headersCP );
        curl_setopt( $curlCP , CURLOPT_POST , 1 );
        curl_setopt( $curlCP , CURLOPT_POSTFIELDS , json_encode(array( "payer_id" => $payer_id)) );
        curl_setopt( $curlCP , CURLOPT_VERBOSE, true);

        $responseCP = urldecode( curl_exec( $curlCP ) );

        // var_dump($responseCP);

        $json_arrayCP = (array)json_decode($responseCP);

        // var_dump($json_arrayCP);

        if(isset($json_arrayCP['id']))
            $_SESSION['paypal']['transaction_id'] = $json_arrayCP['id'];
               
        curl_close($curlCP);    

        return $json_arrayCP;

    }        

    /**
     * Verifica se uma notificação IPN é válida, fazendo a autenticação
     * da mensagem segundo o protocolo de segurança do serviço.
     * 
     * @param array $message Um array contendo a notificação recebida.
     * @return boolean TRUE se a notificação for autência, ou FALSE se
     *                 não for.
     */
    public function isIPNValid(array $message)
    {
        $endpoint = 'https://www.paypal.com';
     
        if (isset($message['test_ipn']) && $message['test_ipn'] == '1') {
            $endpoint = 'https://www.sandbox.paypal.com';
        }
     
        $endpoint .= '/cgi-bin/webscr?cmd=_notify-validate';
     
        $curl = curl_init();
     
        curl_setopt($curl, CURLOPT_URL, $endpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($message));
      
        $response = curl_exec($curl);
        $error = curl_error($curl);
        $errno = curl_errno($curl);
     
        curl_close($curl);
      
        return empty($error) && $errno == 0 && $response == 'VERIFIED';
    }


    public function get_payment_curl($paymentId)
    {

        $curlCP = curl_init();

        $headersCP = array(
            "Content-Type:application/json",
            "Authorization: Bearer ". $_SESSION["paypal"]["payment_token"]
                );

        if($this->sandbox)
            $apiEndpoint  = 'https://api.sandbox.paypal.com/v1/payments/payment/' . $paymentId;
        else
            $apiEndpoint  = 'https://api.paypal.com/v1/payments/payment/' . $paymentId;

        curl_setopt( $curlCP , CURLOPT_URL , $apiEndpoint );
        curl_setopt( $curlCP , CURLOPT_SSL_VERIFYPEER , false );
        curl_setopt( $curlCP , CURLOPT_RETURNTRANSFER , 1 );
        curl_setopt( $curlCP , CURLOPT_HTTPHEADER , $headersCP );
        // curl_setopt( $curlCP , CURLOPT_POST , 1 );
        // curl_setopt( $curlCP , CURLOPT_POSTFIELDS , json_encode(array( "payer_id" => $payer_id)) );
        curl_setopt( $curlCP , CURLOPT_VERBOSE, true);

        $responseCP = urldecode( curl_exec( $curlCP ) );

        $json_arrayCP = (array)json_decode($responseCP);
               
        curl_close($curlCP);    

        return $json_arrayCP;

    }            

    public function make_bearer_token()
    {

        if($this->sandbox)
            $apiEndpoint  = 'https://api.sandbox.paypal.com/v1/oauth2/token';
        else
            $apiEndpoint  = 'https://api.paypal.com/v1/oauth2/token';

        // echo "<br>" . $apiEndpoint;
     
        //Executando a operação
        $curl = curl_init();
     
        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query(array("grant_type" => "client_credentials")));
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            'Accept: application/json',
            'Accept-Language: en_US'
            ));        

        curl_setopt($curl, CURLOPT_USERPWD, $this->clientId . ":" . $this->clientSecret);
     
        $response = urldecode(curl_exec($curl));
     
        curl_close($curl);

        return json_decode($response);

    }

    public function send_payments()
    {

        $requestNvp = array(
            'USER' => $this->user,
            'PWD' => $this->pswd,
            'SIGNATURE' => $this->signature,

            'METHOD' => 'MassPay',
            'VERSION' => '108',

            'CURRENCYCODE' => 'BRL',
            'RECEIVERTYPE' => 'EmailAddress',
            'EMAILSUBJECT' => 'Assunto do email que o cliente receberá',

            'L_EMAIL0' => 'fulano@cliente.com',
            'L_AMT0' => 100.00,

            'L_EMAIL1' => 'beltrano@cliente.com',
            'L_AMT1' => 200.00,

            'L_EMAIL2' => 'cicrano@cliente.com',
            'L_AMT2' => 300.00
        );

        $responseNvp = $this->sendNvpRequest($requestNvp);


    }

    public function refund($amount, $transaction_id)    
    {
        $requestNvp = array(
            'USER' => $this->user,
            'PWD' => $this->pswd,
            'SIGNATURE' => $this->signature,
         
            'VERSION' => '108',
            'METHOD'=> 'RefundTransaction',
         
            'TRANSACTIONID' => $transaction_id,
            'REFUNDTYPE' => 'Partial',
            'AMT' => str_replace(",", ".", str_replace(".", "", $amount)),
            'CURRENCYCODE' => 'BRL'
        );

        //Envia a requisição e obtém a resposta da PayPal
        $responseNvp = $this->sendNvpRequest($requestNvp);
         
        //Verifica se a operação foi bem sucedida
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] == 'Success')
            return $responseNvp['REFUNDTRANSACTIONID'];

    }



    public function create_payment($data)
    {
        if(strtolower($data['payment']['method']) == "crédito")
        {

            $card = new CreditCard();
            $card->setType(strtolower($data['payment']['sctp']))
                ->setNumber($data['payment']['schn'])
                ->setExpireMonth($data['payment']['schnme'])
                ->setExpireYear($data['payment']['schnye'])
                ->setCvv2($data['payment']['schnc'])
                ->setFirstName($data['payment']['schfn'])
                ->setLastName($data['payment']['schln']);

            $fi = new FundingInstrument();
            $fi->setCreditCard($card);

            $payer = new Payer();
            $payer->setPaymentMethod("credit_card")
                ->setFundingInstruments(array($fi));

        }
        else
            return false;

        $items = [];

        foreach ($data['cart']['items'] as $key => $item)
        {

            $_item = new Item();
            $_item->setName($item['prod_name'])
                // ->setDescription($item['prod_desc'])
                ->setCurrency('USD')
                ->setQuantity($item['quantity'])
                ->setTax(0.0)
                ->setPrice(number_format($item['total_value'], 2, '.', ''));

            $items[] = $_item;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping($data['cart']['freight_value'])
            ->setTax(0)
            ->setSubtotal($data['cart']['items_value']);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("USD")
            ->setTotal($data['cart']['total_value'])
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Pedido #" . $_SESSION['shop_cart']['ord_id'])
            ->setInvoiceNumber(uniqid());

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        // For Sample Purposes Only.
        $request = clone $payment;

        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            $payment->create($this->apiContext);
        } catch (Exception $ex) {
            var_dump($ex);

            exit(1);
        }

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        // ResultPrinter::printResult('Create Payment Using Credit Card', 'Payment', $payment->getId(), $request, $payment);

        return $payment;

    }

    public function create_payment_account($cart, $items)
    {

        $payer = new Payer();
        $payer->setPaymentMethod("paypal");

        $items = [];

        foreach ($items as $key => $item)
        {

            $_item = new Item();
            $_item->setName($item['prod_name'])
                // ->setDescription($item['prod_desc'])
                ->setCurrency('BRL')
                ->setQuantity($item['quantity'])
                ->setTax(0.0)
                ->setPrice(number_format($item['total_value'], 2, '.', ''));

            $items[] = $_item;
        }

        $itemList = new ItemList();
        $itemList->setItems($items);

        $details = new Details();
        $details->setShipping($cart['freight_value'])
            ->setTax(0)
            ->setSubtotal($cart['items_value']);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency("BRL")
            ->setTotal($cart['total_value'])
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription("Pedido #" . $_SESSION['shop_cart']['ord_id'])
            ->setPaymentOptions("IMMEDIATE_PAY")
            ->setInvoiceNumber(uniqid());


        $redirectUrls = new RedirectUrls();
        $redirectUrls->setReturnUrl(WEB_ROOT . "vendor/paypal/rest-api-sdk-php/sample/payments/ExecutePayment.php?success=true")
            ->setCancelUrl(WEB_ROOT . "vendor/paypal/rest-api-sdk-php/sample/payments/ExecutePayment.php?success=false");

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'
        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setRedirectUrls($redirectUrls)            
            ->setTransactions(array($transaction));

        // For Sample Purposes Only.
        $request = clone $payment;

        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            $payment->create($this->apiContext);
            var_dump($payment);
            var_dump($this->apiContext);

        } catch (Exception $ex) {
            var_dump($ex);

            exit(1);
        }

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        // ResultPrinter::printResult('Create Payment Using Credit Card', 'Payment', $payment->getId(), $request, $payment);

        return $payment;

    }    

    public function execute_payment($paymentId, $payer_id, $cart)
    {
        // Get the payment Object by passing paymentId
        // payment id was previously stored in session in
        // CreatePaymentUsingPayPal.php
        $payment = Payment::get($paymentId, $this->apiContext);

        // ### Payment Execute
        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId($payer_id);

        // ### Optional Changes to Amount
        // If you wish to update the amount that you wish to charge the customer,
        // based on the shipping address or any other reason, you could
        // do that by passing the transaction object with just `amount` field in it.
        // Here is the example on how we changed the shipping to $1 more than before.
        $transaction = new Transaction();
        $amount = new Amount();
        $details = new Details();

        $details->setShipping($cart['freight_value'])
            ->setTax(0)
            ->setSubtotal($cart['items_value']);

        $amount->setCurrency("BRL")
            ->setTotal($cart['total_value'])
            ->setDetails($details);

        $transaction->setAmount($amount);

        // Add the above transaction object inside our Execution object.
        $execution->addTransaction($transaction);

        try 
        {
            // Execute the payment
            // (See bootstrap.php for more on `ApiContext`)
            $result = $payment->execute($execution, $this->apiContext);

            try
            {
                $payment = Payment::get($paymentId, $this->apiContext);
            }
            catch (Exception $ex)
            {
                var_dump($ex);
                exit(1);
            }
        } 
        catch (Exception $ex) 
        {
            var_dump($ex);
            exit(1);
        }

        // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
        // ResultPrinter::printResult("Get Payment", "Payment", $payment->getId(), null, $payment);

        return $payment;

    }

    /**
     * Envia uma requisição NVP para uma API PayPal.
     *
     * @param array $requestNvp Define os campos da requisição.
     * @param boolean $sandbox Define se a requisição será feita no sandbox ou no
     *                         ambiente de produção.
     *
     * @return array Campos retornados pela operação da API. O array de retorno poderá
     *               pode ser vazio, caso a operação não seja bem sucedida. Nesse caso,
     *               os logs de erro deverão ser verificados.
     */
    private function sendNvpRequest(array $requestNvp)
    {
        //Endpoint da API
        $apiEndpoint  = 'https://api-3t.' . ($this->sandbox? 'sandbox.': null);
        $apiEndpoint .= 'paypal.com/nvp';
     
        //Executando a operação
        $curl = curl_init();
     
        curl_setopt($curl, CURLOPT_URL, $apiEndpoint);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($requestNvp));
     
        $response = urldecode(curl_exec($curl));
     
        curl_close($curl);
     
        //Tratando a resposta
        $responseNvp = array();
     
        if (preg_match_all('/(?<name>[^\=]+)\=(?<value>[^&]+)&?/', $response, $matches)) {
            foreach ($matches['name'] as $offset => $name) {
                $responseNvp[$name] = $matches['value'][$offset];
            }
        }
        
        //Verificando se deu tudo certo e, caso algum erro tenha ocorrido,
        //gravamos um log para depuração.
        if (isset($responseNvp['ACK']) && $responseNvp['ACK'] != 'Success') {
            if ($sandbox)   {
                echo "<html><body><h1>Houve um erro</h1><pre>" . print_r($responseNvp, true) . "</pre></body></html>";
            }
            
            for ($i = 0; isset($responseNvp['L_ERRORCODE' . $i]); ++$i) {
                $message = sprintf("PayPal NVP %s[%d]: %s\n",
                                   $responseNvp['L_SEVERITYCODE' . $i],
                                   $responseNvp['L_ERRORCODE' . $i],
                                   $responseNvp['L_LONGMESSAGE' . $i]);

                error_log($message);
            }
        }
        return $responseNvp;
    }        

    private function json_payment($cart, $items, $client)
    {
        $json = '{
                "intent":"sale",
                "payer":{ 
                      "payment_method":"paypal"
                   },
                   "transactions":[ 
                      { 
                         "amount":{ 
                            "currency":"BRL",
                            "total":"' . number_format($cart['total_value'], 2, '.', '') . '",
                            "details":{ 
                              "shipping":"' . number_format($cart['freight_value'], 2, '.', '') . '",
                              "subtotal":"' . number_format($cart['items_value'], 2, '.', '') . '",
                              "insurance":"0.00",
                              "handling_fee":"0.00",
                              "tax":"0.00"
                            }
                         },
                         "description":"' . "Pedido #" . $_SESSION['shop_cart']['ord_id'] . '",
                         "payment_options":{ 
                            "allowed_payment_method":"IMMEDIATE_PAY"
                         },
                         "item_list":{ 
                            "shipping_address": {
                                "recipient_name" : "' . $client['cli_name'] . '",
                                "line1": "' . $cart['address_log'] . ', ' . $cart['address_number'] . ', ' . $cart['address_comp'] .  '",
                                "line2": "' . $cart['address_nboor'] . '",
                                "city": "' . $cart['address_city'] . '",
                                "country_code": "BR",
                                "postal_code": "' . $cart['address_zip'] .'",
                                "state": "' . $cart['address_uf'] .'",
                                "phone": "' . $client['cli_mobile'] . '"
                            },
                            "items":[ ';

        $x=0;
        foreach ($items as $key => $item)
        {
            if($x > 0)
                $json .= ",";

            $json .= '         { 
                                  "name":"' . $item['p_name'] . '",
                                  "description":"",
                                  "quantity":"' . $item['quantity'] . '",
                                  "price":"' . number_format($item['total_value'], 2, '.', '') . '",
                                  "tax":"0",
                                  "sku":"0",
                                  "currency":"BRL"
                               }';

            $x++;

        }

        $json .= '                               
                            ]
                         }
                      }
                   ],
                   "redirect_urls":{ 
                      "return_url":"' . WEB_ROOT . 'vendor/paypal/rest-api-sdk-php/sample/payments/ExecutePayment.php?success=true",
                      "cancel_url":"' . WEB_ROOT . 'vendor/paypal/rest-api-sdk-php/sample/payments/ExecutePayment.php?success=false"
                   }
                }
        ';        

        return $json;
    }


}



