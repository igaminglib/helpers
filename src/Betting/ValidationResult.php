<?php

declare(strict_types=1);

namespace IGamingLib\Betting;

/**
 * Validation Result
 */
class ValidationResult
{
    private function __construct(
        private readonly bool $valid,
        private readonly ?string $message = null
    ) {}

    /**
     * Creates valid result
     */
    public static function valid(): self
    {
        return new self(true);
    }

    /**
     * Creates invalid result
     */
    public static function invalid(string $message): self
    {
        return new self(false, $message);
    }

    /**
     * Checks if valid
     */
    public function isValid(): bool
    {
        return $this->valid;
    }

    /**
     * Checks if invalid
     */
    public function isInvalid(): bool
    {
        return !$this->valid;
    }

    /**
     * Returns error message (if any)
     */
    public function getMessage(): ?string
    {
        return $this->message;
    }

    /**
     * Throws exception if invalid
     * 
     * @throws \InvalidArgumentException
     */
    public function throwIfInvalid(): void
    {
        if ($this->isInvalid()) {
            throw new \InvalidArgumentException($this->message ?? 'Validation failed');
        }
    }
}
