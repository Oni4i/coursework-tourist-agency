<?php

namespace App\Model\FlashMessage;

/**
 * Class for showing flash messages
 */
class LastActionFlashMessage
{
    const ACTION_REMOVE = 'removed';
    const ACTION_CREATE = 'created';
    const ACTION_UPDATE = 'updated';

    const ACTIONS = [
        self::ACTION_REMOVE,
        self::ACTION_CREATE,
        self::ACTION_UPDATE,
    ];

    /**
     * Showing success message
     *
     * @param string $action
     * @param string $target
     *
     * @return array
     */
    public function getSuccessData(string $action, string $target): array
    {
        return [
            'lastAction',
            [
                'status'    => FlashMessageInterface::SUCCESS_STATUS,
                'message'   => \sprintf('The %s %s', $target, $action),
            ]
        ];
    }

    /**
     * Showing error message
     *
     * @param string $action
     * @param string $target
     *
     * @return array
     */
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