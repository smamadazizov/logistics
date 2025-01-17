<?php

namespace App\Services\Till\Payment;

use App\Data\Dto\Till\PaymentDto;
use App\Models\Till\Payment;
use App\Services\Till\Payment\PaymentRollback\PaymentRollbackServiceFabric;

class PaymentService
{
    public function store(PaymentDto $dto) : Payment
    {
        /** @var Payment $payment */
        $payment = Payment::create($dto->toArray());
        $payment->fillExtras();
        $payment->save();
        return $payment;
    }

    public function destroy(Payment $payment)
    {
        $rollbackServiceFabric = new PaymentRollbackServiceFabric($payment);
        $rollbackService = $rollbackServiceFabric->getWriter();
        $rollbackService->rollback();
        return;
    }
}
