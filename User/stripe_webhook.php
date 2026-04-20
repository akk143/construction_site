<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'stripe_config.php';
require_once '../DB/connection.php';

$payload = file_get_contents('php://input');
$sigHeader = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if (!stripe_verify_signature($payload, $sigHeader, STRIPE_WEBHOOK_SECRET)) {
    error_log('[Stripe Webhook] Invalid signature: ' . ($sigHeader ?: '[missing]') . ' payload_len=' . strlen($payload));
    http_response_code(400);
    echo json_encode(['error' => 'Invalid Stripe signature.']);
    exit;
}

$event = json_decode($payload, true);

if (!$event || !isset($event['type'])) {
    error_log('[Stripe Webhook] Invalid payload: ' . substr($payload, 0, 100));
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload.']);
    exit;
}

$type = $event['type'];
$dataObject = $event['data']['object'] ?? null;

if ($type === 'checkout.session.completed' && is_array($dataObject)) {

    $metadata = $dataObject['metadata'] ?? [];

    $propertyId = intval($metadata['property_id'] ?? 0);
    $clientId   = intval($metadata['client_id'] ?? 0);
    $depositMmk = intval($metadata['deposit_amount'] ?? 0);
    $amountPaidUsd = intval($dataObject['amount_total'] ?? 0) / 100;

    $logEntry = "[Stripe Webhook] Event: $type | Property: $propertyId | Client: $clientId | Amount: {$amountPaidUsd} USD | Deposit: {$depositMmk} MMK";
    error_log($logEntry);

    if ($propertyId > 0 && $clientId > 0 && $amountPaidUsd > 0) {
        $result = stripe_ensure_purchase_record($dbconid, $propertyId, $clientId, $depositMmk, $amountPaidUsd);
        if ($result['success']) {
            if (!empty($result['skipped'])) {
                error_log("[Stripe Webhook] SKIPPED: Existing purchase record for property $propertyId and client $clientId");
            } else {
                error_log("[Stripe Webhook] SUCCESS: Created purchase_property (ID: {$result['purchase_id']}) and payment (ID: {$result['payment_id']})");
            }
        } else {
            error_log('[Stripe Webhook] ERROR: ' . $result['message']);
        }
    } else {
        error_log("[Stripe Webhook] INVALID DATA: propertyId=$propertyId, clientId=$clientId, depositMmk=$depositMmk, amount=$amountPaidUsd");
    }
}

http_response_code(200);
echo json_encode(['received' => true]);