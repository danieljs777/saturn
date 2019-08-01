<?phpclass PagSeguroHelper
{

    private $sandbox = true;

    private $user = "daniel.js@gmail.com";
    private $token = "895112DCE3B94B17B28A7F3290CFA397";

    public function __construct()
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

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

		\PagSeguro\Library::initialize();
		\PagSeguro\Library::cmsVersion()->setName("Lepabe")->setRelease("1.0.0");
		\PagSeguro\Library::moduleVersion()->setName("Lepabe")->setRelease("1.0.0");        

		if($this->sandbox)
			\PagSeguro\Configuration\Configure::setEnvironment('sandbox');
		else
			\PagSeguro\Configuration\Configure::setEnvironment('production');

		\PagSeguro\Configuration\Configure::setAccountCredentials(
		    $this->user,
		    $this->token
		);        
    }	

    public static function call_js_api()
    {
    	if($this->sandbox)
    		echo '<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>';
    	else
    		echo '<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>';
    }

    public function setSessionId()
    {
    	echo '<script type="text/javascript">PagSeguroDirectPayment.setSessionId("' . $_SESSION['pagseguro']['sessionid'] . '");</script>';

    }


    public function createSession()
    {

		try {
		    $_SESSION['pagseguro']['sessionid'] = \PagSeguro\Services\Session::create(
		        \PagSeguro\Configuration\Configure::getAccountCredentials()
		    );

		    echo "<strong>ID de sess&atilde;o criado: </strong>{$sessionCode->getResult()}";
		} catch (Exception $e) {
		    die($e->getMessage());
		}

    }

    public function getInstallments($options)
    {
    	/*
		$options = [
		    'amount' => 30.00, //Required
		    'card_brand' => 'visa', //Optional
		    'max_installment_no_interest' => 2 //Optional
		];
		*/

		try {
		    $result = \PagSeguro\Services\Installment::create(
		        \PagSeguro\Configuration\Configure::getAccountCredentials(),
		        $options
		    );

		    echo "<pre>";
		    print_r($result->getInstallments());
		} catch (Exception $e) {
		    die($e->getMessage());
		}

    }
}