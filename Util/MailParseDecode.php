<?php

namespace Ijanki\Bundle\MailMimeDecodeBundle\Util;

use Ijanki\Bundle\MailMimeDecodeBundle\MailDecoderInterface;
use Ijanki\Bundle\MailMimeDecodeBundle\Exception\BadUsageException;

/**
 * This class is used to decode mail/mime messages
 *
 * This class requires PECL extension mailparse
 */

class MailParseDecode implements MailDecoderInterface
{
    private $mimemail;
    private $input;
    private $parts;
    private $headers;

    public function __construct()
    {
        $this->mimemail = mailparse_msg_create();
        $this->parts = array();
        $this->headers = array();
    }

    public function parse($input)
    {
      if (!$input)
        throw new BadUsageException('No input given');

      $this->input = $input;

      mailparse_msg_parse($this->mimemail, $this->input);

      $structure = mailparse_msg_get_structure($this->mimemail);

      foreach ($structure as $part_id) {
          $part = mailparse_msg_get_part($this->mimemail, $part_id);
          $this->parts[$part_id] = mailparse_msg_get_part_data($part);
      }

      $this->headers = $this->parts[1]['headers'];
    }

    public function getHeaders()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return $this->headers;
    }

    public function getSubject()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return isset($this->headers['subject']) ? $this->headers['subject'] : '';
    }

    public function getContentType()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return isset($this->headers['content-type']) ? $this->headers['content-type'] : '';
    }

    public function getCharacterSet()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');
        $content_type = $this->getContentType();
        preg_match('/charset=([0-9A-Za-z-]*)/i', $content_type, $regs);

        return $regs[1];
    }

    public function getDate()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return isset($this->headers['date']) ? $this->headers['date'] : '';
    }

    public function getTo()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return isset($this->headers['to']) ? $this->headers['to'] : '';
    }

    public function getFrom()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return isset($this->headers['from']) ? $this->headers['from'] : '';
    }

    public function getMessageId()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        return isset($this->headers['message-id']) ? $this->headers['message-id'] : '';
    }

    public function getBody()
    {
        if (!$this->parts) throw new BadUsageException('You should call parse() first');

        $body = false;
        $mime_types = array(
            'text/html',
            'text/plain',
        );

        foreach ($this->parts as $part) {
            foreach ($mime_types as $type) {
                if ($this->getPartContentType($part) == $type) {
                    $headers = $this->getPartHeaders($part);
                    $body = $this->decode($this->getPartBody($part), array_key_exists('content-transfer-encoding', $headers) ? $headers['content-transfer-encoding'] : '');

                    return $body;
                }
            }
        }

        return $body;
    }

    public function getAttachments()
    {
        throw new BadUsageException('Still unimplemented');
    }

    private function decode($string, $encoding)
    {
        if ('base64' == strtolower($encoding)) {
            return base64_decode($string);
        } elseif ('quoted-printable' == strtolower($encoding)) {
            return quoted_printable_decode($string);
        }

        return $string;
    }

    private function getPartHeaders(&$part)
    {
        if (isset($part['headers'])) {
            return $part['headers'];
        }

        return array();
    }

    private function getPartBody(&$part)
    {
        return substr($this->input, $part['starting-pos-body'], $part['ending-pos-body'] - $part['starting-pos-body']);
    }

    private function getPartContentType(&$part)
    {
        if (isset($part['content-type'])) {
            return $part['content-type'];
        }

        return false;
    }
}
