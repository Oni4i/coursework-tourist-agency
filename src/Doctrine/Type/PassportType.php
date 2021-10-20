<?php

namespace App\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use App\Entity\Customer\Passport;

class PassportType extends JsonType
{
    const TYPE = 'passport';

    /**
     * @inheritDoc
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $passport = new Passport();
        $passport->jsonDeserialize(parent::convertToPHPValue($value, $platform) ?? []);

        return $passport;
    }

    /**
     * @inheritDoc
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    /**
     * @inheritDoc
     */
    public function getName()
    {
        return self::TYPE;
    }

}