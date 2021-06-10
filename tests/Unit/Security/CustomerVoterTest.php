<?php

namespace App\Tests\Unit\Security;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use App\Security\Voter\CustomerVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class CustomerVoterTest extends TestCase
{
    public function testItWorks()
    {
        $customerVoter = new CustomerVoter;

        $attributes = [
            'CAN_REMOVE',
            'CAN_EDIT',
            'CAN_LIST_CUSTOMERS',
            'CAN_CREATE_CUSTOMER',
            'CAN_LIST_ALL_CUSTOMERS'
        ];

        $mockTokenInterface = $this->createMock(TokenInterface::class);

        foreach ($attributes as $a) {
            $vote = $customerVoter->vote($mockTokenInterface, null, [$a]);

            $this->assertNotEquals(VoterInterface::ACCESS_ABSTAIN, $vote);
        }
    }

    /**
     * @dataProvider provideAttributeRoleAndResult
     */
    public function testPolicyWithNoSubject(string $attribute, array $roles, int $expectedResponse)
    {
        $customerVoter = new CustomerVoter;

        $user = new User;
        $user->roles = $roles;

        $mockTokenInterface = $this->createMock(TokenInterface::class);
        $mockTokenInterface
            ->expects($this->once())
            ->method('getUser')
            ->willReturn($user);

        $vote = $customerVoter->vote($mockTokenInterface, null, [$attribute]);

        $this->assertEquals($expectedResponse, $vote);
    }

    public function provideAttributeRoleAndResult()
    {
        return [
            ['CAN_CREATE_CUSTOMER', ['ROLE_MODERATOR'], VoterInterface::ACCESS_DENIED],
            ['CAN_CREATE_CUSTOMER', ['ROLE_ADMIN'], VoterInterface::ACCESS_GRANTED],
            ['CAN_CREATE_CUSTOMER', ['ROLE_USER'], VoterInterface::ACCESS_GRANTED],

            ['CAN_LIST_CUSTOMERS', ['ROLE_MODERATOR'], VoterInterface::ACCESS_GRANTED],
            ['CAN_LIST_CUSTOMERS', ['ROLE_ADMIN'], VoterInterface::ACCESS_GRANTED],
            ['CAN_LIST_CUSTOMERS', ['ROLE_USER'], VoterInterface::ACCESS_GRANTED],

            ['CAN_LIST_ALL_CUSTOMERS', ['ROLE_MODERATOR'], VoterInterface::ACCESS_GRANTED],
            ['CAN_LIST_ALL_CUSTOMERS', ['ROLE_ADMIN'], VoterInterface::ACCESS_GRANTED],
            ['CAN_LIST_ALL_CUSTOMERS', ['ROLE_USER'], VoterInterface::ACCESS_DENIED],
        ];
    }
}
