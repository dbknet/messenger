<?php

declare(strict_types=1);

namespace Kerox\Messenger\Api;

use Kerox\Messenger\Exception\InvalidStringException;
use Kerox\Messenger\Exception\InvalidTypeException;
use Kerox\Messenger\Exception\MessengerException;
use Kerox\Messenger\Request\CodeRequest;
use Kerox\Messenger\Response\CodeResponse;

class Code extends AbstractApi
{
    private const CODE_TYPE_STANDARD = 'standard';

    /**
     * @param int         $imageSize
     * @param string      $codeType
     * @param string|null $ref
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     *
     * @return \Kerox\Messenger\Response\CodeResponse
     */
    public function request(
        int $imageSize = 1000,
        string $codeType = self::CODE_TYPE_STANDARD,
        ?string $ref = null
    ): CodeResponse {
        $this->isValidCodeImageSize($imageSize);
        $this->isValidCodeType($codeType);

        if ($ref !== null) {
            $this->isValidRef($ref);
        }

        $request = new CodeRequest($this->pageToken, $imageSize, $codeType, $ref);
        $response = $this->client->post('me/messenger_codes', $request->build());

        return new CodeResponse($response);
    }

    /**
     * @param int $imageSize
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    private function isValidCodeImageSize(int $imageSize): void
    {
        if ($imageSize < 100 || $imageSize > 2000) {
            throw new MessengerException('imageSize must be between 100 and 2000.');
        }
    }

    /**
     * @param string $codeType
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    private function isValidCodeType(string $codeType): void
    {
        $allowedCodeType = $this->getAllowedCodeType();
        if (!\in_array($codeType, $allowedCodeType, true)) {
            throw new InvalidTypeException(sprintf(
                'codeType must be either "%s".',
                implode(', ', $allowedCodeType)
            ));
        }
    }

    /**
     * @param string $ref
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    private function isValidRef(string $ref): void
    {
        if (!preg_match('/^[a-zA-Z0-9\+\/=\-.:_ ]{1,250}$/', $ref)) {
            throw new InvalidStringException(
                'ref must be a string of max 250 characters. Valid characters are "a-z A-Z 0-9 +/=-.:_".'
            );
        }
    }

    /**
     * @return array
     */
    private function getAllowedCodeType(): array
    {
        return [
            self::CODE_TYPE_STANDARD,
        ];
    }
}
