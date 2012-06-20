<?php

namespace Ijanki\Bundle\MailMimeDecodeBundle;

interface MailDecoderInterface
{
    public function parse($input);
    public function getHeaders();
    public function getSubject();
    public function getCharacterSet();
    public function getContentType();
    public function getBody();
    public function getDate();
    public function getTo();
    public function getFrom();
    public function getMessageId();
    public function getAttachments();
}