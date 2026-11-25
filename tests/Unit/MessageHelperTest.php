<?php

namespace Tests\Unit;

use App\Helpers\MessageHelper;
use PHPUnit\Framework\TestCase;

class MessageHelperTest extends TestCase
{
    public function test_success_message_is_returned_verbatim(): void
    {
        $this->assertSame(['success' => 'Galeri cihuy'], MessageHelper::success('Galeri cihuy'));
    }

    public function test_error_message_is_returned_verbatim(): void
    {
        $this->assertSame(['error' => 'Ups.'], MessageHelper::error('Ups.'));
    }
}
