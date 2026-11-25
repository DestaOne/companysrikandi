<?php

namespace Tests\Unit;

use App\Helpers\MessageHelper;
use PHPUnit\Framework\TestCase;

class MessageHelperTest extends TestCase
{
    public function test_success_messages_follow_the_action(): void
    {
        $this->assertSame(['success' => 'Layanan berhasil ditambahkan.'], MessageHelper::success('Layanan'));
        $this->assertSame(['success' => 'Layanan berhasil dihapus.'], MessageHelper::success('Layanan', 'deleted'));
        $this->assertSame(['success' => 'Layanan berhasil diurutkan.'], MessageHelper::success('Layanan', 'diurutkan'));
    }

    public function test_error_messages_follow_the_action(): void
    {
        $this->assertSame(['error' => 'Gagal menyimpan layanan.'], MessageHelper::error('Layanan'));
        $this->assertSame(['error' => 'Gagal memperbarui layanan.'], MessageHelper::error('Layanan', 'updated'));
    }

    public function test_custom_message_overrides_the_sentence(): void
    {
        $this->assertSame(['success' => 'Selesai.'], MessageHelper::success('Layanan', message: 'Selesai.'));
        $this->assertSame(['error' => 'Ups.'], MessageHelper::error('Layanan', message: 'Ups.'));
    }
}
