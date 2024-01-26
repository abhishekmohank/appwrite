<?php

namespace Appwrite\Network\Validator;

use Utopia\Validator;

class CNAME extends Validator
{
    /**
     * @var string
     */
    protected $target;

    /**
     * CNAME constructor.
     *
     * @param string $target
     */
    public function __construct(string $target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Invalid CNAME record';
    }

    /**
     * Check if CNAME record target value matches selected target
     *
     * @param mixed $domain
     *
     * @return bool
     */
    public function isValid($domain): bool
    {
        if (!is_string($domain)) {
            return false;
        }

        try {
            $records = \dns_get_record($domain, DNS_CNAME);
        } catch (\Throwable $th) {
            return false;
        }

        return $this->hasMatchingTarget($records);
    }

    /**
     * Check if records have a matching target
     *
     * @param array|false $records
     *
     * @return bool
     */
    private function hasMatchingTarget($records): bool
    {
        return $records && count(
            array_filter(
                $records,
                static function ($record) {
                    return isset($record['target']) && $record['target'] === $this->target;
                }
            )
        ) > 0;
    }

    /**
     * Is array
     *
     * Function will return true if object is array.
     *
     * @return bool
     */
    public function isArray(): bool
    {
        return false;
    }

    /**
     * Get Type
     *
     * Returns validator type.
     *
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE_STRING;
    }
}
