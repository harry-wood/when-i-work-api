<?php

namespace MyBuilder\Bundle\WhenIWorkBundle\Tests\Service\Repository;

use JMS\Serializer\SerializerInterface;
use MyBuilder\Library\WhenIWork\Service\WhenIWorkApi;
use MyBuilder\Library\WhenIWork\Repository\UserRepository;

/**
 * @group unit
 */
class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var SerializerInterface|\Mockery\MockInterface
     */
    private $serializer;

    /**
     * @var WhenIWorkApi|\Mockery\MockInterface
     */
    private $whenIWorkApi;

    public function setUp()
    {
        $this->whenIWorkApi = \Mockery::mock('MyBuilder\Library\WhenIWork\Service\WhenIWorkApi');
        $this->serializer = \Mockery::mock('JMS\Serializer\SerializerInterface');
        $this->userRepository = new UserRepository(
            $this->whenIWorkApi,
            $this->serializer
        );

    }

    public function test_it_delegate_find_by_id_to_api()
    {
        $user = \Mockery::mock('stdClass');
        $userRaw = \Mockery::mock('stdClass');
        $userRaw->user = $user;

        $this->whenIWorkApi
            ->shouldReceive('usersGetExistingUser')
            ->with($someId = 123)
            ->once()
            ->andReturn($userRaw);

        $this->serializer->shouldReceive('deserialize')->once();

        $this->userRepository->findById($someId);
    }

    public function test_it_is_instance_of_when_i_work_repository()
    {
        $this->assertInstanceOf('MyBuilder\Library\WhenIWork\Repository\WhenIWorkRepository', $this->userRepository);
    }


    public function test_it_delegate_find_all_to_api()
    {
        $this->whenIWorkApi
            ->shouldReceive('usersListingUsers')
            ->once();

        $this->serializer->shouldReceive('deserialize')->once();

        $this->userRepository->findAll();
    }
}
