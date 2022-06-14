<?php
// card com
$config['card_name'] = 'barak9611';/* Username of cardcom */
$config['terminal_number'] = '1000';
$wsdl_url = 'https://secure.cardcom.co.il/service.asmx?wsdl';
$client = new \SoapClient($wsdl_url, array(
    'trace' => true,
    'exceptions' => true));

$params = array(
    'TerminalNumber' => $config['terminal_number'],
    'SumToBill' => $_POST['card_amount'],
    'CardValidityMonth' => $_POST['card_expiry_month'],
    'CardValidityYear' => $_POST['card_validity_year'],
    'CardNumber' => $_POST['card_number'],
    'CardOwnerId' => $_POST['card_owner_id'],
    'ExtendedParameters' => http_build_query(
        array(
                'UserName' => $config['card_name'],
                'invCreateInvoice' => true, /* Create Invoice ?*/
                'invItemDescription' => 'Frame description',/* Invoice Description*/
                'invCustName' => $_POST['card_username'],/* Invoice Name*/
                'invDestEmail' => $_POST['card_email'], /* Invoice Email*/
            )
    )
);
$return = $client->PerfromBillVerySimple($params);
if ($return->PerfromBillVerySimpleResult->ResposeCode == 0) {
    //Payment Success Code
    echo json_encode(array("success"=>true, "message"=>$return->PerfromBillVerySimpleResult->Description));
} else {
    echo json_encode(array("success"=>false, "message"=>$return->PerfromBillVerySimpleResult->Description));
}
