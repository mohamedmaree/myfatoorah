# myfatoorah
## Installation

You can install the package via [Composer](https://getcomposer.org).

```bash
composer require maree/myfatoorah
```
Publish your myfatoorah config file with

```bash
php artisan vendor:publish --provider="maree\myfatoorah\MyfatoorahServiceProvider" --tag="myfatoorah"
```
then change your myfatoorah config from config/myfatoorah.php file
```php
    "token"  => "" ,//To generate one follow instruction here https://myfatoorah.readme.io/docs/live-token.
    "mode"   => "test", //test|live
```
## Usage

creat routes for checkout,callback and error pages
```php
Route::get('checkout', [\App\Http\Controllers\MyFatoorahController::class, 'checkout']);
Route::get('payment/callback', [\App\Http\Controllers\MyFatoorahController::class, 'callback']);
Route::get('payment/error', [\App\Http\Controllers\MyFatoorahController::class, 'error']);

```
##At the controller
```php
use maree\myfatoorah\PaymentMyfatoorah;
    public function  checkout(){
        $pay      = new PaymentMyfatoorah();
        $postFields = [
            'NotificationOption' => 'Lnk',     //'SMS', 'EML', or 'ALL'
            'InvoiceValue'       => $priceDouble,   //the price the customer will pay
            'CustomerName'       => auth()->user()->name, 
            'DisplayCurrencyIso' => 'SAR',
            'MobileCountryCode'  => '+966',
            'CustomerMobile'     => ltrim(auth()->user()->phone,'0'),
            'CallBackUrl'        => route('callback'),    //the route that will be redirected to in the success
            'ErrorUrl'           => route('error'), //the route that will be redirected to in the fail
            'Language'           => 'ar',
            'CustomerReference'  => auth()->id(),    // the refrence to the customer and wil be returned in the respone of the success
            'UserDefinedField'   => $type,//user,product,...  //(optional) extra key and wil be returned in the respone of the succes
        ];
        $data = $pay->getInvoiceURL($postFields);
        return redirect($data['invoiceURL']);
    }

```
##Get callback to check payment status

```php
use maree\myfatoorah\PaymentMyfatoorah;
   
    public function callback(){
        $pay          = new PaymentMyfatoorah();
        $responseData = $pay->getPaymentStatus(request('paymentId'), 'PaymentId');
        $responseDataArr = json_decode(json_encode($responseData), true);
        if ($responseDataArr['focusTransaction']['TransactionStatus'] == 'Succss') { //check if the transaction is the success
            $user_id = $responseDataArr['CustomerReference'];    //get the cutomer refrence
            $user = User::findOrFail($user_id);
            if ($responseDataArr['UserDefinedField'] == 'user') { //get the extra key
                // code
            } elseif ($responseDataArr['UserDefinedField'] == 'product') {
                //code
            }
            return response()->json(['status' => 'success','msg' => 'payment success']);
        }else{
   	    	return response()->json(['status' => 'fail','msg' => 'payment fail']);
        }
    }


```

##Error page

```php
use maree\myfatoorah\PaymentMyfatoorah;
   
    public function error(Request $request) {
   	    return response()->json(['status' => 'fail','msg' => 'payment fail']);
    }

```

You can get test cards from https://myfatoorah.readme.io/docs/test-cards









