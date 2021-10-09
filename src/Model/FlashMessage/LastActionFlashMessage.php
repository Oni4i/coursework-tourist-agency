<?php


namespace App\Model\FlashMessage;


class LastActionFlashMessage
{
    public function getSuccessData(string $action, string $target): array
    {
        return [
            'lastAction',
            [
                'status'    => FlashMessageInterface::SUCCESS_STATUS,
                'message'   => \sprintf('The %s %action', $target, $action),
            ]
        ];
    }

    public function getErrorData(string $action, string $target): array
    {
        return [
            'lastAction',
            [
                'status'    => FlashMessageInterface::ERROR_STATUS,
                'message'   => \sprintf('The %s %action', $target, $action),
            ]
        ];
    }
}