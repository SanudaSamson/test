<?php
class Form {
    private ?string $error = null;
    private ErrorCatalog $errorCatalog;

    public function __construct(ErrorCatalog $errorCatalog) {
        $this->errorCatalog = $errorCatalog;
    }

    // Return array of inputs that fail regex
    public function getRegexMismatchInputs(array $data, array $inputNames, string $pattern): array {
        $invalid = [];
        foreach ($inputNames as $inputName) {
            if (!preg_match($pattern, $data[$inputName])) {
                $invalid[] = $inputName;
                $this->setError('INVALID_INPUTS', $inputName);
            }
        }
        return $invalid;
    }

    public function isTooShort(string $value, int $length, string $inputName): bool {
        if (strlen($value) < $length) {
            $this->setError('TOO_SHORT', $inputName);
            return true;
        }
        return false;
    }

    public function isTooLong(string $value, int $length, string $inputName): bool {
        if (strlen($value) > $length) {
            $this->setError('TOO_LONG', $inputName);
            return true;
        }
        return false;
    }

    public function setError(string $code, ?string $inputName = null): void {
        // First error wins
        if ($this->error !== null) {
            return;
        }

        $message = $this->errorCatalog->getError($code);

        if ($inputName !== null) {
            $this->error = ucfirst($inputName) . " " . $message;
        } else {
            $this->error = $message;
        }
    }

    public function getError(): ?string {
        return $this->error;
    }
} 
?>
