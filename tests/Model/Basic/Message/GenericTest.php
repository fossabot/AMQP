<?php
declare(strict_types = 1);

namespace Tests\Innmind\AMQP\Model\Basic\Message;

use Innmind\AMQP\Model\Basic\{
    Message\Generic,
    Message,
    Message\ContentType,
    Message\ContentEncoding,
    Message\AppId,
    Message\CorrelationId,
    Message\DeliveryMode,
    Message\Id,
    Message\Priority,
    Message\ReplyTo,
    Message\Type,
    Message\UserId
};
use Innmind\TimeContinuum\{
    PointInTimeInterface,
    ElapsedPeriod
};
use Innmind\Immutable\{
    Map,
    Str
};
use PHPUnit\Framework\TestCase;

class GenericTest extends TestCase
{
    public function testInterface()
    {
        $message = new Generic(
            $body = new Str('')
        );

        $this->assertInstanceOf(Message::class, $message);
        $this->assertFalse($message->hasContentType());
        $this->assertFalse($message->hasContentEncoding());
        $this->assertFalse($message->hasHeaders());
        $this->assertFalse($message->hasDeliveryMode());
        $this->assertFalse($message->hasPriority());
        $this->assertFalse($message->hasCorrelationId());
        $this->assertFalse($message->hasReplyTo());
        $this->assertFalse($message->hasExpiration());
        $this->assertFalse($message->hasId());
        $this->assertFalse($message->hasTimestamp());
        $this->assertFalse($message->hasType());
        $this->assertFalse($message->hasUserId());
        $this->assertFalse($message->hasAppId());
        $this->assertSame($body, $message->body());
    }

    public function testContentType()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withContentType(
            $expected = new ContentType('text', 'plain')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasContentType());
        $this->assertTrue($message2->hasContentType());
        $this->assertSame($expected, $message2->contentType());
    }

    public function testContentEncoding()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withContentEncoding(
            $expected = new ContentEncoding('gzip')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasContentEncoding());
        $this->assertTrue($message2->hasContentEncoding());
        $this->assertSame($expected, $message2->contentEncoding());
    }

    public function testHeaders()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withHeaders(
            $expected = new Map('string', 'mixed')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasHeaders());
        $this->assertTrue($message2->hasHeaders());
        $this->assertSame($expected, $message2->headers());
    }

    /**
     * @expectedException TypeError
     * @expectedExceptionMessage Argument 1 must be of type MapInterface<string, mixed>
     */
    public function testThrowWhenInvalidHeaderMap()
    {
        (new Generic(new Str('')))->withHeaders(new Map('string', 'string'));
    }

    public function testDeliveryMode()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withDeliveryMode(
            $expected = DeliveryMode::persistent()
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasDeliveryMode());
        $this->assertTrue($message2->hasDeliveryMode());
        $this->assertSame($expected, $message2->deliveryMode());
    }

    public function testPriority()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withPriority(
            $expected = new Priority(0)
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasPriority());
        $this->assertTrue($message2->hasPriority());
        $this->assertSame($expected, $message2->priority());
    }

    public function testCorrelationId()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withCorrelationId(
            $expected = new CorrelationId('foo')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasCorrelationId());
        $this->assertTrue($message2->hasCorrelationId());
        $this->assertSame($expected, $message2->correlationId());
    }

    public function testReplyTo()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withReplyTo(
            $expected = new ReplyTo('foo')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasReplyTo());
        $this->assertTrue($message2->hasReplyTo());
        $this->assertSame($expected, $message2->replyTo());
    }

    public function testExpiration()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withExpiration(
            $expected = new ElapsedPeriod(1000)
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasExpiration());
        $this->assertTrue($message2->hasExpiration());
        $this->assertSame($expected, $message2->expiration());
    }

    public function testId()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withId(
            $expected = new Id('foo')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasId());
        $this->assertTrue($message2->hasId());
        $this->assertSame($expected, $message2->id());
    }

    public function testTimestamp()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withTimestamp(
            $expected = $this->createMock(PointInTimeInterface::class)
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasTimestamp());
        $this->assertTrue($message2->hasTimestamp());
        $this->assertSame($expected, $message2->timestamp());
    }

    public function testType()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withType(
            $expected = new Type('foo')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasType());
        $this->assertTrue($message2->hasType());
        $this->assertSame($expected, $message2->type());
    }

    public function testUserId()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withUserId(
            $expected = new UserId('foo')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasUserId());
        $this->assertTrue($message2->hasUserId());
        $this->assertSame($expected, $message2->userId());
    }

    public function testAppId()
    {
        $message = new Generic(new Str(''));
        $message2 = $message->withAppId(
            $expected = new AppId('foo')
        );

        $this->assertInstanceOf(Message::class, $message2);
        $this->assertNotSame($message, $message2);
        $this->assertFalse($message->hasAppId());
        $this->assertTrue($message2->hasAppId());
        $this->assertSame($expected, $message2->appId());
    }
}
