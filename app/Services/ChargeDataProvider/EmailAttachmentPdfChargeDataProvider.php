<?php

namespace App\Services\ChargeDataProvider;

use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Process\Process;

class EmailAttachmentPdfChargeDataProvider implements ChargeDataProvider
{
    const TYPE = "email_attachment_pdf";

    public function __construct(
        private int $index,
        private int $page,
        private int $amountPosX,
        private int $amountPosY,
        private int $amountWidth,
        private int $amountHeight,
        private int $chargedAtPosX,
        private int $chargedAtPosY,
        private int $chargedAtWidth,
        private int $chargedAtHeight,
        private string $dateFormat,
    )
    {
    }

    function getData(?object $context = null): ChargeData
    {
        // Save attachment file
        $inputPath = $this->saveAttachmentFile($context->attachments);

        // Analyze PDF attachment with pdf2json tool
        $pdfContent = $this->analyzePDFAttachment($inputPath);

        // Read amount and chargedAt from the PDF content
        $amount = $this->readAmount($pdfContent);
        $chargedAt = $this->readChargedAt($pdfContent);

        return new ChargeData($amount, $chargedAt);
    }

    private function saveAttachmentFile(object $attachments): string
    {
        $attachment = $attachments[$this->index];
        Storage::put($attachment->name, $attachment->data);
        return Storage::path($attachment->name);
    }

    /**
     * Analyze the PDF and return the JSON output
     * @param string $filePath
     * @return array
     */
    protected function analyzePDFAttachment(string $filePath): array
    {
        $outputFilename = 'output.json';
        $outputPath = Storage::path($outputFilename);

        $process = new Process(['pdf2json', '-enc', 'UTF-8', '-f', $this->page + 1, '-l', $this->page + 1, $filePath, $outputPath]);
        $process->run();

        $output = json_decode(Storage::get($outputFilename));
        Storage::delete($outputFilename);

        return $output[0]->text;
    }

    /**
     * @param array $pdfContent
     * @return float
     * @throws UnableToGetChargeDataException
     */
    protected function readAmount(array $pdfContent): float
    {
        $amount = $this->findElementsInBox($pdfContent, $this->amountPosX, $this->amountPosY, $this->amountWidth, $this->amountHeight);
        return $this->parseAmount($amount);
    }

    /**
     * @param array $pdfContent
     * @return Carbon
     * @throws UnableToGetChargeDataException
     */
    protected function readChargedAt(array $pdfContent): Carbon
    {
        $chargedAt = $this->findElementsInBox($pdfContent, $this->chargedAtPosX, $this->chargedAtPosY, $this->chargedAtWidth, $this->chargedAtHeight);
        return $this->parseChargedAt($chargedAt);
    }

    private function parseAmount($data): float
    {
        return floatval(str_replace(',', '.', $data));
    }

    private function parseChargedAt(string $chargedAt): Carbon
    {
        return Carbon::createFromFormat($this->dateFormat, $chargedAt);
    }

    /**
     * @param array $elements
     * @param int $left
     * @param int $top
     * @param int $width
     * @param int $height
     * @return string
     * @throws UnableToGetChargeDataException
     */
    private function findElementsInBox(array $elements, int $left, int $top, int $width, int $height): string
    {
        $found = [];
        foreach ($elements as $element) {
            if (
                $element->left >= $left
                && ($element->left + $element->width) <= ($left + $width)
                && $element->top >= $top
                && ($element->top + $element->height <= ($top + $height))
            ) {
                $found[] = $element;
                break;
            }
        }

        if (empty($found)) {
            throw new UnableToGetChargeDataException();
        }

        return $found[0]->data;
    }
}
