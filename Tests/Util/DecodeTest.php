<?php

namespace Ijanki\Bundle\MailMimeDecodeBundle\Tests\Util;

use Ijanki\Bundle\MailMimeDecodeBundle\Util\MailMimeDecode;
use Ijanki\Bundle\MailMimeDecodeBundle\Util\MailParseDecode;

/**
* Test for MenuHelper class
*
* @author Daniele Cesarini <daniele.cesarini@gmail.com>
*/
class DecodeTest extends \PHPUnit_Framework_TestCase
{
    private $msg1 = <<<EOM
Delivered-To: daniele.cesarini@gmail.com
Received: (qmail 1965 invoked by uid 48); 20 Jun 2012 10:46:02 +0200
To: daniele.cesarini@gmail.com
Subject: Test Subject
Message-ID: <1340181961.4fe18dc9e696a@xxxxxx.it>
Date: Wed, 20 Jun 2012 10:46:01 +0200
From: Foo bar <info@test.lo>
MIME-Version: 1.0
Content-Type: text/plain; charset=utf-8
Content-Transfer-Encoding: quoted-printable

Test message.
Second Line.
EOM;
    
    public function testPhpDecode()
    {
        $decoder = new MailMimeDecode();
        $decoder->parse($this->msg1);
        
        $this->assertEquals('Test Subject', $decoder->getSubject());
    }
    
    public function testPeclDecode()
    {
        $decoder = new MailParseDecode();
        
        $decoder->parse($this->msg1);
        echo $decoder->getBody();
        $this->assertEquals('Test Subject', $decoder->getSubject());
    }
}