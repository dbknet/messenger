<?php

declare(strict_types=1);

namespace Kerox\Messenger\Model\Message\Attachment\Template;

use Kerox\Messenger\Exception\InvalidClassException;
use Kerox\Messenger\Model\Message\Attachment\Template;
use Kerox\Messenger\Model\Message\Attachment\Template\Element\MediaElement;

class MediaTemplate extends Template
{
    /**
     * @var \Kerox\Messenger\Model\Message\Attachment\Template\Element\MediaElement[]
     */
    protected $elements;

    /**
     * MediaTemplate constructor.
     *
     * @param array $elements
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    public function __construct(array $elements)
    {
        parent::__construct();

        $this->isValidElements($elements);

        $this->elements = $elements;
    }

    /**
     * @param array $elements
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     *
     * @return \Kerox\Messenger\Model\Message\Attachment\Template\MediaTemplate
     */
    public static function create(array $elements): self
    {
        return new self($elements);
    }

    /**
     * @param array $elements
     *
     * @throws \Kerox\Messenger\Exception\MessengerException
     */
    private function isValidElements(array $elements): void
    {
        $this->isValidArray($elements, 1, 1);
        foreach ($elements as $element) {
            if (!$element instanceof MediaElement) {
                throw new InvalidClassException(sprintf('Array can only contain instance of %s.', MediaElement::class));
            }
        }
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $array = parent::toArray();
        $array += [
            'payload' => [
                'template_type' => Template::TYPE_MEDIA,
                'elements' => $this->elements,
            ],
        ];

        return $this->arrayFilter($array);
    }
}
